<?php

/**
 * This file is part of the @modelsua\api package.
 */

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Manager\PersonManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Person\Album;
use App\Repository\Person\AlbumRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Person;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Person\Album\Image;

/**
 * @Route("/persons/{personId}/albums", name="person.album.", requirements={"personId": "\d+"})
 */
class UserAlbumController extends AbstractController
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    /** @var PersonManager */
    private PersonManager $personManager;

    /**
     * AlbumController constructor.
     *
     * @param EntityManagerInterface $em
     * @param PersonManager          $manager
     */
    public function __construct(EntityManagerInterface $em, PersonManager $manager)
    {
        $this->personManager = $manager;
        $this->em            = $em;
    }

    /**
     * @Route("", methods={"GET"}, name="index", requirements={"personId": "\d+"})
     *
     * @param int $personId
     *
     * @return JsonResponse
     */
    public function index(int $personId): JsonResponse
    {
        $owner = $this->getOwner($personId);

        return JsonResponse::create($owner->getAlbums()->toArray());
    }

    /**
     * @param int $id
     *
     * @return Person
     */
    private function getOwner(int $id): Person
    {
        $pm = $this->personManager;

        return $pm->getPerson($id);
    }

    /**
     * @Route("/{albumId}", methods={"GET"}, name="read", requirements={"albumId": "\d+"})
     *
     * @param int $personId
     * @param int $albumId
     *
     * @return JsonResponse
     */
    public function read(int $personId, int $albumId): JsonResponse
    {
        /** @var AlbumRepository $repo */
        $repo  = $this->em->getRepository(Album::class);
        $owner = $this->personManager->getPerson($personId);

        return JsonResponse::create($repo->findOneBy(['owner' => $owner, 'id' => $albumId]));
    }

    /**
     * @Route("", methods={"POST", "PUT"}, name="createOrUpdate")
     *
     * @param int     $personId
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createOrUpdate(int $personId, Request $request): JsonResponse
    {
        $typeKey = $request->query->get('type');
        $form    = $request->get($typeKey);
        $type    = $this->getType($typeKey);
        $owner   = $this->getOwner($personId);
        $cover   = (new Image())->setResourceId($form['image']['id']);
        $images  = new ArrayCollection([$cover]);

        $id    = (int) $form['id'];
        $album = $id > 0 ? $this->em->getRepository(Album::class)->find($id) : new Album();

        $album = $this->handleRequest(
            $request,
            $album
                ->setOwner($owner)
                ->setType($type)
                ->setCover($cover)
                ->setImages($images)
                ->setExtraData($form)
        );

        $this->em->persist($album);
        $this->em->flush();

        return JsonResponse::create($album, Response::HTTP_CREATED);
    }

    /**
     * @param string $typeKey
     *
     * @return int
     */
    private function getType(string $typeKey): int
    {
        return array_key_exists($typeKey, Album::TYPES)
            ? Album::TYPES[$typeKey]
            : Album::TYPE_DEFAULT;
    }

    /**
     * @param Request $request
     * @param Album   $album
     *
     * @return Album
     */
    private function handleRequest(Request $request, Album $album): Album
    {
        $form = $request->get($request->query->get('type'));

        switch ($album->getType()) {
            case Album::TYPE_COVERS:
                return $album
                    ->setName($form['journal_name']);

            case Album::TYPE_DEFILE:
                return $album
                    ->setName('');

            case Album::TYPE_CONTEST:
                return $album
                    ->setName($form['contest_event']);

            case Album::TYPE_ADV:
            default:
                return $album
                    ->setName($form['name'])
                    ->setDescription($form['description']);
        }
    }

    /**
     * @Route("/{albumId}", methods={"DELETE"}, name="delete", requirements={"albumId": "\d+"})
     *
     * @param int $albumId
     *
     * @return JsonResponse
     */
    public function delete(int $albumId): JsonResponse
    {
        /** @var AlbumRepository $repo */
        $repo  = $this->em->getRepository(Album::class);
        $album = $repo->findOneById($albumId);

        $this->em->remove($album);
        $this->em->flush();

        return JsonResponse::create($album);
    }
}
