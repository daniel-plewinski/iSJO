<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SchoolController
 * @package AppBundle\Controller
 * @Route("/teacher")
 */
class TeacherController extends Controller
{
    /**
     * @Route("/", name="index_teacher")
     */
    public function teacherIndexAction()
    {
        $this->denyAccessUnlessGranted(['ROLE_TEACHER'], null, 'Musisz się zalogować');

        return $this->render("index_teacher.html.twig");

    }

    /**
     * @Route("/show-courses", name="show_teacher_courses")
     * @return Response
     */
    public function showTeacherCoursesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $courses = $em->getRepository('AppBundle:Course')
            ->findByTeacherId($this->getUser()->getId());

        return $this->render("show_teacher_courses.html.twig", ['courses' => $courses]);
    }


    /**
     * @Route("/{id}/show-lessons", name="show_lessons")
     * @param Request $request
     * @param Course $course
     * @return Response
     */
    public function showLessonsAction(Request $request, Course $course)
    {
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
            "show_lessons.html.twig",
            ['lessons' => $lessons, 'courseName' => $courseName, 'pagination' => $pagination]
        );
    }
}
