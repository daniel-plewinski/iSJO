<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Entity\Lesson;
use AppBundle\Form\CourseLessonType;
use AppBundle\Form\TeacherLessonType;
use DateTime;
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

            $dateAdded = new DateTime();
            $dateAdded->format('Y-m-d H:i:s');

            $newLesson->setDateAdded($dateAdded);

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

    /**
     * @Route("/teacher/{id}/add-lesson", name="add_teacher_lesson_course")
     * @param Request $request
     * @param Course $course
     * @return Response
     */
    public function addTeacherLessonCourse(Request $request, Course $course)
    {
        $newLesson = new Lesson();
        $form = $this->createForm(CourseLessonType::class, $newLesson);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $newLesson = $form->getData();

            $teacherId = $this->getUser()->getId();

            $newLesson->setTeacherId($teacherId);

            $em = $this->getDoctrine()->getManager();

            $teacher = $em->getRepository('AppBundle:User')
                ->find($this->getUser()->getId());


            $newLesson->setCourse($course);

            $dateAdded = new DateTime();
            $dateAdded->format('Y-m-d H:i:s');

            $newLesson->setDateAdded($dateAdded);

            $schoolId = $teacher->getSchoolId();
            $newLesson->setSchoolId($schoolId);

            $em->persist($newLesson);
            $em->flush();

            return $this->redirectToRoute("show_teacher_courses");
        }

        return $this->render(
            "add_course_lesson.html.twig",
            [
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/teacher/{id}/delete-lesson", name="delete_teacher_lesson_course")
     * @param $id
     * @return Response
     */
    public function deleteTeacherLessonCourse($id)
    {
        $em = $this->getDoctrine()->getManager();
        $lesson = $em->getRepository('AppBundle:Lesson')->find($id);

        $teacherId = $this->getUser()->getId();

        if (!$lesson) {

            return $this->redirectToRoute("show_teacher_courses");

        } elseif ($lesson->getTeacherId() != $teacherId) {

            return $this->redirectToRoute("show_teacher_courses");
        }

        $courseId = $lesson->getCourse()->getId();

        if ($this->get('time_validator')->isOneDayOld($lesson->getDateAdded())) {
            $message = "Lekcja dodana więcej niż 12 godzin temu nie może być usunięta";
            return $this->redirectToRoute('show_lessons', ['id' => $courseId, 'message' => $message]);
        }

        $em->remove($lesson);
        $em->flush();

        return $this->redirectToRoute('show_lessons', ['id' => $courseId]);
    }
}
