<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Tag;

class TagFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_tags', function () {
            $tag = new Tag();
            $tag->setName($this->faker->realText(20));

            return $tag;
        });

        $manager->flush();
    }
}
