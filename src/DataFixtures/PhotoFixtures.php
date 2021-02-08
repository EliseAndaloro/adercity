<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Photo;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PhotoFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {
       $photo = new Photo();
       $photo->setFilename('ee945de6f7dde8035874f7f71016afdc.jpg');
       $photo->setTitle('Ljubjana');
       $photo->setSlug('ljubjana');
       $manager->persist($photo);

       $photo2 = new Photo();
       $photo2->setFilename('e1a65317c4e5b2ce3f919880b258c8e5.jpg');
       $photo2->setTitle('La Soča');
       $photo2->setSlug('la-soca');
       $manager->persist($photo2);

       $photo3 = new Photo();
       $photo3->setFilename('351472e0033a7951133eeb1faaf9e111.jpg');
       $photo3->setTitle('Lac de Bled');
       $photo3->setSlug('lac-de-bled');
       $manager->persist($photo3);

       $photo4 = new Photo();
       $photo4->setFilename('4427e283d0a6bce6a927c5d1bc658028.jpg');
       $photo4->setTitle('Lac de Bohinj');
       $photo4->setSlug('lac-de-bohinj');
       $manager->persist($photo4);

       $photo5 = new Photo();
       $photo5->setFilename('1890c7c50b2733378ef664b4880cad94.jpg');
       $photo5->setTitle('Bohinj');
       $photo5->setSlug('bohinj');
       $manager->persist($photo5);

       $photo6 = new Photo();
       $photo6->setFilename('db3053d9b4c1c062b5f5071eaece45c9.jpg');
       $photo6->setTitle('Rivière Ljubjanica à Ljubjana');
       $photo6->setSlug('riviere-ljubjanica-a-ljubjana');
       $manager->persist($photo6);

       $photo7 = new Photo();
       $photo7->setFilename('4bd88edfa0c29ec7ae5ff6dc714c8a8a.jpg');
       $photo7->setTitle('Ville de Scicli en Sicile');
       $photo7->setSlug('ville-de-scicli-en-sicile');
       $manager->persist($photo7);

       $photo8 = new Photo();
       $photo8->setFilename('8ab4ce4a06cfae241824365ad51350cd.jpg');
       $photo8->setTitle('Quartier de Street Art à Ljubjana');
       $photo8->setSlug('quartier-de-street-art-a-ljubjana');
       $manager->persist($photo8);

       $photo9 = new Photo();
       $photo9->setFilename('0c20cbd1c8fee0e10e51cb010d65671e.jpg');
       $photo9->setTitle('Gorges de L"Alcantara');
       $photo9->setSlug('gorges-de-lalcantara');
       $manager->persist($photo9);

        $manager->flush();
    }
}
