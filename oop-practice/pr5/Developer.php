<?php
declare(strict_types=1);

class Developer extends Employee
{
    public function __construct(
        string $name,
        float $baseSalary,
        string $passport,
        private string $level,
    ) {
        parent::__construct($name, $baseSalary, $passport);
    }

    public function getSalary(): float
    {
        $coef = match ($this->level) {
            'junior' => 1.0,
            'middle' => 1.5,
            'senior' => 2.0,
            default  => throw new InvalidArgumentException("неизвестный уровень: {$this->level}"),
        };
        return $this->baseSalary * $coef;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    // проверка для отчёта: private-метод родителя из наследника недоступен
    // public function tryValidate(float $p): void
    // {
    //     $this->validatePercent($p);
    //     // error: call to private method Employee::validatePercent() from scope Developer
    // }

    // проверка для отчёта: final-метод переопределить нельзя
    // public function getMaskedPassport(): string
    // {
    //     return 'x';
    //     // fatal error: cannot override final method Employee::getMaskedPassport()
    // }
}