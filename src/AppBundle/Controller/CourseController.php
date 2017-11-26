<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Form\AddCourseType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

            $em = $this->getDoctrine()->getManager();
            $em->persist($newCourse);
            $em->flush();

            return $this->render("teacher_added.html.twig", []);
        }

        return $this->render("add_teacher.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/show-teachers", name="show_teachers")
     * @return Response
     */
    public function showTeachersAction()
    {

        $em = $this->getDoctrine()->getManager();
        $teachers = $em->getRepository('AppBundle:User')
            ->findBySchoolId($this->getUser()->getId());

        return $this->render("show_teachers.html.twig", ['teachers' => $teachers]);
    }


    /**
     * @Route("/show-courses", name="show_courses")
     * @return Response
     */
    public function showCoursesAction()
    {

        $em = $this->getDoctrine()->getManager();
        $teachers = $em->getRepository('AppBundle:User')
            ->findBySchoolId($this->getUser()->getId());

        return $this->render("show_groups.html.twig", ['teachers' => $teachers]);
    }



}
