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
    private $fromDate;
    private $toDate;
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
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * @return mixed
     */
    public function getToDate()
    {
        return $this->toDate;
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
     * @param mixed $fromDate
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;
    }

    /**
     * @param mixed $toDate
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;
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