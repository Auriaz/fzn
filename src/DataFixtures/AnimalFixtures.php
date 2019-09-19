<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use App\FileManager\PhotoFileManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use App\Service\UploaderHelper;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AnimalFixtures extends BaseFixture implements DependentFixtureInterface
{
    private static $animalName = [
        'Dzikus',
        'Edek',
        'Zbinek',
    ];

    private static $animalImages = [
        'dzikusek.jpeg',
        'edek.jpeg',
        'zbinek.jpeg',
    ];

    private $uploaderHelper;

    public function __construct(PhotoFileManager $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_animals', function ($count) use ($manager) {
            $animal = new Animal();
            $animal->setName($this->faker->randomElement(self::$animalName))
                ->setDescription(
                    <<<EOF
Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
**turkey** shank eu pork belly meatball non cupim.

Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
occaecat lorem meatball prosciutto quis strip steak.

Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham hock pork hamburger enim strip steak
mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon
strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur
cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck
fugiat.
EOF
                );

            if ($this->faker->boolean(70)) {
                $animal->setAdoptionAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }

            $imageFilename = $this->fakeUploadImage();

            $animal->setImageFilename($imageFilename);
            $animal->setCategory(2);
            $animal->setIsActive($this->faker->boolean(5));

            return $animal;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            TagFixture::class,
            ArticleFixtures::class,
        ];
    }

    private function fakeUploadImage(): string
    {
        $randomImage = $this->faker->randomElement(self::$animalImages);
        $fs = new Filesystem();
        $targetPath = sys_get_temp_dir() . '/' . $randomImage;
        $fs->copy(__DIR__ . '/images/' . $randomImage, $targetPath, true);

        return $this->uploaderHelper
            ->uploadImage(new UploadedFile($targetPath, $randomImage));
    }
}
