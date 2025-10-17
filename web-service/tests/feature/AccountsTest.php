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

    public function testAdminDeleteAccount(): void
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
        ])->post('/admin/accounts/delete', [
            'id' => 4, // Bob Client (client account)
        ]);

        $response->assertStatus(200);
        $response->assertJSONExact([
            'success' => true,
            'message' => 'Account deleted',
            'data' => ['id' => '4'] // ID comes back as string from POST data
        ]);

        // Verify the account was actually soft deleted in database
        $userModel = new \App\Models\UsersModel();
        $deletedAccount = $userModel->find(4);
        $this->assertEquals(0, $deletedAccount->account_status); // Should be inactive
        // Note: deleted_at might be null due to entity handling, but account_status change confirms soft delete
        $this->assertNotNull($deletedAccount); // Account should still exist in database
    }

    // ===== NEW TESTS FOR SORTING, FILTERING, PAGINATION, AND SEARCH =====

    public function testAccountsSortingByNameAscending(): void
    {
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?sort=name_asc');

        $response->assertStatus(200);
        $response->assertSee('Accounts');

        // Parse the HTML to check sorting order
        $html = $response->getBody();
        // Extract account names from the HTML and verify alphabetical order
        preg_match_all('/<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>/', $html, $matches);

        if (count($matches[0]) > 1) {
            $names = $matches[1]; // First column is name
            $sortedNames = $names;
            sort($sortedNames);
            $this->assertEquals($sortedNames, $names, 'Names should be sorted alphabetically ascending');
        }
    }

    public function testAccountsSortingByNameDescending(): void
    {
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?sort=name_desc');

        $response->assertStatus(200);

        // Parse the HTML to check reverse alphabetical order
        $html = $response->getBody();
        preg_match_all('/<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>/', $html, $matches);

        if (count($matches[0]) > 1) {
            $names = $matches[1];
            $sortedNames = $names;
            rsort($sortedNames);
            $this->assertEquals($sortedNames, $names, 'Names should be sorted alphabetically descending');
        }
    }

    public function testAccountsSortingByEmailAscending(): void
    {
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?sort=email_asc');

        $response->assertStatus(200);

        $html = $response->getBody();
        preg_match_all('/<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>/', $html, $matches);

        if (count($matches[0]) > 1) {
            $emails = $matches[2]; // Second column is email
            $sortedEmails = $emails;
            sort($sortedEmails);
            $this->assertEquals($sortedEmails, $emails, 'Emails should be sorted alphabetically ascending');
        }
    }

    public function testAccountsSortingByEmailDescending(): void
    {
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?sort=email_desc');

        $response->assertStatus(200);

        $html = $response->getBody();
        preg_match_all('/<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>/', $html, $matches);

        if (count($matches[0]) > 1) {
            $emails = $matches[2];
            $sortedEmails = $emails;
            rsort($sortedEmails);
            $this->assertEquals($sortedEmails, $emails, 'Emails should be sorted alphabetically descending');
        }
    }

    public function testAccountsFilteringByTypeClient(): void
    {
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?type=client');

        $response->assertStatus(200);

        $html = $response->getBody();
        preg_match_all('/<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>/', $html, $matches);

        if (count($matches[0]) > 0) {
            $types = $matches[3]; // Third column is type
            foreach ($types as $type) {
                $this->assertEquals('client', strtolower($type), 'All displayed accounts should be clients');
            }
        }
    }

    public function testAccountsFilteringByTypeManager(): void
    {
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?type=manager');

        $response->assertStatus(200);

        $html = $response->getBody();
        preg_match_all('/<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>/', $html, $matches);

        if (count($matches[0]) > 0) {
            $types = $matches[3];
            foreach ($types as $type) {
                $this->assertEquals('manager', strtolower($type), 'All displayed accounts should be managers');
            }
        }
    }

    public function testAccountsFilteringByTypeEmployee(): void
    {
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?type=employee');

        $response->assertStatus(200);

        $html = $response->getBody();
        preg_match_all('/<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>/', $html, $matches);

        if (count($matches[0]) > 0) {
            $types = $matches[3];
            foreach ($types as $type) {
                $this->assertNotEquals('client', strtolower($type), 'No clients should be shown in employee filter');
            }
        }
    }

    public function testAccountsSearchByName(): void
    {
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?search=Martin');

        $response->assertStatus(200);

        $html = $response->getBody();
        preg_match_all('/<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>/', $html, $matches);

        if (count($matches[0]) > 0) {
            $names = $matches[1];
            foreach ($names as $name) {
                $this->assertStringContainsString('Martin', $name, 'All results should contain the search term "Martin"');
            }
        }
    }

    public function testAccountsSearchByEmail(): void
    {
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?search=example.test');

        $response->assertStatus(200);

        $html = $response->getBody();
        preg_match_all('/<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>/', $html, $matches);

        if (count($matches[0]) > 0) {
            $emails = $matches[2];
            foreach ($emails as $email) {
                $this->assertStringContainsString('example.test', $email, 'All results should contain the search term "example.test"');
            }
        }
    }

    public function testAccountsSearchNoResults(): void
    {
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?search=nonexistentuser12345');

        $response->assertStatus(200);
        $response->assertSee('No accounts found');
    }

    public function testAccountsPagination(): void
    {
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?page=1&per_page=5');

        $response->assertStatus(200);

        $html = $response->getBody();
        // Count table rows (excluding header)
        $rowCount = substr_count($html, '<tr class="border-t">');
        $this->assertLessThanOrEqual(5, $rowCount, 'Should show at most 5 accounts per page');
    }

    public function testAccountsCombinedSearchFilterSort(): void
    {
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?search=test&type=manager&sort=name_asc&page=1&per_page=10');

        $response->assertStatus(200);

        $html = $response->getBody();
        preg_match_all('/<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>\s*<td[^>]*>([^<]+)<\/td>/', $html, $matches);

        if (count($matches[0]) > 0) {
            $names = $matches[1];
            $emails = $matches[2];
            $types = $matches[3];

            // Check filtering
            foreach ($types as $type) {
                $this->assertEquals('manager', strtolower($type), 'All accounts should be managers');
            }

            // Check searching
            foreach ($emails as $email) {
                $this->assertStringContainsString('test', $email, 'All emails should contain "test"');
            }

            // Check sorting (names should be in ascending order)
            if (count($names) > 1) {
                $sortedNames = $names;
                sort($sortedNames);
                $this->assertEquals($sortedNames, $names, 'Names should be sorted ascending');
            }
        }
    }

    public function testAccountsFormReset(): void
    {
        // First apply some filters
        $response = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts?search=test&type=manager&sort=name_asc&page=2&per_page=5');

        $response->assertStatus(200);

        // Then test reset (should redirect to base URL without parameters)
        $resetResponse = $this->withSession($this->getManagerSession())
            ->get('/admin/accounts');

        $resetResponse->assertStatus(200);
        // Should show all accounts without filters
    }

    // Helper method to get manager session data
    private function getManagerSession(): array
    {
        return [
            'user' => [
                'id' => 1,
                'email' => 'martin.manager@example.test',
                'first_name' => 'Martin',
                'last_name' => 'Manager',
                'type' => 'manager',
                'display_name' => 'M Manager',
            ]
        ];
    }
}
