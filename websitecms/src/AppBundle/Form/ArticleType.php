<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        $formBuilder
            ->add("title", TextType::class, ['label' => "Tytuł Artykułu"])
            ->add("content", TextareaType::class, ['label' => "Treść artykułu"])
            ->add("submit", SubmitType::class, ['label' => "Dodaj artykuł"]);
    }
}
