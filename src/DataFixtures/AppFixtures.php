<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Faker\Factory;
use App\Entity\ArticleLike;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

// USED TO LOAD FAKE DATA !!!!

class AppFixtures extends Fixture
{
    public function __construct(ObjectManager $manager, UserRepository $userRepo, ArticleRepository $articleRep)
    {

        $this->manager = $manager;
        $this->userRepo = $userRepo;
        $this->articleRep = $articleRep;
    }
    /**
     *
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();

        $users = [];
        $users[] = $this->userRepo->FindAll();
        for ($i = 0; $i < sizeof($users); $i++) {
            $article =  $this->articleRep->find($i);
            for ($j = 0; $j < mt_rand(0, 10); $j++) {
                $like = new ArticleLike();
                $like->setArticle($article);
                $like->setUser($users[0]);
                $manager->persist($like);
            }
        }



        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
