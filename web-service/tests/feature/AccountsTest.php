<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class AccountsTest extends CIUnitTestCase
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

    public function testAdminAccountsPageLoads(): void
    {
        // Simulate logged-in manager user
        $response = $this->withSession([
            'user' => [
                'id' => 1,
                'email' => 'martin.manager@example.test',
                'first_name' => 'Martin',
                'last_name' => 'Manager',
                'type' => 'manager',
                'display_name' => 'M Manager',
            ]
        ])->get('/admin/accounts');

        $response->assertStatus(200);
        $response->assertSee('Accounts'); // Should contain accounts content
    }

    public function testAdminAccountsPageForbiddenForNonManager(): void
    {
        // Simulate logged-in client user
        $response = $this->withSession([
            'user' => [
                'id' => 2,
                'email' => 'alice@example.test',
                'first_name' => 'Alice',
                'last_name' => 'Client',
                'type' => 'client',
                'display_name' => 'A Client',
            ]
        ])->get('/admin/accounts');

        $response->assertStatus(403);
    }

    public function testAdminAccountsSortingByType(): void
    {
        // Simulate logged-in manager user
        $response = $this->withSession([
            'user' => [
                'id' => 1,
                'email' => 'martin.manager@example.test',
                'first_name' => 'Martin',
                'last_name' => 'Manager',
                'type' => 'manager',
                'display_name' => 'M Manager',
            ]
        ])->get('/admin/accounts?sort=type');

        $response->assertStatus(200);
        // Should return accounts sorted by type
    }

    public function testAdminAccountsSortingByName(): void
    {
        // Simulate logged-in manager user
        $response = $this->withSession([
            'user' => [
                'id' => 1,
                'email' => 'martin.manager@example.test',
                'first_name' => 'Martin',
                'last_name' => 'Manager',
                'type' => 'manager',
                'display_name' => 'M Manager',
            ]
        ])->get('/admin/accounts?sort=name');

        $response->assertStatus(200);
        // Should return accounts sorted by name
    }

    public function testAdminAccountsSortingByEmail(): void
    {
        // Simulate logged-in manager user
        $response = $this->withSession([
            'user' => [
                'id' => 1,
                'email' => 'martin.manager@example.test',
                'first_name' => 'Martin',
                'last_name' => 'Manager',
                'type' => 'manager',
                'display_name' => 'M Manager',
            ]
        ])->get('/admin/accounts?sort=email');

        $response->assertStatus(200);
        // Should return accounts sorted by email
    }

    public function testAdminAccountsSearchByName(): void
    {
        // Simulate logged-in manager user
        $response = $this->withSession([
            'user' => [
                'id' => 1,
                'email' => 'martin.manager@example.test',
                'first_name' => 'Martin',
                'last_name' => 'Manager',
                'type' => 'manager',
                'display_name' => 'M Manager',
            ]
        ])->get('/admin/accounts?search=Martin');

        $response->assertStatus(200);
        // Should return accounts matching the name search
    }

    public function testAdminAccountsSearchByEmail(): void
    {
        // Simulate logged-in manager user
        $response = $this->withSession([
            'user' => [
                'id' => 1,
                'email' => 'martin.manager@example.test',
                'first_name' => 'Martin',
                'last_name' => 'Manager',
                'type' => 'manager',
                'display_name' => 'M Manager',
            ]
        ])->get('/admin/accounts?search=manager@example.test');

        $response->assertStatus(200);
        // Should return accounts matching the email search
    }

    public function testClientSignupCreatesAccount(): void
    {
        $response = $this->post('/signup', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test.user@example.com',
            'password' => 'Password123!',
            'password_confirm' => 'Password123!',
        ]);

        $response->assertStatus(302); // Redirect after successful signup
        $response->assertRedirect('/');

        // Verify user was created in database
        $this->seeInDatabase('users', ['email' => 'test.user@example.com']);
    }

    public function testAdminCreateEmployeeAccount(): void
    {
        // Simulate logged-in manager user
        $response = $this->withSession([
            'user' => [
                'id' => 1,
                'email' => 'martin.manager@example.test',
                'first_name' => 'Martin',
                'last_name' => 'Manager',
                'type' => 'manager',
                'display_name' => 'M Manager',
            ]
        ])->post('/admin/accounts/create', [
            'first_name' => 'John',
            'last_name' => 'Employee',
            'email' => 'john.employee@example.com',
            'password' => 'Password123!',
            'password_confirm' => 'Password123!',
            'type' => 'employee',
        ]);

        $response->assertStatus(201); // Created status for successful account creation

        // Verify employee account was created
        $this->seeInDatabase('users', [
            'email' => 'john.employee@example.com',
            'type' => 'employee'
        ]);
    }

    public function testAdminCreateAccountValidationFailure(): void
    {
        // Simulate logged-in manager user
        $response = $this->withSession([
            'user' => [
                'id' => 1,
                'email' => 'martin.manager@example.test',
                'first_name' => 'Martin',
                'last_name' => 'Manager',
                'type' => 'manager',
                'display_name' => 'M Manager',
            ]
        ])->post('/admin/accounts/create', [
            'first_name' => '', // Invalid: too short
            'last_name' => 'Employee',
            'email' => 'invalid-email', // Invalid: not valid email
            'password' => '123', // Invalid: too short
            'password_confirm' => '456', // Invalid: doesn't match
            'type' => 'employee',
        ]);

        $response->assertStatus(302); // Form validation failure redirects back
    }

    public function testAdminCreateAccountDuplicateEmail(): void
    {
        // Simulate logged-in manager user
        $response = $this->withSession([
            'user' => [
                'id' => 1,
                'email' => 'martin.manager@example.test',
                'first_name' => 'Martin',
                'last_name' => 'Manager',
                'type' => 'manager',
                'display_name' => 'M Manager',
            ]
        ])->post('/admin/accounts/create', [
            'first_name' => 'Duplicate',
            'last_name' => 'User',
            'email' => 'alice@example.test', // This email already exists
            'password' => 'Password123!',
            'password_confirm' => 'Password123!',
            'type' => 'employee',
        ]);

        $response->assertStatus(302); // Duplicate email redirects back
    }

    public function testAdminUpdateAccountType(): void
    {
        // Simulate logged-in manager user
        $response = $this->withSession([
            'user' => [
                'id' => 1,
                'email' => 'martin.manager@example.test',
                'first_name' => 'Martin',
                'last_name' => 'Manager',
                'type' => 'manager',
                'display_name' => 'M Manager',
            ]
        ])->post('/admin/accounts/update', [
            'id' => 3, // Ethan Embalmer (employee)
            'type' => 'manager', // Change from employee to manager
        ]);

        $response->assertStatus(200);
        $response->assertJSONExact([
            'success' => true,
            'message' => 'Account Type Updated',
            'data' => ['id' => '3'] // ID comes back as string from POST data
        ]);

        // Verify the account type was actually updated in database
        $userModel = new \App\Models\UsersModel();
        $updatedAccount = $userModel->find(3);
        $this->assertEquals('manager', $updatedAccount->type);
    }
}
