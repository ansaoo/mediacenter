<?php

namespace App\Entity;

use App\Services\NanoPhotosProvider\Entity\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 */
class Picture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Exif", cascade={"persist", "remove"})
     */
    private $exif;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MediaInfo", cascade={"persist", "remove"})
     */
    private $mediainfo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\Column(type="datetime")
     */
    private $added;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $originalFilename;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $fileSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastUpdate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="pictures")
     */
    private $tag;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dcGIF;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $imgHeight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $imgWidth;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $kind;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $originalUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $src;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $t_height;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $t_width;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $t_url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExif(): ?Exif
    {
        return $this->exif;
    }

    public function setExif(?Exif $exif): self
    {
        $this->exif = $exif;

        return $this;
    }

    public function getMediainfo(): ?MediaInfo
    {
        return $this->mediainfo;
    }

    public function setMediainfo(?MediaInfo $mediainfo): self
    {
        $this->mediainfo = $mediainfo;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getAdded(): ?\DateTimeInterface
    {
        return $this->added;
    }

    public function setAdded(\DateTimeInterface $added): self
    {
        $this->added = $added;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    public function setOriginalFilename(string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setFileSize(int $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(?\DateTimeInterface $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

        return $this;
    }

    public function getDc(): ?string
    {
        return $this->dc;
    }

    public function setDc(?string $dc): self
    {
        $this->dc = $dc;

        return $this;
    }

    public function getDcGIF(): ?string
    {
        return $this->dcGIF;
    }

    public function setDcGIF(?string $dcGIF): self
    {
        $this->dcGIF = $dcGIF;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImgHeight(): ?int
    {
        return $this->imgHeight;
    }

    public function setImgHeight(?int $imgHeight): self
    {
        $this->imgHeight = $imgHeight;

        return $this;
    }

    public function getImgWidth(): ?int
    {
        return $this->imgWidth;
    }

    public function setImgWidth(?int $imgWidth): self
    {
        $this->imgWidth = $imgWidth;

        return $this;
    }

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function setKind(?string $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    public function getOriginalUrl(): ?string
    {
        return $this->originalUrl;
    }

    public function setOriginalUrl(?string $originalUrl): self
    {
        $this->originalUrl = $originalUrl;

        return $this;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function setSrc(?string $src): self
    {
        $this->src = $src;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTHeight(): ?string
    {
        return $this->t_height;
    }

    public function setTHeight(?string $t_height): self
    {
        $this->t_height = $t_height;

        return $this;
    }

    public function getTWidth(): ?string
    {
        return $this->t_width;
    }

    public function setTWidth(?string $t_width): self
    {
        $this->t_width = $t_width;

        return $this;
    }

    public function getTUrl(): ?string
    {
        return $this->t_url;
    }

    public function setTUrl(?string $t_url): self
    {
        $this->t_url = $t_url;

        return $this;
    }

    public function setGalleryItem(Item $item): self
    {
        $this->dc = $item->getDc();
        $this->dcGIF = $item->getDcGIF();
        $this->description = $item->getDescription();
        $this->imgHeight = $item->getImgHeight();
        $this->imgWidth = $item->getImgWidth();
        $this->kind = $item->getKind();
        $this->originalUrl = $item->getOriginalUrl();
        $this->src = $item->getSrc();
        $this->t_url = implode(",", $item->getTUrl());
        $this->t_height = implode(",", $item->getTHeight());
        $this->t_width = implode(",", $item->getTWidth());
        $this->title = $item->getTitle();
        return $this;
    }

    public function toNanoGallery()
    {
        return array(
            "description" => $this->description,
            "title" => $this->title,
            "src" => $this->src,
            "ID" => $this->id,
            "albumID" => 0,
            "kind" => $this->kind,
            "t_url" => explode(",", $this->t_url),
            "t_width" => explode(",", $this->t_width),
            "t_height" => explode(",", $this->t_height),
            "dc" => $this->dc,
            "dcGIF" => $this->dcGIF
        );
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

}
