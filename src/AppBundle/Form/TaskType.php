<?php

namespace AppBundle\Form;

use \Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Task;

/**
 * Class TaskType.
 * @package AppBundle\Form
 */
class TaskType extends AbstractType
{
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add(
                'priority',
                ChoiceType::class,
                array(
                    'choices' => array(
                        'Low' => 0,
                        'Normal' => 1,
                        'High' => 2,
                    ),
                )
            )
            ->add('deadline', DateType::class)
            ->add('save', SubmitType::class);
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Task::class,
            )
        );
    }
}