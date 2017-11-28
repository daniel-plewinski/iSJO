<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Form\AddCourseByTeacherType;
use AppBundle\Form\AddCourseType;
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

            $uniqueNumber =  $newCourse->getCourseName() . '-' . time();

            $newCourse->setCourseName($uniqueNumber);
            $newCourse->setSchoolId($schoolId);
            $newCourse->setTeacherId($teacherId);


            $em = $this->getDoctrine()->getManager();
            $em->persist($newCourse);
            $em->flush();

            return $this->render("show_courses.html.twig", []);
        }

        return $this->render("add_course.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/add-course/{id}", name="add_course_id")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function addCourseByTeacher(Request $request, $id)
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
        return $this->render("add_course.html.twig", ['form' => $form->createView()]);
    }
}
