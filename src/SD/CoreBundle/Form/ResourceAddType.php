<?php

namespace SD\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResourceAddType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->add('validateAndCreate', SubmitType::class, array('label' => 'resource.validate.and.create', 'translation_domain' => 'messages'));
    }
    
	public function getParent()
	{
		return ResourceType::class;
	}
}
