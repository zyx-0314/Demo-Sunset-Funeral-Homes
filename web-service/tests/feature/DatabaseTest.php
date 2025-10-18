<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class DatabaseTest extends CIUnitTestCase
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

    // Migration Tests

    public function testUsersTableExists(): void
    {
        $db = \Config\Database::connect();
        $this->assertTrue($db->tableExists('users'), 'Users table should exist after migration');
    }

    public function testUsersTableStructure(): void
    {
        $db = \Config\Database::connect();

        // Check if all required columns exist
        $columns = $db->getFieldNames('users');
        $requiredColumns = [
            'id',
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'password_hash',
            'type',
            'account_status',
            'email_activated',
            'newsletter',
            'created_at',
            'updated_at'
        ];

        foreach ($requiredColumns as $column) {
            $this->assertContains($column, $columns, "Column '{$column}' should exist in users table");
        }
    }

    public function testServicesTableExists(): void
    {
        $db = \Config\Database::connect();
        $this->assertTrue($db->tableExists('services'), 'Services table should exist after migration');
    }

    public function testServicesTableStructure(): void
    {
        $db = \Config\Database::connect();

        // Check if all required columns exist
        $columns = $db->getFieldNames('services');
        $requiredColumns = [
            'id',
            'title',
            'description',
            'cost',
            'is_available',
            'is_active',
            'inclusions',
            'banner_image',
            'created_at',
            'updated_at',
            'deleted_at'
        ];

        foreach ($requiredColumns as $column) {
            $this->assertContains($column, $columns, "Column '{$column}' should exist in services table");
        }
    }

    // Seeding Tests

    public function testUsersSeedingCount(): void
    {
        $db = \Config\Database::connect();
        $count = $db->table('users')->countAllResults();
        $this->assertEquals(10, $count, 'Should have 10 users after seeding');
    }

    public function testUsersSeedingClientData(): void
    {
        $db = \Config\Database::connect();

        // Check Alice Carson (active client)
        $alice = $db->table('users')
            ->where('email', 'alice@example.test')
            ->get()
            ->getRow();

        $this->assertNotNull($alice, 'Alice Carson should exist');
        $this->assertEquals('Alice', $alice->first_name);
        $this->assertEquals('M', $alice->middle_name);
        $this->assertEquals('Carson', $alice->last_name);
        $this->assertEquals('client', $alice->type);
        $this->assertEquals(1, $alice->account_status);
        $this->assertEquals(1, $alice->email_activated);

        // Check Bob Dawson (inactive client)
        $bob = $db->table('users')
            ->where('email', 'bob@example.test')
            ->get()
            ->getRow();

        $this->assertNotNull($bob, 'Bob Dawson should exist');
        $this->assertEquals(0, $bob->account_status, 'Bob should be inactive');

        // Check Cara Evans (active client)
        $cara = $db->table('users')
            ->where('email', 'cara@example.test')
            ->get()
            ->getRow();

        $this->assertNotNull($cara, 'Cara Evans should exist');
        $this->assertEquals('client', $cara->type);
    }

    public function testUsersSeedingEmployeeData(): void
    {
        $db = \Config\Database::connect();

        // Check Ethan Miller (embalmer)
        $ethan = $db->table('users')
            ->where('email', 'ethan.embalmer@example.test')
            ->get()
            ->getRow();

        $this->assertNotNull($ethan, 'Ethan Miller should exist');
        $this->assertEquals('embalmer', $ethan->type);

        // Check Darren Rios (driver)
        $darren = $db->table('users')
            ->where('email', 'darren.driver@example.test')
            ->get()
            ->getRow();

        $this->assertNotNull($darren, 'Darren Rios should exist');
        $this->assertEquals('driver', $darren->type);

        // Check staff members
        $staffCount = $db->table('users')
            ->where('type', 'staff')
            ->countAllResults();

        $this->assertEquals(3, $staffCount, 'Should have 3 staff members');

        // Check Flora Bloom (florist)
        $flora = $db->table('users')
            ->where('email', 'flora.florist@example.test')
            ->get()
            ->getRow();

        $this->assertNotNull($flora, 'Flora Bloom should exist');
        $this->assertEquals('florist', $flora->type);
    }

    public function testUsersSeedingManagerData(): void
    {
        $db = \Config\Database::connect();

        // Check Martin Gale (manager)
        $martin = $db->table('users')
            ->where('email', 'martin.manager@example.test')
            ->get()
            ->getRow();

        $this->assertNotNull($martin, 'Martin Gale should exist');
        $this->assertEquals('manager', $martin->type);
        $this->assertEquals(1, $martin->account_status);
    }

    public function testUsersSeedingTypeDistribution(): void
    {
        $db = \Config\Database::connect();

        $typeCounts = $db->table('users')
            ->select('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->getResultArray();

        $expectedCounts = [
            'client' => 3,
            'embalmer' => 1,
            'driver' => 1,
            'staff' => 3,
            'florist' => 1,
            'manager' => 1,
        ];

        foreach ($typeCounts as $row) {
            $this->assertEquals(
                $expectedCounts[$row['type']],
                $row['count'],
                "Should have {$expectedCounts[$row['type']]} users of type {$row['type']}"
            );
        }
    }

    public function testUsersSeedingPasswordHashing(): void
    {
        $db = \Config\Database::connect();

        $users = $db->table('users')->get()->getResult();

        foreach ($users as $user) {
            $this->assertNotEmpty($user->password_hash, 'Password hash should not be empty');
            $this->assertNotEquals('Password123!', $user->password_hash, 'Password should be hashed, not plain text');
            $this->assertTrue(password_verify('Password123!', $user->password_hash), 'Password should verify correctly');
        }
    }

    public function testServicesSeedingCount(): void
    {
        $db = \Config\Database::connect();
        $count = $db->table('services')->countAllResults();
        $this->assertEquals(8, $count, 'Should have 8 services after seeding');
    }

    public function testServicesSeedingAvailability(): void
    {
        $db = \Config\Database::connect();

        $availableCount = $db->table('services')
            ->where('is_available', 1)
            ->countAllResults();

        $unavailableCount = $db->table('services')
            ->where('is_available', 0)
            ->countAllResults();

        $this->assertEquals(6, $availableCount, 'Should have 6 available services');
        $this->assertEquals(2, $unavailableCount, 'Should have 2 unavailable services');
    }

    public function testServicesSeedingActiveStatus(): void
    {
        $db = \Config\Database::connect();

        $activeCount = $db->table('services')
            ->where('is_active', 1)
            ->countAllResults();

        $inactiveCount = $db->table('services')
            ->where('is_active', 0)
            ->countAllResults();

        $this->assertEquals(7, $activeCount, 'Should have 7 active services');
        $this->assertEquals(1, $inactiveCount, 'Should have 1 inactive service');
    }

    public function testServicesSeedingDataIntegrity(): void
    {
        $db = \Config\Database::connect();

        $services = $db->table('services')->get()->getResult();

        foreach ($services as $service) {
            $this->assertNotEmpty($service->title, 'Service title should not be empty');
            $this->assertNotEmpty($service->description, 'Service description should not be empty');
            $this->assertIsNumeric($service->cost, 'Service cost should be numeric');
            $this->assertGreaterThan(0, $service->cost, 'Service cost should be greater than 0');
        }
    }

    public function testServicesSeedingSpecificData(): void
    {
        $db = \Config\Database::connect();

        // Check Basic Funeral Package
        $basic = $db->table('services')
            ->where('id', 1)
            ->get()
            ->getRow();

        $this->assertNotNull($basic, 'Basic Funeral Package should exist');
        $this->assertEquals('Basic Funeral Package', $basic->title);
        $this->assertEquals(15000, $basic->cost);
        $this->assertEquals(1, $basic->is_available);
        $this->assertEquals('Chapel,Hearse,Flowers', $basic->inclusions);

        // Check Premium Funeral Package (unavailable)
        $premium = $db->table('services')
            ->where('id', 3)
            ->get()
            ->getRow();

        $this->assertNotNull($premium, 'Premium Funeral Package should exist');
        $this->assertEquals(0, $premium->is_available, 'Premium package should be unavailable');

        // Check Archived Package (inactive)
        $archived = $db->table('services')
            ->where('id', 6)
            ->get()
            ->getRow();

        $this->assertNotNull($archived, 'Archived Package should exist');
        $this->assertEquals(0, $archived->is_active, 'Archived package should be inactive');
    }

    public function testServicesSeedingCostRanges(): void
    {
        $db = \Config\Database::connect();

        $costs = $db->table('services')
            ->select('cost')
            ->get()
            ->getResultArray();

        $costValues = array_column($costs, 'cost');

        $this->assertContains(5000, $costValues, 'Should have service costing $5,000');
        $this->assertContains(60000, $costValues, 'Should have service costing $60,000');

        // Verify all costs are within reasonable range
        foreach ($costValues as $cost) {
            $this->assertGreaterThanOrEqual(5000, $cost, 'Minimum cost should be $5,000');
            $this->assertLessThanOrEqual(60000, $cost, 'Maximum cost should be $60,000');
        }
    }
}
