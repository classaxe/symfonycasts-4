<?php


namespace App\Form;


use App\Entity\Article;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Article|null $article */
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();

        $builder
            ->add('title', TextType::class, [
                'help' => 'Choose something catchy!'
            ])
            ->add('content', null, [
                'rows' => 15
            ])
            ->add('author', UserSelectTextType::class, [
                'disabled' => $isEdit
            ])
            ->add('location', ChoiceType::class, [
                'choices' => [
                    'The Solar System' => 'solar_system',
                    'Near a star' => 'star',
                    'Interstellar Space' => 'interstellar_space',
                ],
                'placeholder' => 'Please choose a location',
                'required' => false
            ])
        ;

        if ($options['include_published_at']) {
            $builder->add('publishedAt', DateTimeType::class, [
                'widget' =>'single_text'
            ]);
        }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Article|null $data */
                $data = $event->getData();
                if (!$data) {
                    return;
                }
                $this->setupSpecficLocationNameField(
                    $event->getForm(),
                    $data->getLocation()
                );
            }
        );

        $builder->get('location')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->setupSpecficLocationNameField(
                    $form->getParent(), // get the FORM, not just the field
                    $form->getData()
                );
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // This is okay because our form is very simple and doesn't need a lot of customising
        $resolver->setDefaults([
            'data_class' => Article::class,
            'include_published_at' => false
        ]);
    }

    private function getLocationNameChoices(string $location)
    {
        $planets = [
            'Mercury',
            'Venus',
            'Earth',
            'Mars',
            'Jupiter',
            'Saturn',
            'Uranus',
            'Neptune',
        ];
        $stars = [
            'Polaris',
            'Sirius',
            'Alpha Centauari A',
            'Alpha Centauari B',
            'Betelgeuse',
            'Rigel',
            'Other'
        ];
        $locationNameChoices = [
            'solar_system' => array_combine($planets, $planets),
            'star' => array_combine($stars, $stars),
            'interstellar_space' => null,
        ];

        return $locationNameChoices[$location] ?? null;
    }

    private function setupSpecficLocationNameField(FormInterface $form, ?string $location)
    {
        if (null === $location) {
            $form->remove('specificLocationName');

            return;
        }

        $choices = $this->getLocationNameChoices($location);
        if (null === $choices) {
            $form->remove('specificLocationName');

            return;
        }

        $form->add('specificLocationName', ChoiceType::class, [
            'choices' => $choices,
            'placeholder' => 'Where exactly?',
            'required' => false
        ]);

    }
}