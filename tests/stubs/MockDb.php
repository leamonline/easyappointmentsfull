<?php

/**
 * Chainable mock database for unit testing CI3 models.
 *
 * Usage:
 *   $db = new MockDb();
 *   $db->setResult([$row1, $row2]);  // For result_array()
 *   $db->setRowResult($row1);        // For row_array()
 *   $db->setNumRows(1);              // For num_rows()
 *   $db->setInsertId(42);            // For insert_id()
 */
class MockDb
{
    private array $resultArray = [];
    private ?array $rowResult = null;
    private int $numRows = 0;
    private int $insertId = 0;
    private bool $lastQuerySuccess = true;

    // --- Configuration methods ---

    public function setResult(array $rows): self
    {
        $this->resultArray = $rows;
        $this->numRows = count($rows);
        return $this;
    }

    public function setRowResult(?array $row): self
    {
        $this->rowResult = $row;
        $this->numRows = $row ? 1 : 0;
        return $this;
    }

    public function setNumRows(int $count): self
    {
        $this->numRows = $count;
        return $this;
    }

    public function setInsertId(int $id): self
    {
        $this->insertId = $id;
        return $this;
    }

    public function setLastQuerySuccess(bool $success): self
    {
        $this->lastQuerySuccess = $success;
        return $this;
    }

    // --- CI3 query builder chain methods (all return $this) ---

    public function select(...$args): self { return $this; }
    public function from(...$args): self { return $this; }
    public function where(...$args): self { return $this; }
    public function or_where(...$args): self { return $this; }
    public function join(...$args): self { return $this; }
    public function order_by(...$args): self { return $this; }
    public function group_start(): self { return $this; }
    public function group_end(): self { return $this; }
    public function like(...$args): self { return $this; }
    public function or_like(...$args): self { return $this; }
    public function limit(...$args): self { return $this; }
    public function offset(...$args): self { return $this; }

    // --- Terminal query methods ---

    public function get(...$args): self
    {
        return $this;
    }

    public function get_where(string $table, array $where = []): self
    {
        return $this;
    }

    public function insert(string $table, array $data): bool
    {
        return $this->lastQuerySuccess;
    }

    public function update(string $table, array $data, array $where = []): bool
    {
        return $this->lastQuerySuccess;
    }

    public function delete(string $table, array $where = []): bool
    {
        return $this->lastQuerySuccess;
    }

    // --- Result methods ---

    public function result_array(): array
    {
        return $this->resultArray;
    }

    public function row_array(): ?array
    {
        return $this->rowResult;
    }

    public function num_rows(): int
    {
        return $this->numRows;
    }

    public function insert_id(): int
    {
        return $this->insertId;
    }
}
