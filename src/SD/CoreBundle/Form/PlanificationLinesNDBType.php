<?php
namespace SD\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SD\CoreBundle\Repository\TimetableRepository;

class PlanificationLinesNDBType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	$builder->add('timetable_MON', EntityType::class, array(
		'class' => 'SDCoreBundle:Timetable',
		'query_builder' => function(TimetableRepository $tr)
						{
						return $tr->getTimetablesQB();
						},
		'choice_label' => 'name'));
	}
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'SD\CoreBundle\Entity\PlanificationLinesNDB'));
    }

    public function getBlockPrefix()
    {
        return 'sd_corebundle_planificationLinesNDB';
    }
}
