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
     * @return integer
     */
    public function getIdEntretien()
    {
        return $this->idEntretien;
    }

    /**
     * @param integer $idEntretien
     */
    public function setIdEntretien(int $idEntretien)
    {
        $this->idEntretien = $idEntretien;
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
     * @return string|null
     */
    public function getGarage()
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
     * @return string|null
     */
    public function getLibelle()
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
        $data = get_object_vars($this);
        if ($this->getDate()) {
            $data["date"] = $this->getDate()->format('Y-m-d');
        }
        return $data;
    }

}

