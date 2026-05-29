<?php
namespace App\Controllers;

class SubscriptionController
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function getAll(int $page = 1, int $limit = 20, ?string $search = null, ?string $status = null): array
    {
        $offset = max(0, ($page - 1) * $limit);
        $where = [];
        $types = '';
        $params = [];
        $sql = 'SELECT * FROM subscriptions';

        if ($search !== null && $search !== '') {
            $where[] = '(gateway_id LIKE ? OR status LIKE ?)';
            $types .= 'ss';
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }

        if ($status !== null && $status !== '') {
            $where[] = 'status = ?';
            $types .= 's';
            $params[] = $status;
        }

        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY created_at DESC LIMIT ? OFFSET ?';
        $types .= 'ii';
        $params[] = $limit;
        $params[] = $offset;

        return $this->runSelect($sql, $types, $params);
    }

    public function getCount(?string $search = null, ?string $status = null): int
    {
        $where = [];
        $types = '';
        $params = [];
        $sql = 'SELECT COUNT(*) AS cnt FROM subscriptions';

        if ($search !== null && $search !== '') {
            $where[] = '(gateway_id LIKE ? OR status LIKE ?)';
            $types .= 'ss';
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }

        if ($status !== null && $status !== '') {
            $where[] = 'status = ?';
            $types .= 's';
            $params[] = $status;
        }

        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $rows = $this->runSelect($sql, $types, $params);
        return isset($rows[0]['cnt']) ? (int) $rows[0]['cnt'] : 0;
    }

    public function getById(int $id): ?array
    {
        $rows = $this->runSelect(
            'SELECT * FROM subscriptions WHERE subscription_id = ?',
            'i',
            [$id]
        );

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
