<?php

namespace Ira\Forms\User;

use Ira\Entities\User;

class RegisterForm
{
    private ?string $name;

    private string $email;

    private string $password;

    private string $passwordConfirmation;

    public function setFields(string $email, string $password, string $passwordConfirmation, string $name = null): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }
}
