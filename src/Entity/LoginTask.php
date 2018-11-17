<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 23/03/18
 * Time: 15:02
 */

namespace App\Entity;


class LoginTask
{
    private $client_login;
    private $password;
    private $send;

    /**
     * @return mixed
     */
    public function getClientLogin()
    {
        return $this->client_login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getSend()
    {
        return $this->send;
    }

    /**
     * @param $client_login
     * @return $this
     */
    public function setClientLogin($client_login)
    {
        $this->client_login = $client_login;
        return $this;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
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