<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChooseTeacherSettlementDateType extends AbstractType
{

    private $user;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $this->user = $user;

        $builder
            ->add(
                'year',
                ChoiceType::class,
                [
                    'choices' => [
                        '2017' => 2017,
                        '2018' => 2018,
                        '2019' => 2019,
                        '2020' => 2020,
                    ],
                    "label" => "Rok",
                    'attr' => [
                        'class' => ' form-control',
                    ],
                ]
            )
            ->add(
                'month',
                ChoiceType::class,
                [
                    'choices' => [
                        'styczeń' => 1,
                        'luty' => 2,
                        'marzec' => 3,
                        'kwiecień' => 4,
                        'maj' => 5,
                        'czerwiec' => 6,
                        'lipiec' => 7,
                        'sierpień' => 8,
                        'wrzesień' => 9,
                        'październik' => 10,
                        'listopad' => 11,
                        'grudzień' => 12,
                    ],
                    "label" => "Miesic",
                    'attr' => [
                        'class' => ' form-control',
                    ],
                ]
            )
            ->add(
                'teacherId',
                EntityType::class,
                [
                    'class' => 'AppBundle:User',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('q')
                            ->select('u')
                            ->from('AppBundle:User', 'u')
                            ->where('u.schoolId = ?1')
                        ->setParameter(1, $this->user);
                    },
                    'label' => 'Pokaż rozliczenie dla',
                    'choice_label' => 'name',

                    'attr' => [
                        'class' => ' form-control',
                    ],
                ]
            )
            ->add(
                "submit",
                SubmitType::class,
                [
                    "label" => "Pokaż rozliczenie",

                    'attr' => [
                        'class' => 'top-marg btn btn-primary button-top-margin',
                    ],
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => null,
                'user' => null,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }

}
