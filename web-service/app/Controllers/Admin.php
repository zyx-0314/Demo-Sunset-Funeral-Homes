<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

/**
 * Admin Controller - Handles Administrative Dashboard and Management
 *
 * This controller manages admin-only functionality and enforces role-based access control.
 * Only authenticated users with 'manager' role can access admin pages.
 *
 * Key Concepts:
 * - Role-Based Access Control (RBAC): Restricts access based on user roles
 * - Session Authentication: Uses CodeIgniter sessions to verify user login state
 * - User Types: 'manager' (admin access), 'employee' (staff), 'client' (customers)
 * - HTTP 403 Forbidden: Shows error page instead of redirecting for unauthorized access
 */
class Admin extends BaseController
{
    /**
     * Check Manager Access - Role-Based Authorization
     *
     * Verifies that the current user is authenticated and has manager privileges.
     * This method implements the core security check for all admin functionality.
     *
     * Security Flow:
     * 1. Check if user session exists (authentication)
     * 2. Verify user has 'manager' type (authorization)
     * 3. Show 403 Forbidden error if access denied
     *
     * @return \CodeIgniter\HTTP\ResponseInterface|string|null Returns 403 error view if access denied, null if granted
     */
    private function checkManagerAccess()
    {
        // Get the session service - this manages user login state
        $session = session();

        // User not authenticated - show 403 Forbidden error
        if (!$session->has('user')) {
            return $this->show403Error('Authentication required. Please log in to access admin pages.');
        }

        $user = $session->get('user');

        // User authenticated but not a manager - show 403 Forbidden error
        if (!isset($user['type']) || $user['type'] !== 'manager') {
            return $this->show403Error('Access denied: Manager role required to access admin pages.');
        }

        // Access granted - return null to continue execution
        return null;
    }

    /**
     * Show 403 Forbidden Error Page
     *
     * Displays a custom 403 error page for unauthorized access attempts.
     * This provides better UX than redirecting to login for authenticated users.
     *
     * @param string $message The error message to display
     * @return string The rendered 403 error view
     */
    private function show403Error(string $message = 'Access Forbidden'): string
    {
        // Set HTTP status code to 403
        $this->response->setStatusCode(403);

        // Render the 403 error view with custom message
        return view('errors/html/error_403', ['message' => $message]);
    }

    /**
     * Display Admin Dashboard
     *
     * GET /admin/dashboard
     * Shows the administrative dashboard with system statistics and management tools.
     * Requires manager authentication.
     */
    public function showDashboardPage()
    {
        // Enforce manager-only access using role-based authorization
        $accessCheck = $this->checkManagerAccess();
        if ($accessCheck !== null) {
            return $accessCheck; // Return 403 error view if access denied
        }

        try {
            $usersModel = new UsersModel();

            // Count active client accounts (status = 1)
            $activeClientsCount = $usersModel->where('type', 'client')->where('account_status', 1)->countAllResults();
        } catch (\Exception $error) {
            // Handle database errors gracefully
            $activeClientsCount = "Server Issue: " . $error;
        }

        // Render admin dashboard with statistics
        return view('admin/dashboard', [
            'activeClientsCount' => $activeClientsCount,
            'requestsCount' => "Under construction", // TODO: fetch from inquiries model
            'servicesCount' => "Under construction"  // TODO: fetch from services model
        ]);
    }
}
