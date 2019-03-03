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
    private $cnt;
    private $src         = '';             // image URL
    private $title       = '';             // item title
    private $description = '';             // item description
    private $ID          = '';             // item ID
    private $albumID     = '0';            // parent album ID
    private $kind        = '';             // 'album', 'image'
    private $t_url       = array();        // thumbnails URL
    private $t_width     = array();        // thumbnails width
    private $t_height    = array();        // thumbnails height
    private $dc          = '#888';
    private $dcGIF       = '#000';   // image dominant color
    /**
     * @var string
     */
    private $originalUrl;
    private $imgWidth;
    private $imgHeight;

    /**
     * @return mixed
     */
    public function getCnt()
    {
        return $this->cnt;
    }

    /**
     * @param mixed $cnt
     */
    public function setCnt($cnt)
    {
        $this->cnt = $cnt;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * @param string $src
     */
    public function setSrc(string $src)
    {
        $this->src = $src;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     */
    public function setID(string $ID)
    {
        $this->ID = $ID;
    }

    /**
     * @return string
     */
    public function getAlbumID(): string
    {
        return $this->albumID;
    }

    /**
     * @param string $albumID
     */
    public function setAlbumID(string $albumID)
    {
        $this->albumID = $albumID;
    }

    /**
     * @return string
     */
    public function getKind(): string
    {
        return $this->kind;
    }

    /**
     * @param string $kind
     */
    public function setKind(string $kind)
    {
        $this->kind = $kind;
    }

    /**
     * @return array
     */
    public function getTUrl(): array
    {
        return $this->t_url;
    }

    /**
     * @param array $t_url
     */
    public function setTUrl(array $t_url)
    {
        $this->t_url = $t_url;
    }

    public function addTUrl($key, $value)
    {
        $this->t_url[$key] = $value;
    }

    /**
     * @return array
     */
    public function getTWidth(): array
    {
        return $this->t_width;
    }

    /**
     * @param array $t_width
     */
    public function setTWidth(array $t_width)
    {
        $this->t_width = $t_width;
    }

    public function addTWidth($key, $value)
    {
        $this->t_width[$key] = $value;
    }

    /**
     * @return array
     */
    public function getTHeight(): array
    {
        return $this->t_height;
    }

    /**
     * @param array $t_height
     */
    public function setTHeight(array $t_height)
    {
        $this->t_height = $t_height;
    }

    public function addTHeight($key, $value)
    {
        $this->t_height[$key] = $value;
    }

    /**
     * @return string
     */
    public function getDc(): string
    {
        return $this->dc;
    }

    /**
     * @param string $dc
     */
    public function setDc(string $dc)
    {
        $this->dc = $dc;
    }

    /**
     * @return string
     */
    public function getDcGIF(): string
    {
        return $this->dcGIF;
    }

    /**
     * @param string $dcGIF
     */
    public function setDcGIF(string $dcGIF)
    {
        $this->dcGIF = $dcGIF;
    }

    /**
     * @return string
     */
    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }

    /**
     * @param string $originalUrl
     */
    public function setOriginalUrl(string $originalUrl)
    {
        $this->originalUrl = $originalUrl;
    }

    /**
     * @return mixed
     */
    public function getImgWidth()
    {
        return $this->imgWidth;
    }

    /**
     * @param mixed $imgWidth
     */
    public function setImgWidth($imgWidth)
    {
        $this->imgWidth = $imgWidth;
    }

    /**
     * @return mixed
     */
    public function getImgHeight()
    {
        return $this->imgHeight;
    }

    /**
     * @param mixed $imgHeight
     */
    public function setImgHeight($imgHeight)
    {
        $this->imgHeight = $imgHeight;
    }         // image dominant color

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