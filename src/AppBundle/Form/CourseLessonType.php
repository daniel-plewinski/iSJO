<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CourseLessonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add(
                'date',
                DateType::class,
                [
                    'label' => 'Data: ',
                    'widget' => 'single_text',
                    'attr' => ['class' => 'js-datepicker form-control'],
                    'html5' => false,
                    'format' => 'yyyy-MM-dd',

                ]
            )
            ->add(
                "submit",
                SubmitType::class,
                [
                    "label" => "Dodaj lekcję",

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
                'data_class' => 'AppBundle\Entity\Lesson',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_lesson';
    }


}
