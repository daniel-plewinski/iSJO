<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;


class AddCourseType extends AbstractType
{
    private $user;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $user = $user->getId();

        $this->user = $user;

        $builder
            ->add(
                'courseName',
                TextType::class,
                [
                    'label' => "Nazwa kursu",
                    'attr' => [
                        'class' => ' form-control',
                    ],
                ]
            )
            ->add(
                'subject',
                TextType::class,
                [
                    'label' => "Przedmiot / język",
                    'attr' => [
                        'class' => ' form-control',
                    ],
                ]
            )
            ->add(
                'totalLessons',
                TextType::class,
                [
                    'label' => "Liczba lekcji",
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
                    'label' => 'Kurs poprowadzi nauczyciel',
                    'choice_label' => 'name',

                    'attr' => [
                        'class' => ' form-control',
                    ],
                ]
            )
            ->add(
                'teacherRate',
                NumberType::class,
                [
                    'label' => "Stawka nauczyciela za 1 lekcję",
                    'attr' => [
                        'class' => 'form-control currency',
                        'max' => 90,
                        'step' => 0.01,
                    ],
                ]
            )
            ->add(
                "submit",
                SubmitType::class,
                [
                    "label" => "Dodaj kurs",

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
                'data_class' => 'AppBundle\Entity\Course',
                'user' => null,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_course';
    }


}
