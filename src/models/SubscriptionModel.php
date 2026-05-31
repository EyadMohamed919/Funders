<?php
require_once __DIR__ . "/../../config/db.php";

class SubscriptionModel
{
    private $subscriptionID;
    private $userID;
    private $frequency;
    private $status;
    private $startDate;
    private $creationDate;
    private $nextBillingDate;
    private $gatewayID;
    private $amount;
    private $createdAt;
    private $subscriptionsCol;
    private $conn;

    public function __construct()
    {
        $this->conn = getDatabaseConnection();
    }

    public function getSubscriptionID()
    {
        return $this->subscriptionID;
    }
    public function getUserID()
    {
        return $this->userID;
    }
    public function getFrequency()
    {
        return $this->frequency;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function getStartDate()
    {
        return $this->startDate;
    }
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    public function getNextBillingDate()
    {
        return $this->nextBillingDate;
    }
    public function getGatewayID()
    {
        return $this->gatewayID;
    }
    public function getAmount()
    {
        return $this->amount;
    }
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    public function getSubscriptionsCol()
    {
        return $this->subscriptionsCol;
    }

    public function getSubscriptionByID($subscriptionID)
    {
        $subscriptionID = (int) $subscriptionID;
        $sql = $this->conn->query("SELECT * FROM subscriptions WHERE subscription_id = $subscriptionID LIMIT 1");
        if ($sql && $sql->num_rows > 0) {
            $row = $sql->fetch_assoc();
            $this->subscriptionID = $row["subscription_id"];
            $this->userID = $row["user_id"];
            $this->frequency = $row["frequency"];
            $this->status = $row["status"];
            $this->startDate = $row["start_date"];
            $this->creationDate = $row["creation_date"];
            $this->nextBillingDate = $row["next_billing_date"];
            $this->gatewayID = $row["gateway_id"];
            $this->amount = $row["amount"];
            $this->createdAt = $row["created_at"];
            $this->subscriptionsCol = $row["subscriptionscol"];
            return $row;
        }
        return null;
    }

    public function getEntityBySubscriptionID($subscriptionID)
    {
        $subscriptionID = (int) $subscriptionID;
        $sql = $this->conn->query("SELECT entity_id FROM subscription_entities WHERE subscription_id = $subscriptionID LIMIT 1");
        if ($sql && $sql->num_rows > 0) {
            return $sql->fetch_assoc();
        }
        return null;
    }

    public function getAttributeValuesByEntityID($entityID)
    {
        $entityID = (int) $entityID;
        $attributes = [];
        $sql = $this->conn->query("SELECT attribute_id, value FROM subscription_attribute_values WHERE entity_id = $entityID");
        if ($sql) {
            while ($row = $sql->fetch_assoc()) {
                $attributes[] = $row;
            }
        }
        return $attributes;
    }

    public function getSubscriptionWithAttributes($subscriptionID)
    {
        $subscription = $this->getSubscriptionByID($subscriptionID);
        if (!$subscription) {
            return null;
        }

        $entity = $this->getEntityBySubscriptionID($subscriptionID);
        if ($entity) {
            $subscription["custom_attributes"] = $this->getAttributeValuesByEntityID($entity["entity_id"]);
        } else {
            $subscription["custom_attributes"] = [];
        }

        return $subscription;
    }

    // de function malhash lazma 
    public function getSubscriptionsByUserID($userID)
    {
        $userID = (int) $userID;
        $subscriptions = [];
        $sql = $this->conn->query("SELECT * FROM subscriptions WHERE user_id = $userID ORDER BY subscription_id DESC");
        if ($sql) {
            while ($row = $sql->fetch_assoc()) {
                $subscriptions[] = $row;
            }
        }
        return $subscriptions;
    }

    // hena function bet geeb kol el subscriptions w btrg3ha fe array
    public function getAllSubscriptions()
    {
        $subscriptions = [];
        $sql = $this->conn->query("SELECT * FROM subscriptions ORDER BY subscription_id DESC");
        if ($sql) {
            while ($row = $sql->fetch_assoc()) {
                $subscriptions[] = $row;
            }
        }
        return $subscriptions;
    }


    // hena bena5od userID w frequency w status w amount w gatewayID  
    // w startDate w b3deen ben7awel a7ot el values de fel database w el subscriptionID el gdida
    public function createSubscription($userID, $frequency, $status, $amount, $gatewayID = null, $startDate = null)
    {
        $userID = (int) $userID;
        $frequency = $this->conn->real_escape_string($frequency);
        $status = $this->conn->real_escape_string($status);
        $amount = (float) $amount;

        $gatewayClause = "";
        $gatewayValue = "";
        if ($gatewayID !== null) {
            $gatewayID = $this->conn->real_escape_string($gatewayID);
            $gatewayClause = ", gateway_id";
            $gatewayValue = ", '$gatewayID'";
        }

        $startClause = "";
        $startValue = "";
        if ($startDate !== null) {
            $startDate = $this->conn->real_escape_string($startDate);
            $startClause = ", start_date";
            $startValue = ", '$startDate'";
        }

        $sql = $this->conn->query("INSERT INTO subscriptions (user_id, frequency, status, amount $gatewayClause $startClause) VALUES ($userID, '$frequency', '$status', $amount $gatewayValue $startValue)");

        if ($sql && $this->conn->affected_rows > 0) {
            return $this->conn->insert_id;
        }
        return null;
    }

    public function createEntity($subscriptionID)
    {
        $subscriptionID = (int) $subscriptionID;
        $sql = $this->conn->query("INSERT INTO subscription_entities (subscription_id, created_at) VALUES ($subscriptionID, NOW())");
        if ($sql && $this->conn->affected_rows > 0) {
            return $this->conn->insert_id;
        }
        return null;
    }

    public function saveAttributeValue($entityID, $attributeID, $value)
    {
        $entityID = (int) $entityID;
        $attributeID = (int) $attributeID;
        $value = $this->conn->real_escape_string($value);

        $check = $this->conn->query("SELECT value_id FROM subscription_attribute_values WHERE entity_id = $entityID AND attribute_id = $attributeID LIMIT 1");
        if ($check && $check->num_rows > 0) {
            $sql = $this->conn->query("UPDATE subscription_attribute_values SET value = '$value' WHERE entity_id = $entityID AND attribute_id = $attributeID");
            return $sql ? true : false;
        }

        $sql = $this->conn->query("INSERT INTO subscription_attribute_values (entity_id, attribute_id, value, created_at) VALUES ($entityID, $attributeID, '$value', NOW())");
        return $sql ? true : false;
    }

    public function deleteAttributeValue($entityID, $attributeID)
    {
        $entityID = (int) $entityID;
        $attributeID = (int) $attributeID;
        $sql = $this->conn->query("DELETE FROM subscription_attribute_values WHERE entity_id = $entityID AND attribute_id = $attributeID");
        return $sql ? true : false;
    }

    public function clearAttributeValues($entityID)
    {
        $entityID = (int) $entityID;
        $sql = $this->conn->query("DELETE FROM subscription_attribute_values WHERE entity_id = $entityID");
        return $sql ? true : false;
    }

    public function updateSubscription($subscriptionID, $status, $amount, $frequency = null, $gatewayID = null)
    {
        $subscriptionID = (int) $subscriptionID;
        $status = $this->conn->real_escape_string($status);
        $amount = (float) $amount;

        $setParts = ["status = '$status'", "amount = $amount"];

        if ($frequency !== null) {
            $frequency = $this->conn->real_escape_string($frequency);
            $setParts[] = "frequency = '$frequency'";
        }

        if ($gatewayID !== null) {
            $gatewayID = $this->conn->real_escape_string($gatewayID);
            $setParts[] = "gateway_id = '$gatewayID'";
        }

        $setSQL = implode(", ", $setParts);
        $sql = $this->conn->query("UPDATE subscriptions SET $setSQL WHERE subscription_id = $subscriptionID");
        return $sql ? true : false;
    }

    public function deleteSubscription($subscriptionID)
    {
        $subscriptionID = (int) $subscriptionID;

        $entity = $this->getEntityBySubscriptionID($subscriptionID);
        if ($entity) {
            $entityID = (int) $entity["entity_id"];
            $this->conn->query("DELETE FROM subscription_attribute_values WHERE entity_id = $entityID");
            $this->conn->query("DELETE FROM subscription_entities WHERE entity_id = $entityID");
        }

        $sql = $this->conn->query("DELETE FROM subscriptions WHERE subscription_id = $subscriptionID");
        return $sql ? true : false;
    }
}
?>