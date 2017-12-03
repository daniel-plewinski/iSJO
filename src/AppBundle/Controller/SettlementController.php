<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use AppBundle\Form\ChooseSettlementDateType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'action' => $this->generateUrl('teacher_settlement'),
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
     * @Route("/teacher/settlement", name="teacher_settlement")
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

        return $this->render(
            "show_teacher_settlement.twig",
            ['year'=>$year, 'month'=>$month, 'salary'=> $salary]
        );
    }
}
