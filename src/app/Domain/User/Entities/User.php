<?php

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserPassword;

class User
{
    private string $id;
    private string $name;
    private UserEmail $email;
    private UserPassword $password;
    private bool $is_admin;

    public function __construct(
        string $id,
        string $name,
        UserEmail $email,
        UserPassword $password,
        bool $is_admin
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->is_admin = $is_admin;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    public function getPassword(): UserPassword
    {
        return $this->password;
    }

    public function getIsAdmin(): bool
    {
        return $this->is_admin;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(UserEmail $email): void
    {
        $this->email = $email;
    }

    public function setPassword(UserPassword $password): void
    {
        $this->password = $password;
    }

    public function setIsAdmin(bool $is_admin): void
    {
        $this->is_admin = $is_admin;
    }

    /**
     * エンティティを配列に変換する
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email->value(),
            'password' => $this->password->value(),
            'is_admin' => $this->is_admin,
        ];
    }
}
