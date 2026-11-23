<?php
declare(strict_types=1);

class Employee
{
    public string $name;               // имя, доступно всем
    protected float $baseSalary;       // оклад, нужен наследникам
    private string $passport;          // паспорт, только внутри этого класса
    private array $salaryHistory = []; // история изменений оклада

    public function __construct(string $name, float $baseSalary, string $passport)
    {
        $this->name = $name;
        $this->baseSalary = $baseSalary;
        $this->passport = $passport;
    }

    public function getSalary(): float
    {
        return $this->baseSalary;
    }

    public function getNetSalary(): float
    {
        return $this->getSalary() - $this->calculateTax($this->getSalary());
    }

    public function raiseSalary(float $percent): void
    {
        $this->validatePercent($percent);
        $this->baseSalary *= 1 + $percent / 100;
        $this->salaryHistory[] = round($this->baseSalary, 2);
    }

    // final, чтобы наследники не могли переопределить и раскрыть паспорт
    final public function getMaskedPassport(): string
    {
        $digits = preg_replace('/\D/', '', $this->passport);
        $last4 = substr($digits, -4);
        return '**** **' . $last4;
    }

    public function getSalaryHistory(): array
    {
        return $this->salaryHistory;
    }

    protected function calculateTax(float $amount): float
    {
        return $amount * 0.13;
    }

    private function validatePercent(float $p): void
    {
        if ($p < 1 || $p > 50) {
            throw new InvalidArgumentException(
                "процент повышения должен быть от 1 до 50, получено: $p"
            );
        }
    }
}