<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

}
