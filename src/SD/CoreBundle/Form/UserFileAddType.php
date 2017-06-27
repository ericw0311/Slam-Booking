<?php

namespace SD\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFileAddType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder->add('email', HiddenType::class)
        ->add('lastName', TextType::class, array('label' => 'user.lastName', 'translation_domain' => 'messages'))
        ->add('firstName', TextType::class, array('label' => 'user.firstName', 'translation_domain' => 'messages'))
        ->add('administrator', HiddenType::class, array('data' => 0));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\CoreBundle\Entity\UserFile'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sd_corebundle_userfile';
    }
}
