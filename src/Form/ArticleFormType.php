<?php

namespace App\Form;

use App\Entity\Article;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    private $userRepository;

    /**
     * ArticleFormType constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Article|null $article */
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();

        $location = $article ? $article->getLocation() : null;

        $builder
            ->add('title', TextType::class, [
                'help' => 'Choose something catchy!',
            ])
            ->add('content', null, [
                'rows' => 15,
            ])
            ->add('author', UserSelectTextType::class, [
                'disabled' => $isEdit,
            ])
            ->add('location', ChoiceType::class, [
                'choices' => [
                    'The Solar System' => 'solar_system',
                    'Near a star' => 'star',
                    'Interstellar Space' => 'interstellar_space',
                ],
                'required' => false,
                'placeholder' => 'Choose a location',
            ]);
        if ($location) {
            $builder->add('specificLocationName', ChoiceType::class, [
                     'placeholder' => 'Where exactly?',
                     'choices' => $this->getLocationNameChoices($location),
                     'required' => false,
                 ]);
        }

        if ($options['include_published_at']) {
            $builder->add('publishedAt', null, [
                'widget' => 'single_text',
            ]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'include_published_at' => false,
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
            'Other',
        ];
        $locationNameChoices = [
            'solar_system' => array_combine($planets, $planets),
            'star' => array_combine($stars, $stars),
            'interstellar_space' => null,
        ];

        return $locationNameChoices[$location];
    }
}
