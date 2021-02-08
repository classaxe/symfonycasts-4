<?php


namespace App\Form;


use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'help' => 'Choose something catchy!'
            ])
            ->add('content', TextareaType::class)
            ->add('publishedAt', DateTimeType::class, [
                'widget' =>'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // This is okay because our form is very simple and doesn't need a lot of customising
        $resolver->setDefaults([
            'data_class' => Article::class
        ]);
    }
}