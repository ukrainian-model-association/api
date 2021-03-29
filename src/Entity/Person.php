<?php

/**
 * This file is part of the @modelsua\api package.
 */

namespace App\Entity;

use App\Entity\Person\Album\Image;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
use JsonSerializable;
use App\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 * @ORM\Table(name="person")
 */
class Person implements JsonSerializable
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Person\Album", mappedBy="owner", orphanRemoval=true)
     */
    private Collection $albums;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Person\Album\Image", mappedBy="owner", orphanRemoval=true)
     */
    private Collection $images;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this
            ->setAlbums(new ArrayCollection())
            ->setImages(new ArrayCollection())
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());
    }

    /**
     * @return Collection
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    /**
     * @param Collection $albums
     *
     * @return Person
     */
    public function setAlbums(Collection $albums): Person
    {
        $this->albums = $albums;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param Collection $images
     *
     * @return Person
     */
    public function setImages(Collection $images): Person
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @param Image $image
     *
     * @return $this
     */
    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setOwner($this);
        }

        return $this;
    }

    /**
     * @param Image $image
     *
     * @return $this
     */
    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getOwner() === $this) {
                $image->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id'       => $this->getId(),
            'username' => $this->getUsername(),
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
     * @param int $id
     *
     * @return Person
     */
    public function setId(int $id): Person
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return Person
     */
    public function setPassword(string $password): Person
    {
        $this->password = $password;

        return $this;
    }
}
