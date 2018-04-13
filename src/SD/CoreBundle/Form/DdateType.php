<?php
namespace SD\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DdateType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('date', DateType::class, array('widget' => 'single_text', 'html5' => false, 'format' => 'dd/MM/yyyy', 'attr' => ['class' => 'datepicker']));
	}
    
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array('data_class' => 'SD\CoreBundle\Entity\Ddate'));
	}

	public function getBlockPrefix()
	{
		return 'sd_corebundle_date';
	}
}
