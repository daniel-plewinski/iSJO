<?php

namespace AppBundle\Controller;

use AppBundle\Form\ChooseSettlementDateType;
use AppBundle\Form\ChooseTeacherSettlementDateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettlementController extends Controller
{
    /**
     * @Route("/teacher/settlement-date", name="teacher_settlement_date")
     * @param Request $request
     * @return Response
     */
    public function chooseSettlementDate(Request $request)
    {
        $form = $this->createForm(
            ChooseSettlementDateType::class,
            null,
            [
                'action' => $this->generateUrl('teacher-settlement'),
            ]
        );

        return $this->render(
            "choose_settlement_date.html.twig",
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/teacher/settlement", name="teacher-settlement")
     * @param Request $request
     * @return Response
     */
    public function showTeacherSettlement(Request $request)
    {
        $postData = $request->request->get('choose_settlement_date');
        $year = $postData['year'];
        $month = $postData['month'];
        $teacher = $this->getUser()->getId();

        $doctrine = $this->getDoctrine();
        $salary = $doctrine->getRepository('AppBundle:Lesson')
            ->teacherMonthlySalary($year, $month, $teacher);

        $lessons = $doctrine->getRepository('AppBundle:Lesson')
            ->teacherMonthlyLessons($year, $month, $teacher);

        // get course count
        $count = [];
        foreach ($lessons as $key => $lesson) {
            $count[] = $lesson['course_id'];
        }

        $courseCount = count(array_unique($count));

        return $this->render(
            "show_teacher_settlement.twig",
            [
                'year' => $year,
                'month' => $month,
                'salary' => $salary,
                'lessons' => $lessons,
                'courseCount' => $courseCount,
            ]
        );
    }

    /**
     * @Route("/school/settlement_date", name="school_settlement_date")
     * @param Request $request
     * @return Response
     */
    public function chooseSchoolSettlementDate(Request $request)
    {
        $user = $this->getUser()->getId();

        $form = $this->createForm(
            ChooseTeacherSettlementDateType::class,
            null,
            [
                'user' => $user,
                'action' => $this->generateUrl('school_settlement'),
            ]
        );


        return $this->render(
            "choose_school_settlement_date.html.twig",
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/school/settlement", name="school_settlement")
     * @param Request $request
     * @return Response
     */
    public function showSchoolSettlement(Request $request)
    {
        $postData = $request->request->get('appbundle_user');

        $year = $postData['year'];
        $month = $postData['month'];
        $teacher = $postData['teacherId'];

        $doctrine = $this->getDoctrine();
        $salary = $doctrine->getRepository('AppBundle:Lesson')
            ->teacherMonthlySalary($year, $month, $teacher);

        $lessons = $doctrine->getRepository('AppBundle:Lesson')
            ->teacherMonthlyLessons($year, $month, $teacher);

        // get course count
        $count = [];
        foreach ($lessons as $key => $lesson) {
            $count[] = $lesson['course_id'];
        }

        $courseCount = count(array_unique($count));

        return $this->render(
            "show_teacher_settlement.twig",
            [
                'year' => $year,
                'month' => $month,
                'salary' => $salary,
                'lessons' => $lessons,
                'courseCount' => $courseCount,
            ]
        );
    }

    /**
     * @Route("/school/overview-settlement-date", name="overview_settlement_date")
     * @param Request $request
     * @return Response
     */
    public function chooseOverviewSettlementDate(Request $request)
    {
        $form = $this->createForm(
            ChooseSettlementDateType::class,
            null,
            [
                'action' => $this->generateUrl('overview-settlement'),
            ]
        );

        return $this->render(
            "choose_overview_settlement_date.html.twig",
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/school/overview-settlement", name="overview-settlement")
     * @param Request $request
     * @return Response
     */
    public function showOverviewSettlement(Request $request)
    {
        $postData = $request->request->get('choose_settlement_date');
        $year = $postData['year'];
        $month = $postData['month'];
        $schoolId = $this->getUser()->getId();

        $doctrine = $this->getDoctrine();
        $totalSalary = $doctrine->getRepository('AppBundle:Lesson')
            ->salaryOfAllTeachers($year, $month, $schoolId);
        $courseCount = $doctrine->getRepository('AppBundle:Lesson')
            ->countCourses($year, $month, $schoolId);
        $lessonCount = $doctrine->getRepository('AppBundle:Lesson')
            ->countLessons($year, $month, $schoolId);

        $salaryByTeachers = $doctrine->getRepository('AppBundle:Lesson')
            ->salaryByTeachers($year, $month, $schoolId);


        return $this->render(
            "show_overview_settlement.html.twig",
            [
                'year' => $year,
                'month' => $month,
                'totalSalary' => $totalSalary,
                'courseCount' => $courseCount,
                'lessonCount' => $lessonCount,
                'salaryByTeachers' => $salaryByTeachers,
            ]
        );
    }
}
