<?php
declare(strict_types=1);

require __DIR__ . '/Student.php';

/* ---------- Создание 4 студентов (п. 5) ---------- */
$students = [
    new Student(1001, 'Иванов', 'Иван', 1),
    new Student(1002, 'Петрова', 'Анна', 2),
    new Student(1003, 'Сидоров', 'Пётр', 3),
    new Student(1004, 'Козлова', 'Мария', 4),
];

$students[0]->grades = [5, 4, 5];
$students[0]->email  = 'ivanov@example.com';

$students[1]->grades = [4, 4, 3];
$students[1]->email  = 'petrova@example.com';

$students[2]->grades = [3, 4, 4];
$students[2]->email  = 'sidorov@example.com';

$students[3]->grades = [5, 5, 5];
// email у четвёртого оставляем null — проверим оператор ??

/* ---------- Таблица студентов (п. 6) ---------- */
printf("%-6s %-22s %-6s %-25s %-8s\n", 'Билет', 'ФИО', 'Курс', 'Почта', 'Ср.балл');
printf("%s\n", str_repeat('-', 72));
foreach ($students as $s) {
    printf(
        "%-6d %-22s %-6d %-25s %-8.2f\n",
        $s->id,
        $s->getFullName(),
        $s->course,
        $s->email ?? 'не указана',
        $s->getAverageGrade()
    );
}

/* ---------- Статические свойства (п. 4) ---------- */
echo PHP_EOL . 'Всего студентов: ' . Student::$totalStudents . PHP_EOL;
echo 'Вуз: ' . Student::$university . PHP_EOL;

/* =========================================================
 * Эксперименты (п. 7)
 * Каждый блок — самостоятельный запуск. Проблемная строка
 * закомментирована, рядом — ожидаемый текст ошибки.
 * ========================================================= */
echo PHP_EOL . '===== ЭКСПЕРИМЕНТЫ =====' . PHP_EOL;

/* --- Эксперимент 1: изменить readonly $id --- */
echo PHP_EOL . '[1] Изменение readonly-свойства $id:' . PHP_EOL;
try {
    $students[0]->id = 5;
    // Ожидается: Error: Cannot modify readonly property Student::$id
} catch (Error $e) {
    echo '  ' . $e::class . ': ' . $e->getMessage() . PHP_EOL;
}

/* --- Эксперимент 2: присвоить строку в int $course --- */
echo PHP_EOL . '[2] Присваивание строки в $course (strict_types=1):' . PHP_EOL;
try {
    $students[0]->course = 'два';
    // Ожидается: TypeError: Cannot assign string to property Student::$course of type int
} catch (TypeError $e) {
    echo '  ' . $e::class . ': ' . $e->getMessage() . PHP_EOL;
}

/* --- Эксперимент 3: создать студента с курсом 7 --- */
echo PHP_EOL . '[3] Создание студента с курсом 7:' . PHP_EOL;
try {
    new Student(1005, 'Тестов', 'Тест', 7);
    // Ожидается: InvalidArgumentException из конструктора
} catch (InvalidArgumentException $e) {
    echo '  ' . $e::class . ': ' . $e->getMessage() . PHP_EOL;
}

/* --- Эксперимент 4: изменение статического $university --- */
echo PHP_EOL . '[4] Изменение Student::$university:' . PHP_EOL;
echo '  До:      ' . Student::$university . PHP_EOL;
Student::$university = 'СПбГУ';
echo '  После:   ' . Student::$university . PHP_EOL;
echo '  У объекта: ' . $students[0]::$university . PHP_EOL;
echo '  У другого объекта: ' . $students[1]::$university . PHP_EOL;
echo '  -> изменение видно у всех объектов (свойство принадлежит классу)' . PHP_EOL;

/* --- Эксперимент 5: var_dump() и get_object_vars() --- */
echo PHP_EOL . '[5] var_dump() и get_object_vars():' . PHP_EOL;
var_dump($students[0]);
echo '  get_object_vars(): ' . PHP_EOL;
print_r(get_object_vars($students[0]));

/* --- Дополнительно (на отлично): __set() --- */
echo PHP_EOL . '[+] Запись несуществующего свойства через __set():' . PHP_EOL;
$students[0]->patronymic = 'Иванович';
echo '  history: ' . implode(', ', $students[0]->getHistory()) . PHP_EOL;