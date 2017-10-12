<?php

namespace AlexBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AthleteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('dateNaissance', BirthdayType::class)
            ->add('photo', FileType::class, array(
                'data_class' => null))
            ->add('pays', EntityType::class, array(
                'class'    => 'AlexBundle:Pays',
                'multiple' => false,
                'expanded' => false
            ))
            ->add('discipline', EntityType::class, array(
                'class'    => 'AlexBundle:Discipline',
                'multiple' => false,
                'expanded' => false
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AlexBundle\Entity\Athlete'
        ));
    }
}
