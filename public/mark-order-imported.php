<?php

// Autoloader einbinden
require_once __DIR__ . '/../vendor/autoload.php';

// .env.local
function loadEnvFile($file) {
    if (!file_exists($file)) {
        die('.env.local file not found');
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Kommentare ignorieren
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // KEY=VALUE parsen
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Anführungszeichen entfernen
            $value = trim($value, '"\'');

            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Load ENV-file data
loadEnvFile(__DIR__ . '/../.env.local');

// Get Token
$webhookToken = $_ENV['WEBHOOK_TOKEN'] ?? '';

if (empty($webhookToken)) {
    die('WEBHOOK_TOKEN not configured in .env.local');
}

function logMessage($message) {
    $logFile = __DIR__ . '/../var/log/webhook.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

try {
    // Check Request-Methode
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        die('Method not allowed');
    }

    // Validate Token
    $headers = getallheaders();
    $authToken = $headers['X-Webhook-Token'] ?? $_GET['token'] ?? '';

    if ($authToken !== $webhookToken) {
        logMessage('Unauthorized access attempt');
        http_response_code(401);
        die('Unauthorized');
    }

    // Get order id
    $orderId = $_GET['auftragsNr'] ?? null;
    $reset = isset($_GET['reset']) && $_GET['reset'];

    if (!$orderId) {
        http_response_code(400);
        die('Missing "auftragsNr"');
    }

    if ($reset) {
        logMessage("Resetting order: auftragsNr=$orderId");
    } else {
        logMessage("Processing order: auftragsNr=$orderId");
    }

    // Call ESB API
    $apiResponse = markOrderAsImported($orderId, $reset);

    // Optional: Order in processed_orders.json speichern
    saveProcessedOrder($orderId);

    // Response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Order marked as imported',
        'apiResponse' => $apiResponse,
        'auftragsNr' => $orderId
    ]);

} catch (Exception $e) {
    logMessage('Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

function markOrderAsImported($orderId, bool $reset = false) {
    // Get API URL and Token from environment variables
    $apiBaseUrl = $_ENV['API_URL'] ?? 'https://test-shop-service.mza-vertrieb.de/api';
    $apiKey = $_ENV['API_KEY'] ?? '';

    // URL: Replace orderId
    if ($reset) {
        $apiUrl = $apiBaseUrl . '/transferauftraege/auftragsNr/' . urlencode($orderId) . '/reset';
        logMessage("Calling API: PUT $apiUrl");
    } else {
        $apiUrl = $apiBaseUrl . '/transferauftraege/auftragsNr/' . urlencode($orderId) . '/transferred';
        logMessage("Calling API: PUT $apiUrl");
    }

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-Api-Key: ' . $apiKey,
        'Accept: application/json',
        'Content-Length: 0'
    ]);

    // Deactivate SSL verification in debug mode
    if (($_ENV['DEBUG_MODE'] ?? 'false') === 'true') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        throw new Exception("cURL Error: $error");
    }

    if ($httpCode !== 200 && $httpCode !== 204) { // 204 = No Content
        logMessage("API Error: HTTP $httpCode, Response: $response");
        throw new Exception("API returned status code: $httpCode");
    }

    // Bei 204 No Content ist response leer
    if ($httpCode === 204) {
        return ['success' => true, 'http_code' => 204];
    }

    logMessage("API response (JSON): $response");

    return json_decode($response, true);
}

function saveProcessedOrder($orderId, bool $reset = false): void
{
    if ($reset) {
        $file = __DIR__ . '/../var/log/reset_orders.json';
    } else {
        $file = __DIR__ . '/../var/log/processed_orders.json';
    }

    $processedOrders = [];
    if (file_exists($file)) {
        $processedOrders = json_decode(file_get_contents($file), true) ?: [];
    }

    if (!in_array($orderId, $processedOrders)) {
        $processedOrders[] = $orderId;
        file_put_contents($file, json_encode($processedOrders));
    }
}