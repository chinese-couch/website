<?php
/**
 * Generate a secure random token
 */
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Generate tokens for different environments
echo "Generated API Tokens:\n";
echo "====================\n\n";

echo "Development Token:\n";
echo generateSecureToken(16) . "\n\n";

echo "Production Tokens:\n";
for ($i = 1; $i <= 3; $i++) {
    echo "Token $i: " . generateSecureToken(32) . "\n";
}

echo "\nAdd these to your .env file and keep them secure!\n";

