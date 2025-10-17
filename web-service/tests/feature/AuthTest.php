<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class AuthTest extends CIUnitTestCase
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

    public function testWrongEmailLogin(): void
    {
        $response = $this->post('/login', [
            'email' => 'wrong.martin.manager@example.test',
            'password' => 'Password123!',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testWrongPasswordLogin(): void
    {
        $response = $this->post('/login', [
            'email' => 'martin.manager@example.test',
            'password' => 'WrongPassword123!',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testInactiveAccountLogin(): void
    {
        $response = $this->post('/login', [
            'email' => 'bob@example.test',
            'password' => 'Password123!',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testManagerLoginSuccess(): void
    {
        $response = $this->post('/login', [
            'email' => 'martin.manager@example.test',
            'password' => 'Password123!',
        ]);
        $response->assertRedirect('/admin/dashboard');
    }

    public function testEmployeeLoginSuccess(): void
    {
        $response = $this->post('/login', [
            'email' => 'ethan.embalmer@example.test',
            'password' => 'Password123!',
        ]);
        $response->assertRedirect('/employee/dashboard');
    }

    public function testClientLoginSuccess(): void
    {
        $response = $this->post('/login', [
            'email' => 'alice@example.test',
            'password' => 'Password123!',
        ]);
        $response->assertRedirect('/');
        // Check logged-in state, e.g., session has user
        $this->assertNotEmpty(session('user'));
    }

    public function testClientSignUpValidationFailure(): void
    {
        $response = $this->post('/signup', [
            'first_name' => 'A', // too short
            'last_name' => 'B', // too short
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirm' => 'DifferentPassword!',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/signup');
    }

    public function testClientSignUpSuccess(): void
    {
        $response = $this->post('/signup', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'Password123!',
            'password_confirm' => 'Password123!',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/');
        // Check user created
        $this->seeInDatabase('users', ['email' => 'john.doe@example.com']);
    }
}
