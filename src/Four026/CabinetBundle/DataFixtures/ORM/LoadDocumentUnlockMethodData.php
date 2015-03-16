<?php
namespace Four026\CabinetBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Four026\CabinetBundle\Entity\DocumentUnlockMethod;
use Four026\CabinetBundle\Entity\PlayerCharacter;

/**
 * Data fixture that loads the standard document unlock methods for double-cross.
 * @package Four026\CabinetBundle\DataFixtures\ORM
 */
class LoadDocumentUnlockMethodData implements FixtureInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $start = new DocumentUnlockMethod();
        $start
            ->setName("Start")
            ->setDescription('Unlocked from the start.');
        $manager->persist($start);

        $password = new DocumentUnlockMethod();
        $password
            ->setName("Password")
            ->setDescription("Unlocked by the specified password.");
        $manager->persist($password);

        $choice = new DocumentUnlockMethod();
        $choice
            ->setName("Choice")
            ->setDescription("Unlocked by selecting the specified choice.");
        $manager->persist($choice);

        $partner = new DocumentUnlockMethod();
        $partner
            ->setName("Partner")
            ->setDescription("Unlocked as soon as the partner unlocks their next document.");
        $manager->persist($partner);

        $manager->flush();
    }
}