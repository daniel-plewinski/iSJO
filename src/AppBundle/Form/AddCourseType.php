<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('courseName')
            ->add('subject')
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
                            ->setParameter(1,  $this->user);
                    },
                    'label' => 'Wybierz nauczyciela',
                    'choice_label' => 'name',

                    'attr' => [
                        'class' => ' form-control',
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
                'user' => null
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
