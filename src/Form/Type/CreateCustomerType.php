<?php

namespace App\Form\Type;

use App\Entity\Company;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class CreateCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('firstName', TextType::class)
            ->add('phoneNumber', TelType::class)
            ->add('email', EmailType::class)
            ->add('company', EntityType::class, [
                'placeholder' => 'Choose a company',
                'class' => Company::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class)
        ;
    }
}