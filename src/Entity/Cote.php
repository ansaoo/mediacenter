<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cote
 *
 * @ORM\Table(name="cote", indexes={@ORM\Index(name="idx_cote_FK_COTE_voiture_id", columns={"voiture_id"})})
 * @ORM\Entity
 */
class Cote
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_argus", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idArgus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

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

    /**
     * @return int
     */
    public function getIdArgus(): int
    {
        return $this->idArgus;
    }

    /**
     * @param int $idArgus
     */
    public function setIdArgus(int $idArgus)
    {
        $this->idArgus = $idArgus;
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


}

