<?php

class Employee {

    public ?string $id;
    public string $firstName;
    public string $lastName;
    public ?string $role;

    public ?string $pfp;

    public function __construct(?string $id, string $firstName, string $lastName, ?string $role, ?string $pfp) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->role = $role;
        $this->pfp = $pfp;
    }

    public function __toString(): string {
        return sprintf('ID: %s, First name: %s, Last name: %s, Role: %s, Profile picture: %s',
            $this->id, $this->firstName, $this->lastName, $this->role, $this->pfp);
    }

}