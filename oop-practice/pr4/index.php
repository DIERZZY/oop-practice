<?php
declare(strict_types=1);

require __DIR__ . '/QueryBuilder.php';

echo QueryBuilder::table('users')
    ->select('id', 'name', 'email')
    ->where('age', '>=', 18)
    ->where('city', '=', 'Москва')
    ->orderBy('name')
    ->limit(10) . PHP_EOL;

echo QueryBuilder::table('products')
    ->where('price', '<', 1000)
    ->orderBy('price', 'DESC') . PHP_EOL;

try {
    QueryBuilder::table('users')->orderBy('name', 'UP');
} catch (InvalidArgumentException $e) {
    echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
}

try {
    QueryBuilder::table('users')->limit(0);
} catch (InvalidArgumentException $e) {
    echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
}

// Дополнительно
echo QueryBuilder::table('users')->whereCity('Москва') . PHP_EOL;