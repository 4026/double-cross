<?php
namespace Four026\CabinetBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Four026\CabinetBundle\Entity\Character;

/**
 * Data fixture that loads the standard character data for double-cross.
 * @package Four026\CabinetBundle\DataFixtures\ORM
 */
class LoadCharacterData implements FixtureInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $jan = new Character();
        $jan
            ->setName("Jan")
            ->setBio(
                "He’s a university professor, a few years away from tenure. Bio-chemistry. When he was younger he was ".
                "involved in a research project for an R&D firm owned by the government, meddling in cruel and unusual ".
                "weapons technology. The unit was shut down amid one of the coups before they could complete the most ".
                "unsettling of their research projects – Project Six – and the team was disbanded. But some of them must ".
                "have carried on. Because Jan has been told that Project Six has been completed, and the new government ".
                "want to buy the plans to cement their grip on power."
            );
        $manager->persist($jan);

        $eva = new Character();
        $eva
            ->setName("Eva")
            ->setBio(
                "For years Eva has worked as an analyst at The Firm, a consultancy who specialise in massaging the ".
                "complex relationships between clients who work outside the law. The Firm have had her stationed in this ".
                "godforsaken town for four years, notable for no reason other than for its proximity to the border. She’s ".
                "grown accustomed to her little life, living in a small apartment by the market square, but is desperate ".
                "to move up the echelons of her company and get enough power to get the hell out of here. Her life needs ".
                "to change, and change soon. She has been hungry for her chance to prove herself, and today it has come ".
                "– an unexpected opportunity to jump out of the back room and lead a simple job in the field."
            );
        $manager->persist($eva);

        $manager->flush();
    }
}