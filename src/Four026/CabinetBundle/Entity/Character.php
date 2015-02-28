<?php

namespace Four026\CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Character
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Character
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The character's name. Must be a member of the $character_names array.
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * The character's biography, presented to users when selecting characters.
     * @var string
     *
     * @ORM\Column(name="bio", type="text")
     */
    private $bio;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Character
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set bio
     *
     * @param string $bio
     * @return Character
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string 
     */
    public function getBio()
    {
        return $this->bio;
    }
}
