<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AddCourseByTeacherType extends AbstractType
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
