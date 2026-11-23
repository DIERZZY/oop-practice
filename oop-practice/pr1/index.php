<?php
declare(strict_types=1);

require __DIR__ . '/Book.php';
require __DIR__ . '/Library.php';

$lib = new Library();
$lib->addBook(new Book('Мастер и Маргарита', 'М. Булгаков', 1967, 480));
$lib->addBook(new Book('Преступление и наказание', 'Ф. Достоевский', 1866, 672));
$lib->addBook(new Book('Война и мир', 'Л. Толстой', 1869, 1274));
$lib->addBook(new Book('1984', 'Дж. Оруэлл', 1949, 320));
$lib->addBook(new Book('Собачье сердце', 'М. Булгаков', 1925, 160));

$lib->printAll();
echo 'Всего книг: ' . $lib->count() . PHP_EOL;

echo 'Книги Булгакова:' . PHP_EOL;
foreach ($lib->findByAuthor('М. Булгаков') as $b) {
    echo '  ' . $b->getInfo() . PHP_EOL;
}

// Пункт 7: присваивание vs клонирование
$b1 = new Book('Оригинал', 'Автор', 2000, 100);
$b2 = $b1;         // ссылка на тот же объект
$b3 = clone $b1;   // независимая копия
$b1->title = 'Изменённый';
echo $b1->getInfo() . PHP_EOL; // Изменённый
echo $b2->getInfo() . PHP_EOL; // Изменённый (тот же объект!)
echo $b3->getInfo() . PHP_EOL; // Оригинал (копия)

// Дополнительно
$old = $lib->getOldest();
echo 'Самая старая: ' . ($old?->getInfo() ?? 'нет книг') . PHP_EOL;