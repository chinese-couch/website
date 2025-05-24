<?php
// api-test.php - API endpoint with bearer token authentication
require __DIR__ . '/vendor/autoload.php';

// Looing for .env at the root directory
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Check if request method is GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Function to get valid tokens from environment
function getValidTokens() {
    $tokens = [];
    
    // Single token
    if (isset($_ENV['API_BEARER_TOKEN'])) {
        $tokens[] = $_ENV['API_BEARER_TOKEN'];
    }
    
    // Multiple tokens (comma-separated)
    if (isset($_ENV['API_BEARER_TOKENS'])) {
        $tokens = array_merge($tokens, array_map('trim', explode(',', $_ENV['API_BEARER_TOKENS'])));
    }
    
    // Using getenv() function (works in more environments)
    if (getenv('API_BEARER_TOKEN')) {
        $tokens[] = getenv('API_BEARER_TOKEN');
    }
    
    if (getenv('API_BEARER_TOKENS')) {
        $tokens = array_merge($tokens, array_map('trim', explode(',', getenv('API_BEARER_TOKENS'))));
    }
    
    // Remove duplicates and empty values
    $tokens = array_unique(array_filter($tokens));
    
    // Fallback for development
    if (empty($tokens) && getenv('APP_ENV') === 'development') {
        error_log('WARNING: No API tokens configured, using development defaults');
        $tokens = ['dev-token-only'];
    }
    
    return $tokens;
}

// Function to validate bearer token
function validateBearerToken($authHeader) {
    // Extract token from Authorization header
    if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        return false;
    }
    
    $token = $matches[1];
    $validTokens = getValidTokens();
    
    // Check if we have any valid tokens configured
    if (empty($validTokens)) {
        error_log('ERROR: No valid API tokens configured');
        return false;
    }
    
    // Use timing-safe comparison to prevent timing attacks
    foreach ($validTokens as $validToken) {
        if (hash_equals($validToken, $token)) {
            return true;
        }
    }
    
    return false;
}

// Get Authorization header
$authHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';

// Some servers use different header names
if (empty($authHeader)) {
    $authHeader = isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) ? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] : '';
}

// Validate bearer token
if (!validateBearerToken($authHeader)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Authentication successful
http_response_code(200);
echo json_encode(['message' => 'Authentication successful.']);
