<?php

namespace App\DataFixtures;

use App\Entity\Foundation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FoundationFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $foundationValues = [
            ['Stichting Nepal', 'http://www.stichtingnepal.org', '5d21faee1c3bd404314803.png'],
            ['The Nepal Trust', 'http://www.nepaltrust.org', '5d2305258f257224624434.png'],
            ['Nepal Federatie Nederland', 'http://nepalfederatie.org', '5d23058e37856133307062.jpg'],
            ['BIKAS association vzw', 'http://www.bikas.org', '5d230609076c2148172974.png'],
            ['Jhuwani Environment Protection Programme', 'http://www.jhuwani-environment.com', '5d93156adf5c7199454684.png'],
            ['Child Rescue Nepal', 'http://www.childrescuenepal.org', '5d9315e4cb747916620037.png'],
            ['RHEST', 'http://www.rhest.org', '5e0490ae83426069608949.png'],
            ['Friends of Sankhu', null, '5e0494ff030a3427759249.png'],
            ['BVS Nepal', 'http://www,bvsnepal.org.np', '5e04957b56675484899351.png'],
            ['DIRDC', 'http://dirdc.org.np', '5e049614bd8b2080194392.png'],
            ['Nepal Development Foundation', null, '5e05d4a11139c749955266.png'],
        ];

        array_walk($foundationValues, function(array $values) use ($manager){
            $foundation = new Foundation();

            $foundation->setName($values[0]);
            $foundation->setUrl($values[1]);
            $foundation->setImage($values[2]);

            $manager->persist($foundation);
        });
        $manager->flush();
    }
}
