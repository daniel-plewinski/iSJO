<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class TeacherLessonType extends AbstractType
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
                'course',
                EntityType::class,
                [
                    'class' => 'AppBundle:Course',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('q')
                            ->select('u')
                            ->from('AppBundle:Course', 'u')
                            ->where('u.teacherId = ?1')
                            ->setParameter(1, $this->user);
                    },
                    'label' => 'Wybierz kurs',
                    'choice_label' => 'courseName',
                    'attr' => [
                        'class' => ' form-control',
                    ],
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
                'user' => null,
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
