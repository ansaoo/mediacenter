<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExifRepository")
 */
class Exif
{
    /**
     * @var string|null
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $exposureTime;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fNumber;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $exposureProgram;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $ISOSpeedRatings;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $exifVersion;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateTimeOriginal;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateTimeDigitized;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $compressedBitsPerPixel;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $exposureBiasValue;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $maxApertureValue;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $meteringMode;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $lightSource;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $flash;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $focalLength;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $makerNote;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $flashPixVersion;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $colorSpace;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $exifImageWidth;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ExifImageLength;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $customRendered;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $exposureMode;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $whiteBalance;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $sceneCaptureType;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $contrast;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $saturation;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $sharpness;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $make;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $model;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $apertureFNumber;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateTime;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $YCbCrPositioning;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $width;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $mimeType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExposureTime(): ?string
    {
        return $this->exposureTime;
    }

    public function setExposureTime(?string $exposureTime): self
    {
        $this->exposureTime = $exposureTime;

        return $this;
    }

    public function getFNumber(): ?string
    {
        return $this->fNumber;
    }

    public function setFNumber(?string $fNumber): self
    {
        $this->fNumber = $fNumber;

        return $this;
    }

    public function getExposureProgram(): ?string
    {
        return $this->exposureProgram;
    }

    public function setExposureProgram(?string $exposureProgram): self
    {
        $this->exposureProgram = $exposureProgram;

        return $this;
    }

    public function getISOSpeedRatings(): ?string
    {
        return $this->ISOSpeedRatings;
    }

    public function setISOSpeedRatings(?string $ISOSpeedRatings): self
    {
        $this->ISOSpeedRatings = $ISOSpeedRatings;

        return $this;
    }

    public function getExifVersion(): ?string
    {
        return $this->exifVersion;
    }

    public function setExifVersion(?string $exifVersion): self
    {
        $this->exifVersion = $exifVersion;

        return $this;
    }

    public function getDateTimeOriginal(): ?\DateTime
    {
        return $this->dateTimeOriginal;
    }

    public function setDateTimeOriginal(?\DateTime $dateTimeOriginal): self
    {
        $this->dateTimeOriginal = $dateTimeOriginal;

        return $this;
    }

    public function getDateTimeDigitized(): ?\DateTime
    {
        return $this->dateTimeDigitized;
    }

    public function setDateTimeDigitized(?\DateTime $dateTimeDigitized): self
    {
        $this->dateTimeDigitized = $dateTimeDigitized;

        return $this;
    }

    public function getCompressedBitsPerPixel(): ?string
    {
        return $this->compressedBitsPerPixel;
    }

    public function setCompressedBitsPerPixel(?string $compressedBitsPerPixel): self
    {
        $this->compressedBitsPerPixel = $compressedBitsPerPixel;

        return $this;
    }

    public function getExposureBiasValue(): ?string
    {
        return $this->exposureBiasValue;
    }

    public function setExposureBiasValue(?string $exposureBiasValue): self
    {
        $this->exposureBiasValue = $exposureBiasValue;

        return $this;
    }

    public function getMaxApertureValue(): ?string
    {
        return $this->maxApertureValue;
    }

    public function setMaxApertureValue(?string $maxApertureValue): self
    {
        $this->maxApertureValue = $maxApertureValue;

        return $this;
    }

    public function getMeteringMode(): ?string
    {
        return $this->meteringMode;
    }

    public function setMeteringMode(?string $meteringMode): self
    {
        $this->meteringMode = $meteringMode;

        return $this;
    }

    public function getLightSource(): ?string
    {
        return $this->lightSource;
    }

    public function setLightSource(?string $lightSource): self
    {
        $this->lightSource = $lightSource;

        return $this;
    }

    public function getFlash(): ?string
    {
        return $this->flash;
    }

    public function setFlash(?string $flash): self
    {
        $this->flash = $flash;

        return $this;
    }

    public function getFocalLength(): ?string
    {
        return $this->focalLength;
    }

    public function setFocalLength(?string $focalLength): self
    {
        $this->focalLength = $focalLength;

        return $this;
    }

    public function getMakerNote(): ?string
    {
        return $this->makerNote;
    }

    public function setMakerNote(?string $makerNote): self
    {
        $this->makerNote = $makerNote;

        return $this;
    }

    public function getFlashPixVersion(): ?string
    {
        return $this->flashPixVersion;
    }

    public function setFlashPixVersion(?string $flashPixVersion): self
    {
        $this->flashPixVersion = $flashPixVersion;

        return $this;
    }

    public function getColorSpace(): ?string
    {
        return $this->colorSpace;
    }

    public function setColorSpace(?string $colorSpace): self
    {
        $this->colorSpace = $colorSpace;

        return $this;
    }

    public function getExifImageWidth(): ?int
    {
        return $this->exifImageWidth;
    }

    public function setExifImageWidth(?int $exifImageWidth): self
    {
        $this->exifImageWidth = $exifImageWidth;

        return $this;
    }

    public function getExifImageLength(): ?int
    {
        return $this->ExifImageLength;
    }

    public function setExifImageLength(?int $ExifImageLength): self
    {
        $this->ExifImageLength = $ExifImageLength;

        return $this;
    }

    public function getCustomRendered(): ?string
    {
        return $this->customRendered;
    }

    public function setCustomRendered(?string $customRendered): self
    {
        $this->customRendered = $customRendered;

        return $this;
    }

    public function getExposureMode(): ?string
    {
        return $this->exposureMode;
    }

    public function setExposureMode(?string $exposureMode): self
    {
        $this->exposureMode = $exposureMode;

        return $this;
    }

    public function getWhiteBalance(): ?string
    {
        return $this->whiteBalance;
    }

    public function setWhiteBalance(?string $whiteBalance): self
    {
        $this->whiteBalance = $whiteBalance;

        return $this;
    }

    public function getSceneCaptureType(): ?string
    {
        return $this->sceneCaptureType;
    }

    public function setSceneCaptureType(?string $sceneCaptureType): self
    {
        $this->sceneCaptureType = $sceneCaptureType;

        return $this;
    }

    public function getContrast(): ?string
    {
        return $this->contrast;
    }

    public function setContrast(?string $contrast): self
    {
        $this->contrast = $contrast;

        return $this;
    }

    public function getSaturation(): ?string
    {
        return $this->saturation;
    }

    public function setSaturation(?string $saturation): self
    {
        $this->saturation = $saturation;

        return $this;
    }

    public function getSharpness(): ?string
    {
        return $this->sharpness;
    }

    public function setSharpness(?string $sharpness): self
    {
        $this->sharpness = $sharpness;

        return $this;
    }

    public function getMake(): ?string
    {
        return $this->make;
    }

    public function setMake(?string $make): self
    {
        $this->make = $make;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getApertureFNumber(): ?string
    {
        return $this->apertureFNumber;
    }

    public function setApertureFNumber(?string $apertureFNumber): self
    {
        $this->apertureFNumber = $apertureFNumber;

        return $this;
    }

    public function getDateTime(): ?\DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(?\DateTime $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getYCbCrPositioning(): ?int
    {
        return $this->YCbCrPositioning;
    }

    public function setYCbCrPositioning(?int $YCbCrPositioning): self
    {
        $this->YCbCrPositioning = $YCbCrPositioning;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getCreatedDateTime(): ?\DateTime
    {
        return ($this->getDateTimeOriginal() ?? $this->getDateTimeDigitized()) ?? $this->getDateTime();
    }
}
