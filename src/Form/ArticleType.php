<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('art_description')
            ->add('art_prix')
            ->add('art_qte')
            ->add('art_remise')
            // ->add('art_filename')

            // ->add('updated_at')
            ->add('promotion', CheckboxType::class, [
                'required' => false
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'required' => false,
                // 'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('art_imageFile', FileType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
