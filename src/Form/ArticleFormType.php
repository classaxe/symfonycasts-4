<?php


namespace App\Form;


use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // This is okay because our form is very simple and doesn't need a lot of customising
        $resolver->setDefaults([
            'data_class' => Article::class
        ]);
    }
}