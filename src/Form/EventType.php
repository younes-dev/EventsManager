<?php

namespace App\Form;

use App\Entity\Event;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('all_day')
//            ->add('startAt', DateTimeType::class, [
//                'widget' => 'single_text',
//                'attr' => ['class' => 'pik'],
//                'label' => 'date start event',
//            ])


            ->add('startAt', DateTimeType::class,[
                'widget' =>'single_text',
                'attr' => ['class' => 'pik'],
                'label' => 'date end event',
//                'html5' => false,
            ])


            ->add('endAt', DateTimeType::class,[
                'widget' =>'single_text',
                'attr' => ['class' => 'pik'],
                'label' => 'date end event',
//                'html5' => false,
            ])


//            ->add('endAt', DateTimeType::class,[
//                'widget' =>'single_text',
//                'attr' => ['class' => 'pik'],
//                'label' => 'date end event',
//            ])
            ->add('background_color',ColorType::class, [
                'attr' => ['class' => 'pik']
            ])
            ->add('border_color',ColorType::class, [
                'attr' => ['class' => 'pik']
            ])
            ->add('text_color',ColorType::class, [
                'attr' => ['class' => 'pik']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
