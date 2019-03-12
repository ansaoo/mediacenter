<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 11/02/19
 * Time: 21:55
 */

namespace App\Services\NanoPhotosProvider\Entity;


class Item
{
    /**
     * @var int|null
     */
    private $cnt;
    /**
     * @var string|null
     */
    private $src         = '';             // image URL
    /**
     * @var string|null
     */
    private $title       = '';             // item title
    /**
     * @var string|null
     */
    private $description = '';             // item description
    /**
     * @var string|null
     */
    private $ID          = '';             // item ID
    /**
     * @var string|null
     */
    private $albumID     = '0';            // parent album ID
    /**
     * @var string|null
     */
    private $kind        = '';             // 'album', 'image'
    /**
     * @var array|null
     */
    private $t_url       = array();        // thumbnails URL
    /**
     * @var array|null
     */
    private $t_width     = array();        // thumbnails width
    /**
     * @var array|null
     */
    private $t_height    = array();        // thumbnails height
    /**
     * @var string|null
     */
    private $dc          = '#888';
    /**
     * @var string|null
     */
    private $dcGIF       = '#000';   // image dominant color
    /**
     * @var string|null
     */
    private $originalUrl;
    /**
     * @var int|null
     */
    private $imgWidth;
    /**
     * @var int|null
     */
    private $imgHeight;

    /**
     * @return int|null
     */
    public function getCnt (): int
    {
        return $this->cnt;
    }

    /**
     * @param int|null $cnt
     */
    public function setCnt ( int $cnt )
    {
        $this->cnt = $cnt;
    }

    /**
     * @return null|string
     */
    public function getSrc (): string
    {
        return $this->src;
    }

    /**
     * @param null|string $src
     */
    public function setSrc ( string $src )
    {
        $this->src = $src;
    }

    /**
     * @return null|string
     */
    public function getTitle (): string
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     */
    public function setTitle ( string $title )
    {
        $this->title = $title;
    }

    /**
     * @return null|string
     */
    public function getDescription (): string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription ( string $description )
    {
        $this->description = $description;
    }

    /**
     * @return null|string
     */
    public function getID (): string
    {
        return $this->ID;
    }

    /**
     * @param null|string $ID
     */
    public function setID ( string $ID )
    {
        $this->ID = $ID;
    }

    /**
     * @return null|string
     */
    public function getAlbumID (): string
    {
        return $this->albumID;
    }

    /**
     * @param null|string $albumID
     */
    public function setAlbumID ( string $albumID )
    {
        $this->albumID = $albumID;
    }

    /**
     * @return null|string
     */
    public function getKind (): string
    {
        return $this->kind;
    }

    /**
     * @param null|string $kind
     */
    public function setKind ( string $kind )
    {
        $this->kind = $kind;
    }

    /**
     * @return array|null
     */
    public function getTUrl (): array
    {
        return $this->t_url;
    }

    /**
     * @param array|null $t_url
     */
    public function setTUrl ( array $t_url )
    {
        $this->t_url = $t_url;
    }

    /**
     * @return array|null
     */
    public function getTWidth (): array
    {
        return $this->t_width;
    }

    /**
     * @param array|null $t_width
     */
    public function setTWidth ( array $t_width )
    {
        $this->t_width = $t_width;
    }

    /**
     * @return array|null
     */
    public function getTHeight (): array
    {
        return $this->t_height;
    }

    /**
     * @param array|null $t_height
     */
    public function setTHeight ( array $t_height )
    {
        $this->t_height = $t_height;
    }

    /**
     * @return null|string
     */
    public function getDc (): string
    {
        return $this->dc;
    }

    /**
     * @param null|string $dc
     */
    public function setDc ( string $dc )
    {
        $this->dc = $dc;
    }

    /**
     * @return null|string
     */
    public function getDcGIF (): string
    {
        return $this->dcGIF;
    }

    /**
     * @param null|string $dcGIF
     */
    public function setDcGIF ( string $dcGIF )
    {
        $this->dcGIF = $dcGIF;
    }

    /**
     * @return null|string
     */
    public function getOriginalUrl (): string
    {
        return $this->originalUrl;
    }

    /**
     * @param null|string $originalUrl
     */
    public function setOriginalUrl ( string $originalUrl )
    {
        $this->originalUrl = $originalUrl;
    }

    /**
     * @return null|int
     */
    public function getImgWidth (): ?int
    {
        return $this->imgWidth;
    }

    /**
     * @param null|int $imgWidth
     */
    public function setImgWidth ( ?int $imgWidth )
    {
        $this->imgWidth = $imgWidth;
    }

    /**
     * @return null|int
     */
    public function getImgHeight (): ?int
    {
        return $this->imgHeight;
    }

    /**
     * @param null|int $imgHeight
     */
    public function setImgHeight ( ?int $imgHeight )
    {
        $this->imgHeight = $imgHeight;
    }

    public function addTUrl($key, $value)
    {
        $this->t_url[$key] = $value;
    }

    public function addTWidth($key, $value)
    {
        $this->t_width[$key] = $value;
    }

    public function addTHeight($key, $value)
    {
        $this->t_height[$key] = $value;
    }

    /**
     * @param string $attribute
     * @return mixed
     */
    public function getAttribute(string $attribute)
    {
        return $this->$attribute;
    }

    /**
     * @param string $attribute
     * @param $value
     */
    public function setAttribute(string $attribute, $value)
    {
        $this->$attribute = $value;
    }

    public function toObject()
    {
        return get_object_vars($this);
    }
}