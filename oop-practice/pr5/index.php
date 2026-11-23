<?php
declare(strict_types=1);

require __DIR__ . '/Employee.php';
require __DIR__ . '/Manager.php';
require __DIR__ . '/Developer.php';

// создаю сотрудников разных типов
$employees = [
    new Manager('Иван Петров', 80000, '1234 567890', 5),
    new Developer('Анна Смирнова', 100000, '2345 678901', 'senior'),
    new Developer('Пётр Иванов', 60000, '3456 789012', 'junior'),
    new Manager('Ольга Сидорова', 95000, '4567 890123', 3),
];

// немного меняю оклады, чтобы была видна история
$employees[0]->raiseSalary(10);
$employees[1]->raiseSalary(20);

// вывожу таблицу: имя, тип, зарплата, после налога, сколько изменений оклада
printf(
    "%-20s %-12s %14s %14s %10s\n",
    'имя', 'тип', 'зарплата', 'после налога', 'история'
);
printf("%s\n", str_repeat('-', 76));

foreach ($employees as $e) {
    printf(
        "%-20s %-12s %14.2f %14.2f %10s\n",
        $e->name,
        $e::class,
        $e->getSalary(),
        $e->getNetSalary(),
        count($e->getSalaryHistory()) . ' изм.'
    );
}

// небольшая обёртка, чтобы ловить ошибки и печатать их текстом
function run(string $label, callable $fn): void
{
    echo PHP_EOL . $label . PHP_EOL;
    try {
        $fn();
        echo '  ок, ошибки нет' . PHP_EOL;
    } catch (Throwable $e) {
        echo '  ' . $e::class . ': ' . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL . '===== ЭКСПЕРИМЕНТЫ =====' . PHP_EOL;

// 1. имя public, поэтому снаружи читается
run('[1] читаю name снаружи:', function () use ($employees) {
    echo '  $employees[0]->name = ' . $employees[0]->name . PHP_EOL;
});

// 2. оклад protected, снаружи нельзя
run('[2] читаю baseSalary снаружи:', function () use ($employees) {
    echo '  $employees[0]->baseSalary = ' . $employees[0]->baseSalary . PHP_EOL;
    // error: cannot access protected property Employee::$baseSalary
});

// 3. в Manager оклад читается, потому что protected
run('[3] читаю baseSalary в Manager:', function () {
    $manager = new Manager('Тест', 70000, '1111 222233', 2);
    echo '  getSalary() менеджера = ' . $manager->getSalary() . PHP_EOL;
});

// 4. паспорт private, даже наследнику не виден
run('[4] читаю passport в Manager:', function () {
    echo '  из Manager к $this->passport доступа нет (private)' . PHP_EOL;
    // внутри Manager: echo $this->passport;
    // error: cannot access private property Employee::$passport
});

// 5. calculateTax protected, снаружи нельзя
run('[5] вызываю calculateTax() снаружи:', function () use ($employees) {
    echo '  $employees[0]->calculateTax(1000) = ' . $employees[0]->calculateTax(1000) . PHP_EOL;
    // error: call to protected method Employee::calculateTax() from global scope
});

// 6. validatePercent private, из Developer нельзя
run('[6] вызываю validatePercent() в Developer:', function () {
    echo '  из Developer к private-методу родителя доступа нет' . PHP_EOL;
    // внутри Developer: $this->validatePercent(10);
    // error: call to private method Employee::validatePercent() from scope Developer
});

// 7. final-метод переопределить нельзя
run('[7] переопределяю final getMaskedPassport():', function () {
    echo '  если раскомментировать метод в Developer, будет fatal error: ' .
         'cannot override final method Employee::getMaskedPassport()' . PHP_EOL;
});

// показываю маску паспорта и историю оклада
echo PHP_EOL . '===== ДОПОЛНИТЕЛЬНО =====' . PHP_EOL;
echo 'паспорт (маска): ' . $employees[0]->getMaskedPassport() . PHP_EOL;
echo 'история оклада менеджера: ' . implode(', ', $employees[0]->getSalaryHistory()) . PHP_EOL;

// проверяю, что raiseSalary не даст поставить 80%
echo PHP_EOL . '[+] raiseSalary(80):' . PHP_EOL;
try {
    $employees[0]->raiseSalary(80);
} catch (InvalidArgumentException $e) {
    echo '  ' . $e::class . ': ' . $e->getMessage() . PHP_EOL;
}