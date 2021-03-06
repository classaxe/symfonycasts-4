<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function($i) use ($manager) {
            $user = new User();
            $user
                ->setEmail(sprintf('spacebar%d@example.com', $i))
                ->setFirstName($this->faker->firstName)
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user, 'engage'
                ))
                ->agreeTerms()
                ->setTwitterUsername($this->faker->boolean ? $this->faker->userName : null)
            ;

            // API Tokens could have its own fixture, adding here just for simplicity
            $apiToken1 = new ApiToken($user);
            $apiToken2 = new ApiToken($user);
            $manager->persist($apiToken1);
            $manager->persist($apiToken2);

            return $user;
        });

        $this->createMany(3, 'admin_users', function($i){
            $user = new User();
            $user
                ->setEmail(sprintf('admin%d@example.com', $i))
                ->setFirstName($this->faker->firstName)
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user, 'engage'
                ))
                ->agreeTerms()
                ->setRoles(['ROLE_ADMIN'])
            ;

            return $user;
        });

        $manager->flush();
    }
}
