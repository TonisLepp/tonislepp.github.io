<?php

class Task {

    public ?string $id;
    public string $description;
    public ?string $difficulty;
    public ?string $employee_id;

    public function __construct(?string $id, string $description, ?string $difficulty, ?string $employee_id) {
        $this->id = $id;
        $this->description = $description;
        $this->difficulty = $difficulty;
        $this->employee_id = $employee_id;
    }

    public function __toString(): string {
        return sprintf('ID: %s, Description: %s, Difficulty: %s, Assigned employee: %s',
            $this->id, $this->description, $this->difficulty, $this->employee_id);
    }

}