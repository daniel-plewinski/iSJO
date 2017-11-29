<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use AppBundle\Form\TeacherLessonType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LessonController extends Controller
{
    /**
     * @Route("/teacher/add-lesson", name="add_teacher_lesson")
     * @param Request $request
     * @return Response
     */
    public function addTeacherLesson(Request $request)
    {
        $newLesson = new Lesson();
        $form = $this->createForm(TeacherLessonType::class, $newLesson, ['user' => $this->getUser()]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $newLesson = $form->getData();

            $teacherId = $this->getUser()->getId();

            $newLesson->setTeacherId($teacherId);

            $em = $this->getDoctrine()->getManager();

            $teacher = $em->getRepository('AppBundle:User')
                ->find($this->getUser()->getId());

            $schoolId = $teacher->getSchoolId();
            $newLesson->setSchoolId($schoolId);

            $em->persist($newLesson);
            $em->flush();

            return $this->redirectToRoute("add_teacher_lesson");
        }

        return $this->render(
            "add_teacher_lesson.html.twig",
            [
                'form' => $form->createView(),
            ]
        );
    }
}
