<?php
namespace MarksBundle\Form;

use DisciplineBundle\Repository\DisciplineRepository;
use MarksBundle\Entity\Mark;
use ProjectBundle\Repository\ProjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MarkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('project', EntityType::class, array(
            'class'=>'ProjectBundle:Project',
            'query_builder'=>function (ProjectRepository $repository) {
                return $repository->findProjectsByDisciplineIdFormType(16);
            },
            'choice_label' => 'name'
        ));
        $builder->add('mark', NumberType::class);
        $builder->add('comment', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Mark::class,
        ));
    }
}
