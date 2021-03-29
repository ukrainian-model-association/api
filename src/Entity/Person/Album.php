<?php

/**
 * This file is part of the @modelsua\api package.
 */

namespace App\Entity\Person;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Person;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use DateTime;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Entity\Person\Album\Image;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Person\AlbumRepository")
 * @ORM\Table(name="person_album")
 */
class Album implements JsonSerializable
{
    use TimestampableEntity;

    public const TYPE_DEFAULT       = null;
    public const TYPE_COVERS        = 1;
    public const TYPE_FASHION       = 2;
    public const TYPE_DEFILE        = 3;
    public const TYPE_ADV           = 4;
    public const TYPE_ADVERTISEMENT = 5;
    public const TYPE_CONTEST       = 6;
    public const TYPE_CATALOGS      = 7;
    public const TYPE_PORTFOLIO     = 8;

    public const TYPES = [
        'default'       => self::TYPE_DEFAULT,
        'covers'        => self::TYPE_COVERS,
        'fashion'       => self::TYPE_FASHION,
        'defile'        => self::TYPE_DEFILE,
        'adv'           => self::TYPE_ADV,
        'advertisement' => self::TYPE_ADVERTISEMENT,
        'contest'       => self::TYPE_CONTEST,
        'catalogs'      => self::TYPE_CATALOGS,
        'portfolio'     => self::TYPE_PORTFOLIO,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="albums", cascade={"persist"})
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     */
    private Person $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Person\Album\Image", mappedBy="album", cascade={"persist", "remove"})
     */
    private Collection $images;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Person\Album\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private ?Image $cover;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(name="extra_data", type="json", nullable=true)
     */
    private array $extraData;

    /**
     * Album constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $type            = $this->getType();
        $extraData       = $this->getExtraData();
        $extraData['id'] = $this->getId();

        return [
            'extra_data'  => $extraData,
            'id'          => $this->getId(),
            'owner'       => $this->getOwner(),
            '@type'       => $type,
            'type'        => array_search($type, self::TYPES, true),
            'name'        => $this->getName(),
            'description' => $this->getDescription(),
            'cover'       => $this->getCover(),
            'images'      => $this->getImages(),
            'created_at'  => $this->getCreatedAt()->format('d.m.Y H:i:s'),
        ];
    }

    /**
     * @return null|int
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param null|int $type
     *
     * @return $this
     */
    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtraData(): array
    {
        return $this->extraData;
    }

    /**
     * @param array $extraData
     *
     * @return Album
     */
    public function setExtraData(array $extraData): Album
    {
        $this->extraData = $extraData;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id ?? null;
    }

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
     * @return Album
     */
    public function setOwner(Person $owner): Album
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description ?? null;
    }

    /**
     * @param null|string $description
     *
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return null|Image
     */
    public function getCover(): ?Image
    {
        return $this->cover ?? null;
    }

    /**
     * @param null|Image $cover
     *
     * @return Album
     */
    public function setCover(?Image $cover): Album
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param Collection $images
     *
     * @return Album
     */
    public function setImages(Collection $images): Album
    {
        $this->images = $images->map(
            function (Image $image) {
                return $image
                    ->setOwner($this->getOwner())
                    ->setAlbum($this);
            }
        );

        return $this;
    }
}
