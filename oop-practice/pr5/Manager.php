<?php
declare(strict_types=1);

class Manager extends Employee
{
    public function __construct(
        string $name,
        float $baseSalary,
        string $passport,
        private int $teamSize,
    ) {
        parent::__construct($name, $baseSalary, $passport);
    }

    public function getSalary(): float
    {
        // baseSalary protected, поэтому наследник может его читать
        return $this->baseSalary + 5000 * $this->teamSize;
    }

    public function getTeamSize(): int
    {
        return $this->teamSize;
    }

    // проверяю, что protected-метод родителя доступен из наследника
    public function taxOf(float $amount): float
    {
        return $this->calculateTax($amount);
    }
}