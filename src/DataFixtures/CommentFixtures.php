<?php


namespace App\DataFixtures;


use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends BaseFixtures implements DependentFixtureInterface
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Comment::class, 100, function(Comment $comment) {
            $comment
                ->setContent(
                    $this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(2,true)
                )
                ->setAuthorName($this->faker->name)
                ->setCreatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'))
                ->setIsDeleted($this->faker->boolean(20))
                ->setArticle($this->getRandomReference(Article::class));

        });
        $manager->flush();
        // TODO: Implement loadData() method.
    }

    public function getDependencies()
    {
        return [
            ArticleFixtures::class
        ];
    }
}