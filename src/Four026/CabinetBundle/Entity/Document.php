<?php

namespace Four026\CabinetBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * A single chapter in the story.
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(fields="name", message="Document name already taken")
 */
class Document
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
     * The title of the document.
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * The body text of this document.
     * @var string
     *
     * @ORM\Column(name="bodyText", type="text")
     */
    private $bodyText;

    /**
     * The character that this document will be displayed to.
     * @var PlayerCharacter
     *
     * @ORM\ManyToOne(targetEntity="PlayerCharacter")
     */
    private $character;

    /**
     * The documents that might follow this one in the continuity.
     * @ORM\OneToMany(targetEntity="Document", mappedBy="previousDocument")
     */
    private $nextDocuments;

    /**
     * The document that precedes this one in the continuity.
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="nextDocuments")
     */
    private $previousDocument;

    /**
     * The method by which this document can be unlocked.
     * @var DocumentUnlockMethod
     *
     * @ORM\ManyToOne(targetEntity="DocumentUnlockMethod")
     */
    private $unlockType;

    /**
     * The parameter string for this document's unlock method.
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $unlockParam;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nextDocuments = new ArrayCollection();
    }

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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Document
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getBodyText()
    {
        return $this->bodyText;
    }

    /**
     * Set type
     *
     * @param string $bodyText
     *
     * @return Document
     */
    public function setBodyText($bodyText)
    {
        $this->bodyText = $bodyText;

        return $this;
    }

    /**
     * Get unlockType
     *
     * @return string
     */
    public function getUnlockType()
    {
        return $this->unlockType;
    }

    /**
     * Set unlockType
     *
     * @param string $unlockType
     * @return Document
     */
    public function setUnlockType($unlockType)
    {
        $this->unlockType = $unlockType;

        return $this;
    }

    /**
     * Get unlockParam
     *
     * @return string
     */
    public function getUnlockParam()
    {
        return $this->unlockParam;
    }

    /**
     * Set unlockParam
     *
     * @param string $unlockParam
     * @return Document
     */
    public function setUnlockParam($unlockParam)
    {
        $this->unlockParam = $unlockParam;

        return $this;
    }

    /**
     * Get character
     *
     * @return PlayerCharacter
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * Set character
     *
     * @param PlayerCharacter $character
     * @return Document
     */
    public function setCharacter(PlayerCharacter $character = null)
    {
        $this->character = $character;

        return $this;
    }

    /**
     * Add nextDocuments
     *
     * @param Document $nextDocuments
     * @return Document
     */
    public function addNextDocument(Document $nextDocuments)
    {
        $this->nextDocuments[] = $nextDocuments;

        return $this;
    }

    /**
     * Remove nextDocuments
     *
     * @param Document $nextDocuments
     */
    public function removeNextDocument(Document $nextDocuments)
    {
        $this->nextDocuments->removeElement($nextDocuments);
    }

    /**
     * Get nextDocuments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNextDocuments()
    {
        return $this->nextDocuments;
    }

    /**
     * Get previousDocument
     *
     * @return Document
     */
    public function getPreviousDocument()
    {
        return $this->previousDocument;
    }

    /**
     * Set previousDocument
     *
     * @param Document $previousDocument
     * @return Document
     */
    public function setPreviousDocument(Document $previousDocument = null)
    {
        $this->previousDocument = $previousDocument;

        return $this;
    }
}
