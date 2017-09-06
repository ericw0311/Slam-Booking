<?php
namespace SD\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use SD\CoreBundle\Repository\TimetableRepository;

class PlanificationLinesNDBType extends AbstractType
{
    private $currentFile;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	$this->currentFile = $options['current_file'];


	$builder->add('timetable_MON', EntityType::class, array(
		'label' => false,
		'class' => 'SDCoreBundle:Timetable',
		'query_builder' => function(TimetableRepository $tr)
						{
						return $tr->getTimetablesQB($this->currentFile);
						},
		'choice_label' => 'name'))
			->add('timetable_TUE', EntityType::class, array(
		'label' => false,
		'class' => 'SDCoreBundle:Timetable',
		'query_builder' => function(TimetableRepository $tr)
						{
						return $tr->getTimetablesQB($this->currentFile);
						},
		'choice_label' => 'name'))
			->add('activate_MON', CheckboxType::class, array(
		'label' => false,
		'required' => false));
	}
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'SD\CoreBundle\Entity\PlanificationLinesNDB'));
		$resolver->setRequired('current_file');
    }

    public function getBlockPrefix()
    {
        return 'sd_corebundle_planificationLinesNDB';
    }
}
