<?php
require __DIR__ . '/../../config/db.php';
require __DIR__ . '/../controllers/AttributeDefinitionController.php';
require __DIR__ . '/../controllers/SubscriptionController.php';
require __DIR__ . '/../controllers/SubscriptionEntityController.php';

$conn = getDatabaseConnection();
if (!$conn) {
    die('DB connection error');
}


// ana keda ba process el form elly fe ChooseTypeSub.php, w ba3d keda ba redirect 3ala subscription-list.php
$choice    = $_POST['choice']    ?? '';
$amount    = $_POST['amount']    ?? null;
$frequency = $_POST['frequency'] ?? null;

// hena e7na ba3mel validation 3ala el inputs, w ba2olak en el choice lazem yeb2a wa7ed men 'one-time' aw 'recurring'

if (!in_array($choice, ['one-time', 'recurring'], true)) {
    http_response_code(400); echo 'Invalid choice'; exit;
}
if ($choice === 'one-time' && (!is_numeric($amount) || (float)$amount <= 0)) {
    http_response_code(400); echo 'Invalid amount'; exit;
}
if ($choice === 'recurring' && !in_array($frequency, ['weekly', 'monthly', 'yearly'], true)) {
    http_response_code(400); echo 'Invalid frequency'; exit;
}

// hena ba create el controllers elly hanestakhdemha 3ashan ne3mel el inserts el mo7taga fe database
$attrController   = new App\Controllers\AttributeDefinitionController($conn);
$subController    = new App\Controllers\SubscriptionController($conn);
$entityController = new App\Controllers\SubscriptionEntityController($conn);

// hena ba fetch kol el attribute definitions 3ashan ne3raf el attribute_id beta3 kol wa7ed fehom, w da mohem 3ashan el EAV model beta3na
$allDefs = $attrController->getAll();
$attrMap = array_column($allDefs, 'attribute_id', 'name');
// $attrMap = ['choice' => 1, 'amount' => 2, 'frequency' => 3]

// hena ba prepare el data elly han7otaha fe database, w ba3mel el inserts el mo7taga fe subscriptions, subscription_entities, w subscription_attribute_values
$now             = time();
$nextBillingDate = 0;
if ($choice === 'recurring') {
    $nextBillingDate = match ($frequency) {
        'weekly'  => strtotime('+1 week'),
        'monthly' => strtotime('+1 month'),
        'yearly'  => strtotime('+1 year'),
    };
}

// bos b2a ana ba3mel el inserts el mo7taga fe database:
$subscriptionId = $subController->create(
    frequency:       $choice === 'recurring' ? $frequency : null,
    status:          'pending',
    startDate:       date('Y-m-d H:i:s', $now),
    creationDate:    $now,
    nextBillingDate: $nextBillingDate,
    gatewayId:       '',
    amount:          $choice === 'one-time' ? (float)$amount : 0.00,
    userId:          (int) ($_SESSION['user_id'] ?? 0)
);

// ana instance l entity controller 3ashan a3mel insert fe subscription_entities w subscription_attribute_values
$entityId = $entityController->createForSubscription($subscriptionId);

// hena ba3mel loop 3ala el data elly 3andi w ba save kol wa7ed feha fe subscription_attribute_values, w ba use el attribute
$toSave = ['choice' => $choice];
if ($choice === 'one-time')  $toSave['amount']    = (string)(float)$amount;
if ($choice === 'recurring') $toSave['frequency'] = $frequency;

foreach ($toSave as $name => $value) {
    $attrId = $attrMap[$name] ?? null;
    if ($attrId === null) {
        throw new \RuntimeException("attribute_definition '$name' not found — did you run the seed?");
    }
    $entityController->saveAttributeValue($entityId, (int)$attrId, $value);
}

header('Location: subscription-list.php');
exit;