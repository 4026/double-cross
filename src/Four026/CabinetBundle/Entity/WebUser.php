<?php

namespace Four026\CabinetBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * WebUser
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(fields="username", message="Username already taken")
 * @UniqueEntity(fields="email_address", message="Email already taken")
 */
class WebUser implements UserInterface, \Serializable
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
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=30, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email_address", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email_address;

    /**
     * The bcrypt-hash of the user's password
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    private $password;

    /**
     * The player's partner.
     * @var WebUser
     * @ORM\OneToOne(targetEntity="WebUser")
     **/
    private $partner;

    /**
     * The documents that this user has unlocked.
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Document")
     * @ORM\JoinTable(name="webusers_documents",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id")}
     *      )
     */
    private $unlocked_documents;

    /**
     * The notes that this user has unlocked.
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Note")
     * @ORM\JoinTable(name="webusers_notes",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="note_id", referencedColumnName="id")}
     *      )
     */
    private $unlocked_notes;


    public function __construct()
    {
        $this->unlocked_documents = new ArrayCollection();
        $this->unlocked_notes = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return WebUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return WebUser
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * Set email_address
     *
     * @param string $emailAddress
     * @return WebUser
     */
    public function setEmailAddress($emailAddress)
    {
        $this->email_address = $emailAddress;

        return $this;
    }

    /**
     * Get email_address
     *
     * @return string 
     */
    public function getEmailAddress()
    {
        return $this->email_address;
    }

    /**
     * Add unlocked_documents
     *
     * @param Document $unlockedDocuments
     * @return WebUser
     */
    public function addUnlockedDocument(Document $unlockedDocuments)
    {
        $this->unlocked_documents[] = $unlockedDocuments;

        return $this;
    }

    /**
     * Remove unlocked_documents
     *
     * @param Document $unlockedDocuments
     */
    public function removeUnlockedDocument(Document $unlockedDocuments)
    {
        $this->unlocked_documents->removeElement($unlockedDocuments);
    }

    /**
     * Get unlocked_documents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUnlockedDocuments()
    {
        return $this->unlocked_documents;
    }

    /**
     * Add unlocked_notes
     *
     * @param Note $unlockedNotes
     * @return WebUser
     */
    public function addUnlockedNote(Note $unlockedNotes)
    {
        $this->unlocked_notes[] = $unlockedNotes;

        return $this;
    }

    /**
     * Remove unlocked_notes
     *
     * @param Note $unlockedNotes
     */
    public function removeUnlockedNote(Note $unlockedNotes)
    {
        $this->unlocked_notes->removeElement($unlockedNotes);
    }

    /**
     * Get unlocked_notes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUnlockedNotes()
    {
        return $this->unlocked_notes;
    }

    /**
     * Set partner
     *
     * @param WebUser $partner
     * @return WebUser
     */
    public function setPartner(WebUser $partner = null)
    {
        $this->partner = $partner;

        return $this;
    }

    /**
     * Get partner
     *
     * @return WebUser
     */
    public function getPartner()
    {
        return $this->partner;
    }
}
