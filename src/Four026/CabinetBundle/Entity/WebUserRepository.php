<?php

namespace Four026\CabinetBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * WebUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WebUserRepository extends EntityRepository
{
    /**
     * Find the user with the specified username.
     * @param string $username
     * @return WebUser|null The WebUser instance or NULL if the entity can not be found.
     */
    public function findOneByUsername($username)
    {
        return $this->findOneBy(['username' => $username]);
    }
}
