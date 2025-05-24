<?php
// This file demonstrates different ways to manage tokens

class TokenConfig {
    /**
     * Get tokens based on environment
     */
    public static function getTokens() {
        // Option 1: Environment variables (recommended)
        if (getenv('API_BEARER_TOKENS')) {
            return explode(',', getenv('API_BEARER_TOKENS'));
        }
        
        // Option 2: Separate config file (not in Git)
        $configFile = __DIR__ . '/tokens.local.php';
        if (file_exists($configFile)) {
            return require $configFile;
        }
        
        // Option 3: Database storage (for dynamic tokens)
        if (getenv('USE_DATABASE_TOKENS') === 'true') {
            return self::getTokensFromDatabase();
        }
        
        throw new Exception('No token configuration found');
    }
    
    /**
     * Get tokens from database
     */
    private static function getTokensFromDatabase() {
        $pdo = new PDO(
            sprintf('mysql:host=%s;dbname=%s', getenv('DB_HOST'), getenv('DB_NAME')),
            getenv('DB_USER'),
            getenv('DB_PASS')
        );
        
        $stmt = $pdo->query('SELECT token FROM api_tokens WHERE active = 1');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
