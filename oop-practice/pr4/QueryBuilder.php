<?php
declare(strict_types=1);

class QueryBuilder
{
    private string $table = '';
    private array $columns = ['*'];
    private array $wheres = [];
    private array $orders = [];
    private ?int $limit = null;

    private const OPERATORS = ['=', '!=', '>', '<', '>=', '<=', 'LIKE'];

    public static function table(string $name): static
    {
        $qb = new static();
        $qb->table = $name;
        return $qb;
    }

    public function select(string ...$columns): static
    {
        if ($columns !== []) {
            $this->columns = $columns;
        }
        return $this;
    }

    public function where(string $column, string $operator, string|int $value): static
    {
        $op = strtoupper($operator);
        if (!in_array($op, self::OPERATORS, true)) {
            throw new InvalidArgumentException("Недопустимый оператор: $operator");
        }
        $quoted = is_string($value) ? "'" . addslashes($value) . "'" : (string)$value;
        $this->wheres[] = "$column $op $quoted";
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $dir = strtoupper($direction);
        if (!in_array($dir, ['ASC', 'DESC'], true)) {
            throw new InvalidArgumentException("Направление только ASC или DESC");
        }
        $this->orders[] = "$column $dir";
        return $this;
    }

    public function limit(int $count): static
    {
        if ($count <= 0) {
            throw new InvalidArgumentException('limit должен быть больше 0');
        }
        $this->limit = $count;
        return $this;
    }

    public function toSql(): string
    {
        $sql = 'SELECT ' . implode(', ', $this->columns) . ' FROM ' . $this->table;
        if ($this->wheres !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        if ($this->orders !== []) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orders);
        }
        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;
        }
        return $sql;
    }

    public function __toString(): string
    {
        return $this->toSql();
    }

    // Дополнительно: ->whereCity('Москва') => where('city', '=', 'Москва')
    public function __call(string $name, array $args): static
    {
        if (str_starts_with($name, 'where') && count($args) === 1) {
            $column = lcfirst(substr($name, 5));
            return $this->where($column, '=', $args[0]);
        }
        throw new BadMethodCallException("Метод $name не существует");
    }
}