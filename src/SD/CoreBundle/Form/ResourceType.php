<?php

namespace SD\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('label' => 'resource.name', 'translation_domain' => 'messages'))
			->add('backgroundColor', TextType::class, array('label' => 'resource.planning.background.color', 'translation_domain' => 'messages',
			'attr' => ['class' => 'simple_color_custom_slam_booking']))
			->add('foregroundColor', TextType::class, array('label' => 'resource.planning.foreground.color', 'translation_domain' => 'messages',
			'attr' => ['class' => 'simple_color_custom_slam_booking']));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\CoreBundle\Entity\Resource'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sd_corebundle_resource';
    }


}
