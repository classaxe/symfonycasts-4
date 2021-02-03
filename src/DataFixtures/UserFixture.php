<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function($i){
            $user = new User();
            $user
                ->setEmail(sprintf('spacebar%d@example.com', $i))
                ->setFirstName($this->faker->firstName);

            return $user;
        });

        $manager->flush();
    }
}
