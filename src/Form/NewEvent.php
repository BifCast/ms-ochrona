<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Events;

class NewEvent extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Data i czas zdarzenia',
                'attr' => [
                    'min' => (new \DateTime('-12 hours'))->format('Y-m-d\TH:i'),
                    'max' => (new \DateTime('now'))->format('Y-m-d\TH:i'),
                ], // podwojne sprawdzenie na wszelki wypadek
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Opis zdarzenia',
                'attr' => [
                    'maxlength' => 500,
                    'minlength' => 5,
                    'rows' => 5
                ]
            ])

            ->add('typeID', ChoiceType::class, [
                'label' => 'Typ zdarzenia',
                'choices' => [
                    'Interwencja' => 1,
                    'Zgłoszenie mieszkańca' => 2,
                    'Incydent agresji' => 3,
                    'Zdarzenie drogowe' => 4,
                    'Inne' => 5,
                ],
                'placeholder' => 'Wybierz typ zdarzenia',
            ])

            ->add('place', TextType::class, [
                'label' => 'Miejsce',
                'attr' => [
                    'maxlength' => 100,
                    'minlength' => 5,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Events::class
        ]);
    }
}
