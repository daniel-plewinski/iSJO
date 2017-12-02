<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Form\AddCourseByTeacherType;
use AppBundle\Form\AddCourseType;
use AppBundle\Form\EditCourseType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SchoolController
 * @package AppBundle\Controller
 * @Route("/school")
 */
class CourseController extends Controller
{
    /**
     * @Route("/add-course", name="add_course")
     * @param Request $request
     * @return Response
     */
    public function addCourse(Request $request)
    {
        $newCourse = new Course();
        $form = $this->createForm(AddCourseType::class, $newCourse, ['user' => $this->getUser()]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $newCourse = $form->getData();

            $schoolId = $this->getUser()->getId();

            $teacherId = $newCourse->getTeacherId()->getId();

            $newCourse->setSchoolId($schoolId);
            $newCourse->setTeacherId($teacherId);


            $em = $this->getDoctrine()->getManager();
            $em->persist($newCourse);
            $em->flush();

            return $this->redirectToRoute("show_courses");
        }

        return $this->render("add_course.html.twig", ['form' => $form->createView()]);
    }


    /**
     * @Route("/edit-course/{id}", name="edit_course")
     * @param Request $request
     * @param Course $course
     * @return Response
     */
    public function editCourse(Request $request, Course $course)
    {
        $user = $course->getSchoolId();

        $form = $this->createForm(EditCourseType::class, $course, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $newCourse = $form->getData();

            $newCourse->setTeacherId($newCourse->getTeacherId()->getId());

            $em = $this->getDoctrine()->getManager();
            $em->persist($newCourse);
            $em->flush();

            return $this->redirectToRoute("show_courses");
        }

        return $this->render("edit_course.html.twig", ['form' => $form->createView()]);
    }


    /**
     * @Route("/add-course/{id}", name="add_course_id")
     * @param Request $request
     * @param $id
     * @return Response
     */
    function addCourseByTeacher(Request $request, $id)
    {
        $newCourse = new Course();
        $form = $this->createForm(AddCourseByTeacherType::class, $newCourse, ['user' => $this->getUser()]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $newCourse = $form->getData();

            $schoolId = $this->getUser()->getId();

            $teacherId = $id;

            $uniqueNumber = $newCourse->getCourseName().'-'.time();

            $newCourse->setCourseName($uniqueNumber);
            $newCourse->setSchoolId($schoolId);
            $newCourse->setTeacherId($teacherId);


            $em = $this->getDoctrine()->getManager();
            $em->persist($newCourse);
            $em->flush();

            return $this->redirectToRoute('show_courses');
        }

        return $this->render("add_course_teacher.html.twig", ['form' => $form->createView()]);
    }
}
