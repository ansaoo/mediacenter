<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 10/02/18
 * Time: 23:46
 */

namespace App\Entity;


class UploadTask
{
    private $file;
    private $send;

    /**
     * @return mixed
     */
    public function getSend()
    {
        return $this->send;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;
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