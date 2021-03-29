<?php

/**
 * This file is part of the @modelsua\api package.
 */

namespace App\Entity\Person\Album;

use App\Entity\Person;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Person\Album;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Person\Album\ImageRepository")
 * @ORM\Table(name="person_album_image")
 */
class Image implements JsonSerializable
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="images")
     * @ORM\JoinColumn(name="`user_id`", referencedColumnName="id")
     */
    private Person $owner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person\Album", inversedBy="images")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private ?Album $album;

    /**
     * @ORM\Column(type="integer")
     */
    private int $resourceId;

    /**
     * @return Person
     */
    public function getOwner(): Person
    {
        return $this->owner;
    }

    /**
     * @param Person $owner
     *
     * @return $this
     */
    public function setOwner(Person $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return int[]
     */
    public function jsonSerialize(): array
    {
        return [
            'id'         => $this->getId(),
            'resourceId' => $this->getResourceId(),
        ];
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    /**
     * @return int
     */
    public function getResourceId(): int
    {
        return $this->resourceId;
    }

    /**
     * @param int $resourceId
     *
     * @return $this
     */
    public function setResourceId(int $resourceId): self
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    /**
     * @return null|Album
     */
    public function getAlbum(): ?Album
    {
        return $this->album ?? null;
    }

    /**
     * @param Album $album
     *
     * @return Image
     */
    public function setAlbum(Album $album): Image
    {
        $this->album = $album;

        return $this;
    }
}
