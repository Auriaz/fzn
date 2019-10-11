<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
use App\FileManager\PhotoFileManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleFixtures extends BaseFixture implements DependentFixtureInterface
{
    private static $articleTitles = [
        'Dlaczego koty lubią miałczeć',
        'Czy psy szczekaja',
        'Życie domowe zwierząt',
    ];

    private static $articleImages = [
        'dzikusek.jpeg',
        'edek.jpeg',
        'zbinek.jpeg',
    ];

    private $photoFileManager;

    public function __construct(PhotoFileManager $photoFileManager)
    {
        $this->photoFileManager = $photoFileManager;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_articles', function ($count) use ($manager) {
            $article = new Article();
            $article->setTitle($this->faker->randomElement(self::$articleTitles))
                ->setContent(
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

            // publish most articles
            if ($this->faker->boolean(70)) {
                $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }
          
            $imageFilename = $this->fakeUploadImage();

            $article->setAuthor($this->getRandomReference('main_users'))
                ->setHeartCount($this->faker->numberBetween(5, 100))
                ->setImageFilename( $imageFilename)
                // ->setArticleReferences($imageFilename, $this->faker->numberBetween(0, 5))
                ;

            $tags = $this->getRandomReferences('main_tags', $this->faker->numberBetween(0, 5));
            foreach ($tags as $tag) {
                $article->addTag($tag);
            }

            return $article;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            TagFixture::class,
        ];
    }

    private function fakeUploadImage(): string
    {
        $randomImage = $this->faker->randomElement(self::$articleImages);
        $fs = new Filesystem();
        $targetPath = sys_get_temp_dir() . '/' . $randomImage;
        $fs->copy(__DIR__ . '/images/' . $randomImage, $targetPath, true);

        return $this->photoFileManager
            ->uploadArticleImage(new UploadedFile($targetPath, $randomImage), null);
    }
}
