<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('searchtext', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Search...']
            ])
            ->add('min', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Minimum Price...']
            ])
            ->add('max', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Maximum Price...']
            ])
            ->add('promotion', CheckboxType::class, [
                'label' => ' Promotion !!!',
                'required' => false
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'required' => false,
                // 'choice_label' => 'name',
                'multiple' => true
            ]);
        // ->add('min', IntegerType::class)
        // ->add('max', IntegerType::class)
        // ->add('categories');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
        ]);
    }
}
