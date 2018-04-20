<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 17/04/18
 * Time: 23:41
 */

namespace App\Entity;


class ImgSearchTask
{
    private $keyword;
    private $send;

    /**
     * @return mixed
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @return mixed
     */
    public function getSend()
    {
        return $this->send;
    }

    /**
     * @param mixed $keyword
     * @return $this
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }
    /**
     * @param mixed $send
     * @return $this
     */
    public function setSend($send)
    {
        $this->send = $send;
        return $this;
    }
}