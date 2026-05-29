<?php
namespace App\Controllers;

class AttributeDefinitionController
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        return $this->runSelect('SELECT * FROM attribute_definitions ORDER BY name', '', []);
    }

    public function getById(int $id): ?array
    {
        $rows = $this->runSelect('SELECT * FROM attribute_definitions WHERE attribute_id = ?', 'i', [$id]);
        return $rows[0] ?? null;
    }

    private function runSelect(string $sql, string $types, array $params): array
    {
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new \RuntimeException('SQL prepare failed: ' . $this->db->error);
        }

        if ($types !== '') {
            $this->bindParams($stmt, $types, $params);
        }

        if (! $stmt->execute()) {
            throw new \RuntimeException('SQL execute failed: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        $rows = [];

        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $result->free();
        }

        $stmt->close();
        return $rows;
    }

    private function bindParams(\mysqli_stmt $stmt, string $types, array $params): void
    {
        if ($params === []) {
            return;
        }

        $refs = [];
        foreach ($params as $key => $value) {
            $refs[$key] = &$params[$key];
        }

        array_unshift($refs, $types);
        call_user_func_array([$stmt, 'bind_param'], $refs);
    }
}
