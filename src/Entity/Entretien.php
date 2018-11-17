<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entretien
 *
 * @ORM\Table(name="entretien", indexes={@ORM\Index(name="idx_entretien_FK_ENTRETIEN_voiture_id", columns={"voiture_id"})})
 * @ORM\Entity
 */
class Entretien
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_entretien", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEntretien;

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
     * @var string
     *
     * @ORM\Column(name="garage", type="string", length=255, nullable=true)
     */
    private $garage;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=255, nullable=true)
     */
    private $lieu;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var integer
     *
     * @ORM\Column(name="voiture_id", type="integer", nullable=true)
     */
    private $voitureId;

    private $submit;

    /**
     * @return int
     */
    public function getIdEntretien(): int
    {
        return $this->idEntretien;
    }

    /**
     * @param int $idEntretien
     */
    public function setIdEntretien(int $idEntretien)
    {
        $this->idEntretien = $idEntretien;
    }

    /**
     * @return int
     */
    public function getCompteur(): int
    {
        return $this->compteur;
    }

    /**
     * @param int $compteur
     */
    public function setCompteur(int $compteur)
    {
        $this->compteur = $compteur;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
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
     * @return string
     */
    public function getGarage(): string
    {
        return $this->garage;
    }

    /**
     * @param string $garage
     */
    public function setGarage(string $garage)
    {
        $this->garage = $garage;
    }

    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle(string $libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return string
     */
    public function getLieu(): string
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
     * @return float
     */
    public function getPrix(): float
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
     * @return int
     */
    public function getVoitureId(): int
    {
        return $this->voitureId;
    }

    /**
     * @param int $voitureId
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

