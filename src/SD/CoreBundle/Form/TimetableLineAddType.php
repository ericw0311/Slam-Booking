<?php

namespace SD\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TimetableLineAddType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->add('validateAndCreate', SubmitType::class, array('label' => 'timetableLine.validate.and.create', 'translation_domain' => 'messages'));
    }
    
	public function getParent()
	{
	return TimetableLineType::class;
	}
}
