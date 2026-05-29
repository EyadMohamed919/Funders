<?php
namespace App\Controllers;



class SubscriptionController {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }


    public function getAll(int $page = 1, int $limit = 20): array {
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare(
            "SELECT * FROM subscriptions ORDER BY created_at DESC LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }

    /**
     * Get total count of subscriptions
     */
    public function getCount(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM subscriptions");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get subscription by ID with its entities
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT * FROM subscriptions WHERE subscription_id = :id
        ");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get subscription entities by subscription ID
     */
    public function getEntities(int $subscriptionId): array {
        $stmt = $this->db->prepare(
            "SELECT se.*, GROUP_CONCAT(CONCAT(ad.name, ':', sav.value) SEPARATOR '|') as attributes
             FROM subscription_entities se
             LEFT JOIN subscription_attribute_values sav ON se.entity_id = sav.entity_id
             LEFT JOIN attribute_definitions ad ON sav.attribute_id = ad.attribute_id
             WHERE se.subscription_id = :sub_id
             GROUP BY se.entity_id"
        );
        $stmt->bindValue(':sub_id', $subscriptionId, \PDO::PARAM_INT);
        $stmt->execute();

        $entities = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $entities[] = $row;
        }

        return $entities;
    }

 
    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO subscriptions 
            (frequency, status, start_date, creation_date, next_billing_date, gateway_id, amount)
            VALUES 
            (:frequency, :status, :start_date, :creation_date, :next_billing_date, :gateway_id, :amount)
        ");
        
        $stmt->execute([
            ':frequency' => $data['frequency'] ?? 'monthly',
            ':status' => $data['status'] ?? 'pending',
            ':start_date' => $data['start_date'] ?? date('Y-m-d H:i:s'),
            ':creation_date' => $data['creation_date'] ?? time(),
            ':next_billing_date' => $data['next_billing_date'] ?? null,
            ':gateway_id' => $data['gateway_id'] ?? null,
            ':amount' => $data['amount'] ?? 0.00
        ]);
        
        return (int) $this->db->lastInsertId();
    }

 
    public function update(int $id, array $data): bool {
        $updates = [];
        $params = [':id' => $id];
        
        $allowed = ['frequency', 'status', 'start_date', 'next_billing_date', 'gateway_id', 'amount'];
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql = "UPDATE subscriptions SET " . implode(', ', $updates) . " WHERE subscription_id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($params);
    }

    /**
     * Delete a subscription
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM subscriptions WHERE subscription_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Get subscriptions by status
     */
    public function getByStatus(string $status, int $limit = 20): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM subscriptions WHERE status = :status ORDER BY created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }

    /**
     * Search subscriptions
     */
    public function search(string $query, int $limit = 20): array {
        $query = "%$query%";
        $stmt = $this->db->prepare("
            SELECT DISTINCT s.* FROM subscriptions s
            LEFT JOIN subscription_entities se ON s.subscription_id = se.subscription_id
            LEFT JOIN subscription_attribute_values sav ON se.entity_id = sav.entity_id
            LEFT JOIN attribute_definitions ad ON sav.attribute_id = ad.attribute_id
            WHERE s.gateway_id LIKE :query
               OR s.status LIKE :query
               OR sav.value LIKE :query
            LIMIT :limit
        ");
        
        $stmt->bindValue(':query', $query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }

    /**
     * Get subscription statistics
     */
    public function getStatistics(): array {
        $stats = [];
        
        // Total subscriptions
        $stmt = $this->db->query("SELECT COUNT(*) FROM subscriptions");
        $stats['total'] = (int) $stmt->fetchColumn();
        
        // By status
        $stmt = $this->db->query("SELECT status, COUNT(*) as count FROM subscriptions GROUP BY status");
        $byStatus = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $byStatus[] = $row;
        }
        $stats['by_status'] = $byStatus;
        
        // Total amount
        $stmt = $this->db->query("SELECT SUM(amount) as total_amount FROM subscriptions");
        $stats['total_amount'] = (float) $stmt->fetchColumn();
        
        // By frequency
        $stmt = $this->db->query("SELECT frequency, COUNT(*) as count FROM subscriptions GROUP BY frequency");
        $byFreq = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $byFreq[] = $row;
        }
        $stats['by_frequency'] = $byFreq;
        
        return $stats;
    }
}
