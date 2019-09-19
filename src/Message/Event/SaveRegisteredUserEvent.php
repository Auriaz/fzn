<?php

namespace App\Message\Event;

use App\Entity\User;

class SaveRegisteredUserEvent
{
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
