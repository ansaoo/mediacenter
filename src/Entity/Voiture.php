<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Voiture
 *
 * @ORM\Table(name="voiture")
 * @ORM\Entity
 */
class Voiture
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_voiture", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idVoiture;

    /**
     * @var integer
     *
     * @ORM\Column(name="cv", type="integer", nullable=true)
     */
    private $cv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_sortie", type="date", nullable=true)
     */
    private $dateSortie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_achat", type="date", nullable=true)
     */
    private $dateAchat;

    /**
     * @var string
     *
     * @ORM\Column(name="marque", type="string", length=255, nullable=true)
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="modele", type="string", length=255, nullable=true)
     */
    private $modele;

    /**
     * @var string
     *
     * @ORM\Column(name="motorisation", type="string", length=255, nullable=true)
     */
    private $motorisation;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_achat", type="float", precision=10, scale=0, nullable=true)
     */
    private $prixAchat;

    /**
     * @var string
     *
     * @ORM\Column(name="serie", type="string", length=255, nullable=true)
     */
    private $serie;

    /**
     * @return int
     */
    public function getIdVoiture(): int
    {
        return $this->idVoiture;
    }

    /**
     * @param int $idVoiture
     */
    public function setIdVoiture(int $idVoiture)
    {
        $this->idVoiture = $idVoiture;
    }

    /**
     * @return int
     */
    public function getCv(): int
    {
        return $this->cv;
    }

    /**
     * @param int $cv
     */
    public function setCv(int $cv)
    {
        $this->cv = $cv;
    }

    /**
     * @return \DateTime
     */
    public function getDateSortie(): \DateTime
    {
        return $this->dateSortie;
    }

    /**
     * @param \DateTime $dateSortie
     */
    public function setDateSortie(\DateTime $dateSortie)
    {
        $this->dateSortie = $dateSortie;
    }

    /**
     * @return \DateTime
     */
    public function getDateAchat(): \DateTime
    {
        return $this->dateAchat;
    }

    /**
     * @param \DateTime $dateAchat
     */
    public function setDateAchat(\DateTime $dateAchat)
    {
        $this->dateAchat = $dateAchat;
    }

    /**
     * @return string
     */
    public function getMarque(): string
    {
        return $this->marque;
    }

    /**
     * @param string $marque
     */
    public function setMarque(string $marque)
    {
        $this->marque = $marque;
    }

    /**
     * @return string
     */
    public function getModele(): string
    {
        return $this->modele;
    }

    /**
     * @param string $modele
     */
    public function setModele(string $modele)
    {
        $this->modele = $modele;
    }

    /**
     * @return string
     */
    public function getMotorisation(): string
    {
        return $this->motorisation;
    }

    /**
     * @param string $motorisation
     */
    public function setMotorisation(string $motorisation)
    {
        $this->motorisation = $motorisation;
    }

    /**
     * @return float
     */
    public function getPrixAchat(): float
    {
        return $this->prixAchat;
    }

    /**
     * @param float $prixAchat
     */
    public function setPrixAchat(float $prixAchat)
    {
        $this->prixAchat = $prixAchat;
    }

    /**
     * @return string
     */
    public function getSerie(): string
    {
        return $this->serie;
    }

    /**
     * @param string $serie
     */
    public function setSerie(string $serie)
    {
        $this->serie = $serie;
    }

    public function _toArray()
    {
        $data = get_object_vars($this);
        if ($this->getDateSortie()) {
            $data["dateSortie"] = $this->getDateSortie()->format('Y-m-d');
        }
        if ($this->getDateAchat()) {
            $data["dateAchat"] = $this->getDateAchat()->format('Y-m-d');
        }
        return $data;
    }
}

