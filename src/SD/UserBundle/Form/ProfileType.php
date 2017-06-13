<?php
// src/SD/UserBundle/Form/ProfileType.php

namespace SD\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\OptionsResolver\OptionsResolver;

use SD\UserBundle\Entity\User;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder->add('accountType', ChoiceType::class, array(
        'label' => 'user.account.type',
        'translation_domain' => 'messages',
        'choices' => array('INDIVIDUAL' => 'INDIVIDUAL', 'ORGANISATION' => 'ORGANISATION'),
        'choice_label' => function ($value, $key, $index) { return 'user.account.type.'.$key; }
        ))
    ->add('lastName', TextType::class, array('label' => 'user.lastName', 'translation_domain' => 'messages'))
    ->add('firstName', TextType::class, array('label' => 'user.firstName', 'translation_domain' => 'messages'))
    ->add('uniqueName', TextType::class, array('label' => 'user.organisation.name', 'translation_domain' => 'messages', 'required' => false));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => User::class));
    }
    
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'sd_userbundle_profile';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}