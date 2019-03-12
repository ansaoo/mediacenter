<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScanRepository")
 */
class Scan
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $refId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_files;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $progress;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percent;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_finish;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_error;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $error_log;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefId(): ?int
    {
        return $this->refId;
    }

    public function setRefId(int $refId): self
    {
        $this->refId = $refId;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalFiles(): ?int
    {
        return $this->total_files;
    }

    public function setTotalFiles(int $total_files): self
    {
        $this->total_files = $total_files;

        return $this;
    }

    public function getProgress(): ?int
    {
        return $this->progress;
    }

    public function setProgress(?int $progress): self
    {
        $this->progress = $progress;

        return $this;
    }

    public function getPercent(): ?float
    {
        return $this->percent;
    }

    public function setPercent(?float $percent): self
    {
        $this->percent = $percent;

        return $this;
    }

    public function getIsFinish(): ?bool
    {
        return $this->is_finish;
    }

    public function setIsFinish(?bool $is_finish): self
    {
        $this->is_finish = $is_finish;

        return $this;
    }

    public function getIsError(): ?bool
    {
        return $this->is_error;
    }

    public function setIsError(?bool $is_error): self
    {
        $this->is_error = $is_error;

        return $this;
    }

    public function getErrorLog(): ?string
    {
        return $this->error_log;
    }

    public function setErrorLog(?string $error_log): self
    {
        $this->error_log = $error_log;

        return $this;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}
