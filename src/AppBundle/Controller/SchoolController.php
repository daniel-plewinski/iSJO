<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Form\AddTeacherType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SchoolController
 * @package AppBundle\Controller
 * @Route("/school")
 */
class SchoolController extends Controller
{
    /**
     * @Route("/", name="index_school")
     */
    public function schoolIndexAction()
    {
        $this->denyAccessUnlessGranted(['ROLE_SCHOOL'], null, 'Musisz się zalogować');


        $em = $this->getDoctrine()->getManager();
        $teachers = $em->getRepository('AppBundle:User')
            ->findBySchoolId($this->getUser()->getId());
        $teacherNo = count($teachers);
        $courses = $em->getRepository('AppBundle:Course')
            ->findBySchoolId($this->getUser()->getId());
        $courseNo = count($courses);

        return $this->render("index_school.html.twig", ['teacherNo' => $teacherNo, 'courseNo' => $courseNo]);
    }

    /**
     * @Route("/add-teacher", name="add_teacher")
     * @param Request $request
     * @return Response
     */
    public function addTeacherAction(SessionInterface $session, Request $request)
    {
        $this->denyAccessUnlessGranted(['ROLE_SCHOOL'], null, 'Musisz się zalogować');

        $newTeacher = new User();
        $form = $this->createForm(AddTeacherType::class, $newTeacher);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $newTeacher = $form->getData();

            $schoolId = $this->getUser()->getId();

            // generate password
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $count = mb_strlen($chars);

            for ($i = 0, $password = ''; $i < 8; $i++) {
                $index = rand(0, $count - 1);
                $password .= mb_substr($chars, $index, 1);
            }

            $session->set('password', $password);

            $newTeacher->setPlainPassword($password);
            $newTeacher->setSchoolId($schoolId);
            $newTeacher->addRole("ROLE_TEACHER");
            $newTeacher->removeRole("ROLE_SCHOOL");
            $newTeacher->setEnabled(1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newTeacher);
            $em->flush();

            return $this->render("teacher_added.html.twig", ['newTeacher' => $newTeacher, 'password' => $password]);
        }

        return $this->render("add_teacher.html.twig", ['form' => $form->createView()]);
    }


    /**
     * @Route("/edit-teacher/{id}", name="edit_teacher")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function editTeacherAction(Request $request, User $user)
    {
        $this->denyAccessUnlessGranted(['ROLE_SCHOOL'], null, 'Musisz się zalogować');

        $form = $this->createForm(AddTeacherType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $newTeacher = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($newTeacher);
            $em->flush();

            return $this->render("teacher_added.html.twig", ['newTeacher' => $newTeacher]);
        }

        return $this->render("add_teacher.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/show-teachers", name="show_teachers")
     * @return Response
     */
    public function showTeachersAction()
    {
        $this->denyAccessUnlessGranted(['ROLE_SCHOOL'], null, 'Musisz się zalogować');

        $em = $this->getDoctrine()->getManager();
        $teachers = $em->getRepository('AppBundle:User')
            ->findBySchoolId($this->getUser()->getId());

        return $this->render("show_teachers.html.twig", ['teachers' => $teachers]);
    }


    /**
     * @Route("/show-courses", name="show_courses")
     * @param Request $request
     * @return Response
     */
    public function showCoursesAction(Request $request)
    {
        $this->denyAccessUnlessGranted(['ROLE_SCHOOL'], null, 'Musisz się zalogować');

        // Cannot user repository as is not supported by knp_paginator
        $schoolId = $this->getUser()->getId();

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT u FROM AppBundle:Course u WHERE u.schoolId = $schoolId";
        $courses = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $courses, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        $teachers = $em->getRepository('AppBundle:User')
            ->findBySchoolId($this->getUser()->getId());

        $teacherAr = [];

        foreach ($teachers as $index => $teacher) {
            $teacherAr[$teachers[$index]->getId()] = $teachers[$index]->getName();
        }

        return $this->render("show_courses.html.twig", ['pagination' => $pagination, 'teacherAr'=> $teacherAr]);
    }

    /**
     * @Route("/{id}/show-lessons", name="shows_school_lessons")
     * @param Request $request
     * @param Course $course
     * @return Response
     */
    public function showLessonsAction(Request $request, Course $course)
    {
        $this->denyAccessUnlessGranted(['ROLE_SCHOOL'], null, 'Musisz się zalogować');

        $courseId = $course->getId();

        // Cannot user repository as is not supported by knp_paginator
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT u FROM AppBundle:Lesson u WHERE u.course = $courseId";
        $lessons = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $lessons, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        $courseName = $course->getCourseName();

        return $this->render(
            "show_school_lessons.html.twig",
            ['lessons' => $lessons, 'courseName' => $courseName, 'pagination' => $pagination]
        );
    }
}
