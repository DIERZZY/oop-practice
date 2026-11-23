<?php
declare(strict_types=1);

// Приоритет у Composer: если vendor/autoload.php существует — используем его.
if (is_file(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} elseif (is_file(__DIR__ . '/autoload.php')) {
    require __DIR__ . '/autoload.php';
} else {
    throw new RuntimeException(
        'Не найден ни vendor/autoload.php, ни autoload.php. ' .
        'Выполните composer dump-autoload или создайте autoload.php.'
    );
}