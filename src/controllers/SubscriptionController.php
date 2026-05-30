<?php
namespace App\Controllers;

class SubscriptionController
{
    public function __construct(private \mysqli $db) {}

    public function getAll(int $page = 1, int $limit = 20, ?string $search = null, ?string $status = null): array
    {
        $offset = max(0, ($page - 1) * $limit);
        [$where, $types, $params] = $this->buildFilters($search, $status);

        $sql = 'SELECT * FROM subscriptions'
            . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
            . ' ORDER BY created_at DESC LIMIT ? OFFSET ?';

        return $this->runSelect($sql, $types . 'ii', [...$params, $limit, $offset]);
    }

    public function getCount(?string $search = null, ?string $status = null): int
    {
        [$where, $types, $params] = $this->buildFilters($search, $status);

        $sql = 'SELECT COUNT(*) AS cnt FROM subscriptions'
            . ($where ? ' WHERE ' . implode(' AND ', $where) : '');

        $rows = $this->runSelect($sql, $types, $params);
        return (int) ($rows[0]['cnt'] ?? 0);
    }

    public function getById(int $id): ?array
    {
        return $this->runSelect(
            'SELECT * FROM subscriptions WHERE subscription_id = ?', 'i', [$id]
        )[0] ?? null;
    }


    
    private function buildFilters(?string $search, ?string $status): array
    {
        $where = [];
        $types = '';
        $params = [];

        if ($search !== null && $search !== '') {
            $where[] = '(gateway_id LIKE ? OR status LIKE ?)';
            $types .= 'ss';
            $params = [...$params, '%' . $search . '%', '%' . $search . '%'];
        }

        if ($status !== null && $status !== '') {
            $where[] = 'status = ?';
            $types .= 's';
            $params[] = $status;
        }

        return [$where, $types, $params];
    }

    private function runSelect(string $sql, string $types, array $params): array
    {
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new \RuntimeException('SQL prepare failed: ' . $this->db->error);
        }

        if ($types !== '') {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new \RuntimeException('SQL execute failed: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $result?->free();
        $stmt->close();

        return $rows;
    }
    public function create(
    ?string $frequency,
    string  $status,
    string  $startDate,
    int     $creationDate,
    int     $nextBillingDate,
    string  $gatewayId,
    float   $amount,
    int     $userId
): int {
    $stmt = $this->db->prepare(
        'INSERT INTO subscriptions
         (frequency, status, start_date, creation_date, next_billing_date, gateway_id, amount, user_id, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())'
    );
    if ($stmt === false) {
        throw new \RuntimeException('Prepare failed: ' . $this->db->error);
    }
    $stmt->bind_param('sssiisdi',
        $frequency, $status, $startDate,
        $creationDate, $nextBillingDate,
        $gatewayId, $amount, $userId
    );
    if (!$stmt->execute()) {
        throw new \RuntimeException('Execute failed: ' . $stmt->error);
    }
    $id = (int) $this->db->insert_id;
    $stmt->close();
    return $id;
}
}