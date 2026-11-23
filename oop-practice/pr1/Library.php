<?php
declare(strict_types=1);

class Library
{
    private array $books = [];

    public function addBook(Book $book): void
    {
        $this->books[] = $book;
    }

    public function findByAuthor(string $author): array
    {
        return array_values(array_filter(
            $this->books,
            fn(Book $b) => $b->author === $author
        ));
    }

    public function count(): int
    {
        return count($this->books);
    }

    public function printAll(): void
    {
        foreach ($this->books as $book) {
            echo $book->getInfo() . PHP_EOL;
        }
    }

    // Дополнительно (на отлично)
    public function getOldest(): ?Book
    {
        if ($this->books === []) {
            return null;
        }
        $oldest = $this->books[0];
        foreach ($this->books as $b) {
            if ($b->year < $oldest->year) {
                $oldest = $b;
            }
        }
        return $oldest;
    }
}