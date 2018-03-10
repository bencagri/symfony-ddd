<?php

namespace App\Aurora\Infrastructure\User;

use Doctrine\ORM\Mapping as ORM;

class User {

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

}