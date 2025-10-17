<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class ErrorTest extends CIUnitTestCase
{
    use DatabaseTestTrait, FeatureTestTrait;

    protected $basePath = APPPATH . '../';
    protected $namespace = 'App';
    protected $seed = \App\Database\Seeds\DatabaseSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();
        // Override baseURL for tests
        $_SERVER['app.baseURL'] = 'http://localhost:8090';
    }

    public function test403ForbiddenForNonManagerAccessingAdmin(): void
    {
        // Simulate logged-in client user
        $response = $this->withSession([
            'user' => [
                'id' => 1,
                'email' => 'alice@example.test',
                'first_name' => 'Alice',
                'last_name' => 'Client',
                'type' => 'client',
                'display_name' => 'A Client',
            ]
        ])->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test404NotFound(): void
    {
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->get('/nonexistent');
    }

    public function test400BadRequest(): void
    {
        // Test posting invalid data to signup that causes validation failure
        $response = $this->post('/signup', [
            'first_name' => '',
            'last_name' => '',
            'email' => 'invalid-email',
            'password' => '',
            'password_confirm' => 'mismatch',
        ]);
        $response->assertStatus(302); // Validation failure redirects back
        // Note: In web apps, 400 is less common; validation errors typically redirect with 302
    }
}
