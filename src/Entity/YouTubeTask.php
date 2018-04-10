<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 08/04/18
 * Time: 15:33
 */

namespace App\Entity;


class YouTubeTask
{
    private $url;
    private $send;

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSend()
    {
        return $this->send;
    }

    /**
     * @param $send
     * @return $this
     */
    public function setSend($send)
    {
        $this->send = $send;
        return $this;
    }

}