<?php
namespace App\Form\DataTransformer;

use App\Entity\User;
use App\Repository\UserRepository;
use LogicException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EmailToUserTransformer implements DataTransformerInterface
{
    /** @var UserRepository */
    private $userRepository;
    /** @var callable */
    private $finderCallback;

    // This doesn't get autowired, so the caller must provide UserRepository

    public function __construct(
        UserRepository $userRepository,
        callable $finderCallback
    ) {
        $this->userRepository = $userRepository;
        $this->finderCallback = $finderCallback;
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }
        if (!$value instanceof User) {
            throw new LogicException('The UserSelectTextType can only be used with User objects');
        }
        return $value->getEmail();
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }
        $callback = $this->finderCallback;
        $user = $callback($this->userRepository, $value);
        if (!$user) {
            throw new TransformationFailedException(sprintf('No user found with email "%s"', $value));
        }
        return $user;
    }
}