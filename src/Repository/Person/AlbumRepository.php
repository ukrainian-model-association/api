<?php

/**
 * This file is part of the @modelsua\api package.
 */

namespace App\Repository\Person;

use App\Entity\Person\Album;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method null|Album find($id, $lockMode = null, $lockVersion = null)
 * @method null|Album findOneBy(array $criteria, array $orderBy = null)
 * @method null|Album findOneById(int $albumId)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    /**
     * AlbumRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }
}
