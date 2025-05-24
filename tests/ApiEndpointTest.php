<?php
use PHPUnit\Framework\TestCase;

class ApiEndpointTest extends TestCase
{
    private $baseUrl;
    private static $originalEnv = [];
    
    protected function setUp(): void
    {
        // Set your base URL for testing
        // Update this to match your actual development server
        $this->baseUrl = 'http://localhost:8000/api-test/';
        
        // Alternative URLs to try:
        // $this->baseUrl = 'http://localhost:8000/api-test';  // PHP built-in server
        // $this->baseUrl = 'http://localhost:80/api-test';   // Apache default
        // $this->baseUrl = 'http://127.0.0.1/api-test';      // Using IP instead
        
        // Store original environment values
        self::$originalEnv['API_BEARER_TOKEN'] = getenv('API_BEARER_TOKEN');
        self::$originalEnv['API_BEARER_TOKENS'] = getenv('API_BEARER_TOKENS');
        
        // Set test tokens
        putenv('API_BEARER_TOKEN=test-token-123');
        putenv('API_BEARER_TOKENS=valid-bearer-token,api-key-xyz789');
    }
    
    protected function tearDown(): void
    {
        // Restore original environment
        foreach (self::$originalEnv as $key => $value) {
            if ($value === false) {
                putenv($key);
            } else {
                putenv("$key=$value");
            }
        }
    }
    
    /**
     * Test successful authentication with valid bearer token
     */
    public function testSuccessfulAuthentication()
    {
        $ch = curl_init($this->baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer test-token-123'
        ]);
        
        // Add debugging options
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        // Debug output
        if ($httpCode === 0) {
            $this->fail("Could not connect to $this->baseUrl. Error: $error. Is your server running?");
        }
        
        $this->assertEquals(200, $httpCode);
        $this->assertJson($response);
        
        $data = json_decode($response, true);
        $this->assertEquals('Authentication successful.', $data['message']);
    }
    
    /**
     * Test authentication failure with invalid token
     */
    public function testAuthenticationFailureInvalidToken()
    {
        $ch = curl_init($this->baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer invalid-token'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $this->assertEquals(401, $httpCode);
        $this->assertJson($response);
        
        $data = json_decode($response, true);
        $this->assertEquals('Unauthorized', $data['error']);
    }
    
    /**
     * Test authentication failure without Authorization header
     */
    public function testAuthenticationFailureNoHeader()
    {
        $ch = curl_init($this->baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $this->assertEquals(401, $httpCode);
        $this->assertJson($response);
        
        $data = json_decode($response, true);
        $this->assertEquals('Unauthorized', $data['error']);
    }
    
    /**
     * Test that only GET method is allowed
     */
    public function testMethodNotAllowed()
    {
        $ch = curl_init($this->baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer test-token-123'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $this->assertEquals(405, $httpCode);
        $this->assertJson($response);
        
        $data = json_decode($response, true);
        $this->assertEquals('Method not allowed', $data['error']);
    }
}
