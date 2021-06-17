<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',
                TextType::class,
                [
                    'label' => 'Название:'
                ]
            )
            ->add('price', IntegerType::class,
                [
                    'label' => 'Цена'
                ]
            )
            ->add('description',
                TextType::class,
                [
                    'label' => 'Описание:'
                ]
            )
            ->add('LongDescription',
                TextareaType::class,
                [
                    'label' => 'Длинное описание'
                ]
            )
            ->add('image', FileType::class, [
                'mapped' => false,
                'label' => "Выберите фото",
                'required' => false,
                'attr' => array('accept' => 'image/jpeg,image/png')
            ])
            ->add('category', EntityType::class, ['label' => 'категория', 'class' => Category::class, 'choice_label' => 'title'])
            ->add('isActive', CheckboxType::class, ['label' => 'Активен?']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
