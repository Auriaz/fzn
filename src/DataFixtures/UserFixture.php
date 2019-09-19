<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\ApiToken;

class UserFixture extends BaseFixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function($i) use ($manager) {
            $user = new User();
            $user->setEmail(sprintf('spacebar%d@example.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user-> setLastName($this->faker->lastName);
            $user->agreeToTerms();
            $user->setIsActive($this->faker->boolean($i));
            if($this->faker->boolean) {
                $user-> setNick($this->faker->name);
                $user->setTwitterUsername($this->faker->userName);
            }

            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'foo'
                )
            );

            $apiToken1 = new ApiToken($user);
            $apiToken2 = new ApiToken($user);

            $manager->persist($apiToken1);
            $manager->persist($apiToken2);

            return $user;
        });

        $this->createMany(3, 'admin_users', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@example.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setNick($this->faker->name);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setIsActive($this->faker->boolean($i));
            $user->agreeToTerms();

            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'foo'
                )
            );

            return $user;
        });
        $manager->flush();
    }
}
