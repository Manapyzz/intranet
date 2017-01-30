<?php

namespace DisciplineBundle\Form;

use DisciplineBundle\Entity\Discipline;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class StudentDisciplineType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name', EntityType::class, array(
            'class' => 'DisciplineBundle:Discipline',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->select('m')
                    ->from('DisciplineBundle\Entity\Discipline','m')
                    ->leftJoin('m.students','c')
                    ->having('COUNT(c.id) = 0')
                    ->groupBy('m.id');

            },
            'choice_label' => 'name',
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class_discipline' => 'DisciplineBundle\Entity\Discipline'
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'disciplinebundle_discipline';
    }

}
