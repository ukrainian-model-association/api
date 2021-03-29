<?php

/**
 * This file is part of the @modelsua\api package.
 */

namespace App\Manager;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserManager.
 */
class PersonManager
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $manager;

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param int $userId
     *
     * @return null|Person|object
     */
    public function getPerson(int $userId)
    {
        $repository = $this->manager->getRepository(Person::class);

        return $repository->find($userId);
    }

    /**
     * @param string $username
     *
     * @return Person
     */
    public function createPerson(string $username): Person
    {
        $person = new Person();
        $person->setUsername($username);

        return $person;
    }
}
