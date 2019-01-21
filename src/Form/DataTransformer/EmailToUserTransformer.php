<?php

namespace App\Form\DataTransformer;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EmailToUserTransformer implements DataTransformerInterface
{
    private $userRepository;

    /**
     * EmailToUserTransformer constructor.Because this class is not instantiated by Symfony's container.
     * we pass UserRepository in UserSelectTextType.php.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get a property by a Model.
     *
     * @param mixed $value
     *
     * @return mixed|string|null
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        //Sanity check
        if (!$value instanceof User) {
            throw new \LogicException('The UserSelectTextType can only be used with User objects');
        }

        return $value->getEmail();
    }

    /**
     * Get the Model by a property given. Need user Repository to query by property.
     *
     * @param mixed $value
     *
     * @return User|mixed|null
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return;
        }

        $user = $this->userRepository->findOneBy(['email' => $value]);
        if (!$user) {
            throw new TransformationFailedException(sprintf('No user found with email "%s"', $value));
        }

        return $user;
    }
}
