<?php

namespace App\Message;

use App\Entity\User;

class AddEmailFromRegrister
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser():User 
    {
        return $this->user;
    }
}

