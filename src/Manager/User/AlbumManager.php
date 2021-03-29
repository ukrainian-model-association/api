<?php

/**
 * This file is part of the @modelsua\api package.
 */

namespace App\Manager\User;

use App\Repository\Person\AlbumRepository;
use App\Repository\PersonRepository;
use App\Entity\Person;
use Doctrine\Common\Collections\Collection;
use App\Entity\Person\Album;

/**
 * Class AlbumManager.
 */
class AlbumManager
{
    /** @var AlbumRepository */
    private AlbumRepository $repository;

    /** @var PersonRepository */
    private PersonRepository $userRepository;

    /**
     * AlbumManager constructor.
     *
     * @param AlbumRepository  $repository
     * @param PersonRepository $userRepository
     */
    public function __construct(AlbumRepository $repository, PersonRepository $userRepository)
    {
        $this->repository     = $repository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Person $owner
     *
     * @return Album[]|Collection
     */
    public function getAlbumsByOwner(Person $owner)
    {
        return $this->repository->findBy(['owner' => $owner]);
    }
}
