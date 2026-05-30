<?php
require_once __DIR__ . "/../models/SubscriptionModel.php";
class SubscriptionController
{
    private $model;

    public function __construct()
    {
        $this->model = new SubscriptionModel();
    }

    public function index()
    {
        $subscriptions = $this->model->getAllSubscriptions();
        require __DIR__ . "/../views/subscription_list.php";
    }

    public function show($subscriptionID)
    {
        $subscriptionID = (int) $subscriptionID;
        $subscription = $this->model->getSubscriptionWithAttributes($subscriptionID);
        if(!$subscription)
        {
            header("HTTP/1.0 404 Not Found");
            echo "Subscription not found.";
            return;
        }
        require __DIR__ . "/../views/subscription_detail.php";
    }

    public function create()
    {
        $error = "";

        if($_SERVER["REQUEST_METHOD"] === "POST")
        {
            $userID = !empty($_POST["user_id"]) ? (int) $_POST["user_id"] : 0;
            $frequency = !empty($_POST["frequency"]) ? $_POST["frequency"] : "monthly";
            $status = !empty($_POST["status"]) ? $_POST["status"] : "pending";
            $amount = !empty($_POST["amount"]) ? (float) $_POST["amount"] : 0.00;
            $gatewayID = !empty($_POST["gateway_id"]) ? $_POST["gateway_id"] : null;
            $startDate = !empty($_POST["start_date"]) ? $_POST["start_date"] : null;

            $newID = $this->model->createSubscription($userID, $frequency, $status, $amount, $gatewayID, $startDate);
            if($newID)
            {
                $customFields = $this->extractCustomFieldsFromPost();
                if(!empty($customFields))
                {
                    $entityID = $this->model->createEntity($newID);
                    if($entityID)
                    {
                        foreach($customFields as $attrID => $value)
                        {
                            $this->model->saveAttributeValue($entityID, $attrID, $value);
                        }
                    }
                }

                header("Location: subscription.php?id=" . $newID);
                exit;
            }
            else
            {
                $error = "Failed to create subscription.";
            }
        }

        $isEdit = false;
        $subscription = null;
        require __DIR__ . "/../views/subscription_form.php";
    }

    public function edit($subscriptionID)
    {
        $subscriptionID = (int) $subscriptionID;
        $subscription = $this->model->getSubscriptionWithAttributes($subscriptionID);
        if(!$subscription)
        {
            header("HTTP/1.0 404 Not Found");
            echo "Subscription not found.";
            return;
        }

        $error = "";

        if($_SERVER["REQUEST_METHOD"] === "POST")
        {
            $status = !empty($_POST["status"]) ? $_POST["status"] : $subscription["status"];
            $amount = !empty($_POST["amount"]) ? (float) $_POST["amount"] : (float) $subscription["amount"];
            $frequency = !empty($_POST["frequency"]) ? $_POST["frequency"] : $subscription["frequency"];
            $gatewayID = !empty($_POST["gateway_id"]) ? $_POST["gateway_id"] : $subscription["gateway_id"];

            $this->model->updateSubscription($subscriptionID, $status, $amount, $frequency, $gatewayID);

            $customFields = $this->extractCustomFieldsFromPost();

            $entity = $this->model->getEntityBySubscriptionID($subscriptionID);
            $entityID = null;

            if($entity)
            {
                $entityID = (int) $entity["entity_id"];
                $this->model->clearAttributeValues($entityID);
            }
            elseif(!empty($customFields))
            {
                $entityID = $this->model->createEntity($subscriptionID);
            }

            if($entityID && !empty($customFields))
            {
                foreach($customFields as $attrID => $value)
                {
                    $this->model->saveAttributeValue($entityID, $attrID, $value);
                }
            }

            header("Location: subscription.php?id=" . $subscriptionID);
            exit;
        }

        $isEdit = true;
        require __DIR__ . "/../views/subscription_form.php";
    }

    public function destroy($subscriptionID)
    {
        $subscriptionID = (int) $subscriptionID;

        if($_SERVER["REQUEST_METHOD"] === "POST")
        {
            $this->model->deleteSubscription($subscriptionID);
            header("Location: subscriptions.php");
            exit;
        }

        $subscription = $this->model->getSubscriptionByID($subscriptionID);
        if(!$subscription)
        {
            header("HTTP/1.0 404 Not Found");
            echo "Subscription not found.";
            return;
        }

        require __DIR__ . "/../views/subscription_delete.php";
    }

    private function extractCustomFieldsFromPost()
    {
        $fields = [];
        if(empty($_POST["custom_field"]) || !is_array($_POST["custom_field"]))
        {
            return $fields;
        }

        foreach($_POST["custom_field"] as $key => $val)
        {
            if($key === "new_id" || $key === "new_value")
            {
                continue;
            }
            if($val !== "")
            {
                $fields[(int) $key] = $val;
            }
        }

        if(!empty($_POST["custom_field"]["new_id"]) && !empty($_POST["custom_field"]["new_value"]))
        {
            $fields[(int) $_POST["custom_field"]["new_id"]] = $_POST["custom_field"]["new_value"];
        }

        return $fields;
    }
}
?>