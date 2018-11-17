<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carburant
 *
 * @ORM\Table(name="carburant", indexes={@ORM\Index(name="idx_carburant_FK_CARBURANT_voiture_id", columns={"voiture_id"})})
 * @ORM\Entity
 */
class Carburant
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_carburant", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCarburant;

    /**
     * @var integer
     *
     * @ORM\Column(name="compteur", type="integer", nullable=true)
     */
    private $compteur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="kilometre", type="float", precision=10, scale=0, nullable=true)
     */
    private $kilometre;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=255, nullable=true)
     */
    private $lieu;

    /**
     * @var float
     *
     * @ORM\Column(name="litre", type="float", precision=10, scale=0, nullable=true)
     */
    private $litre;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="station", type="string", length=255, nullable=true)
     */
    private $station;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="voiture_id", type="integer", nullable=true)
     */
    private $voitureId;

    private $submit;

    /**
     * @return integer
     */
    public function getIdCarburant()
    {
        return $this->idCarburant;
    }

    /**
     * @param integer $idCarburant
     */
    public function setIdCarburant(int $idCarburant)
    {
        $this->idCarburant = $idCarburant;
    }

    /**
     * @return integer
     */
    public function getCompteur()
    {
        return $this->compteur;
    }

    /**
     * @param integer $compteur
     */
    public function setCompteur(int $compteur)
    {
        $this->compteur = $compteur;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return float|null
     */
    public function getKilometre()
    {
        return $this->kilometre;
    }

    /**
     * @param float $kilometre
     */
    public function setKilometre(float $kilometre)
    {
        $this->kilometre = $kilometre;
    }

    /**
     * @return string|null
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * @param string $lieu
     */
    public function setLieu(string $lieu)
    {
        $this->lieu = $lieu;
    }

    /**
     * @return float|null
     */
    public function getLitre()
    {
        return $this->litre;
    }

    /**
     * @param float $litre
     */
    public function setLitre(float $litre)
    {
        $this->litre = $litre;
    }

    /**
     * @return float|null
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param float $prix
     */
    public function setPrix(float $prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return string|null
     */
    public function getStation()
    {
        return $this->station;
    }

    /**
     * @param string $station
     */
    public function setStation(string $station)
    {
        $this->station = $station;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return integer
     */
    public function getVoitureId()
    {
        return $this->voitureId;
    }

    /**
     * @param integer $voitureId
     */
    public function setVoitureId(int $voitureId)
    {
        $this->voitureId = $voitureId;
    }

    /**
     * @return mixed
     */
    public function getSubmit()
    {
        return $this->submit;
    }

    /**
     * @param mixed $submit
     */
    public function setSubmit($submit)
    {
        $this->submit = $submit;
    }

    public function _toArray()
    {
        return get_object_vars($this);
    }

}

