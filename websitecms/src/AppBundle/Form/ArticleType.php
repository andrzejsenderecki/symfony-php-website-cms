<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use AppBundle\Entity\Article;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class, ['label' => "Tytuł Artykułu"])
            ->add("content", TextareaType::class, ['label' => "Treść artykułu"])
            ->add("image", FileType::class, array('data_class' => null), ['label' => "Dodaj obraz"])
            ->add("submit", SubmitType::class, ['label' => "Dodaj artykuł"])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => Article::class,
    ));
    }

}
