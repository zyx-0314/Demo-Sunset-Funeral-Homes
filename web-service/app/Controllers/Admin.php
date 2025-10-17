<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use CodeIgniter\HTTP\ResponseInterface;

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

    /**
     * Display Admin Accounts Management Page
     *
     * GET /admin/accounts
     * Shows the accounts management interface with user statistics and account listings.
     * Requires manager authentication.
     */
    public function showAccountsPage()
    {
        // Enforce manager-only access using role-based authorization
        $accessCheck = $this->checkManagerAccess();
        if ($accessCheck !== null) {
            return $accessCheck; // Return 403 error view if access denied
        }

        try {
            // Initialize UsersModel for database operations
            $userModel = new UsersModel();

            // Fetch all active accounts (status = 1) ordered by ID ascending for display
            $accounts = $userModel->where('account_status', 1)->orderBy('id', 'ASC')->findAll();

            // Count total active accounts for dashboard statistics
            $accountsCount = $userModel->where('account_status', 1)->countAllResults();

            // Count verified email accounts (email_activated = 1) among active users
            $verifiedEmailAccountsCount = $userModel->where('account_status', 1)->where('email_activated', 1)->countAllResults();

            // Calculate non-verified email accounts by subtracting verified from total
            $nonVerfiedEmailAccountsCount = $accountsCount - $verifiedEmailAccountsCount;
        } catch (\Exception $e) {
            // Handle database errors gracefully by setting error message
            $accounts = "Server Issue: " . $e;
        }

        // Render accounts management view with collected data and statistics
        return view('admin/accounts', [
            'accounts' => $accounts,
            'accountsCount' => $accountsCount ?? 0,
            'verifiedEmailAccountsCount' => $verifiedEmailAccountsCount ?? 0,
            'nonVerfiedEmailAccountsCount' => $nonVerfiedEmailAccountsCount ?? 0,
        ]);
    }

    /**
     * Create New User Account
     *
     * POST /admin/accounts
     * Creates a new user account with validation, duplicate checking, and optional profile image upload.
     * Supports both AJAX/JSON responses and traditional form submissions.
     * Requires manager authentication.
     */
    public function createAccounts()
    {
        // Get CodeIgniter services for request handling and session management
        $request = service('request');
        $session = session();

        // Initialize validation service and define comprehensive field rules
        $validation = \Config\Services::validation();
        $validation->setRule('first_name', 'First name', 'required|min_length[2]|max_length[100]');
        $validation->setRule('middle_name', 'Middle name', 'permit_empty|max_length[100]');
        $validation->setRule('last_name', 'Last name', 'required|min_length[2]|max_length[100]');
        $validation->setRule('email', 'Email', 'required|valid_email');
        $validation->setRule('password', 'Password', 'required|min_length[8]');
        $validation->setRule('password_confirm', 'Password Confirmation', 'required|matches[password]');

        // Extract POST data for processing
        $post = $request->getPost();

        // Run validation and handle failures with appropriate response format
        if (! $validation->run($post)) {
            $errors = $validation->getErrors();

            // Detect if request expects JSON response (AJAX or API call)
            $wantsJson = $request->isAJAX() || stripos((string)$request->getHeaderLine('Accept'), 'application/json') !== false;
            if ($wantsJson) {
                // Return structured JSON error response for AJAX requests
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors'  => $errors,
                        'old'     => $post,
                    ]);
            }

            // For form submissions, store errors in session and redirect back
            $session->setFlashdata('errors', array_values($errors));
            $session->setFlashdata('fieldErrors', $errors);
            $session->setFlashdata('old', $post);
            return redirect()->back()->withInput();
        }

        // Initialize UsersModel for database operations
        $userModel = new UsersModel();

        // Check for duplicate email addresses to prevent account conflicts
        if ($userModel->where('email', $post['email'])->first()) {
            $wantsJson = $request->isAJAX() || stripos((string)$request->getHeaderLine('Accept'), 'application/json') !== false;
            if ($wantsJson) {
                // Return conflict error for AJAX requests
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_CONFLICT)
                    ->setJSON(['success' => false, 'message' => 'Email already registered', 'errors' => ['email' => 'Email already registered']]);
            }

            // For form submissions, store error and redirect back
            $session->setFlashdata('errors', ['email' => 'Email already registered']);
            $session->setFlashdata('old', $post);
            return redirect()->back()->withInput();
        }

        // Handle optional profile image upload with validation and security checks
        $profileImagePath = null;
        try {
            $file = $request->getFile('profile_image');
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                // Validate file type against allowed image formats
                $allowed = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
                $mime = $file->getClientMimeType();
                if (! in_array($mime, $allowed)) {
                    return $this->response->setStatusCode(ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE)
                        ->setJSON(['success' => false, 'message' => 'Invalid image type']);
                }

                // Check file size limit (5MB)
                $maxBytes = 5 * 1024 * 1024;
                if ($file->getSize() > $maxBytes) {
                    return $this->response->setStatusCode(413)
                        ->setJSON(['success' => false, 'message' => 'Image too large']);
                }

                // Create organized directory structure by year/month
                $sub = date('Y') . DIRECTORY_SEPARATOR . date('m');
                $publicUploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'profiles' . DIRECTORY_SEPARATOR . $sub . DIRECTORY_SEPARATOR;
                if (! is_dir($publicUploadDir)) mkdir($publicUploadDir, 0755, true);

                // Generate secure random filename and move file
                $newName = $file->getRandomName();
                $moved = $file->move($publicUploadDir, $newName);
                if ($moved) {
                    $profileImagePath = 'uploads/profiles/' . str_replace(DIRECTORY_SEPARATOR, '/', $sub) . '/' . $newName;
                }
            }
        } catch (\Exception $e) {
            // Handle file upload errors gracefully
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON(['success' => false, 'message' => 'Failed to process profile image']);
        }

        try {
            // Prepare complete user data array with defaults and processed values
            $data = [
                'first_name' => $post['first_name'],
                'middle_name' => $post['middle_name'] ?? null,
                'last_name' => $post['last_name'],
                'email' => $post['email'],
                'password_hash' => password_hash($post['password'], PASSWORD_DEFAULT), // Secure password hashing
                'type' => $post['type'] ?? 'client', // Default to client type
                'account_status' => 1, // Active by default
                'email_activated' => 0, // Email verification required
                'newsletter' => isset($post['newsletter']) ? 1 : 0, // Newsletter subscription
                'gender' => $post['gender'] ?? null,
                'profile_image' => $profileImagePath, // Path to uploaded image or null
            ];

            // Insert new user record into database
            $inserted = $userModel->insert($data);

            if ($inserted === false) {
                // Handle database insertion failure
                return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                    ->setJSON(['success' => false, 'message' => 'Could not create account']);
            }

            // Return success response for successful account creation
            return $this->response->setStatusCode(ResponseInterface::HTTP_CREATED)
                ->setJSON(['success' => true, 'message' => 'Account created']);
        } catch (\Exception $e) {
            // Handle unexpected errors during account creation
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON(['success' => false, 'message' => 'Server error while creating account: ' . $e->getMessage()]);
        }
    }

    /**
     * Update User Account Type
     *
     * POST /admin/accounts/update
     * Updates an existing user account's type (manager, employee, client).
     * Requires manager authentication and valid account ID.
     * Used for role management and account type changes.
     */
    public function updateAccount()
    {
        // Get CodeIgniter services for request handling and session management
        $request = service('request');
        $session = session();

        // Initialize UsersModel for database operations
        $userModel = new UsersModel();

        // Set up validation rules for required update fields
        $validation = \Config\Services::validation();
        $validation->setRule('id', 'ID', 'required|min_length[1]');
        $validation->setRule('type', 'User Type', 'required|min_length[1]');

        // Extract POST data containing account ID and new type
        $post = $request->getPost();

        // Validate input data and handle validation failures
        if (! $validation->run($post)) {
            // Store validation errors in session for form display
            $session->setFlashdata('errors', $validation->getErrors());
            $session->setFlashdata('old', $post);
            return redirect()->back()->withInput();
        }

        try {
            // Verify account exists before attempting update
            $account = $userModel->where('id', $post['id'])->first();
            if (! $account) {
                // Return 404 error if account not found
                return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                    ->setJSON(['success' => false, 'message' => 'Account not found']);
            }

            // Prepare update payload with account ID and new type
            $payload = [
                'id' => $post['id'],
                'type' => $post['type'],
            ];

            // Execute update operation using model's save method
            $ok = $userModel->save($payload);
            if ($ok === false) {
                // Throw exception if model update fails
                throw new \Exception('Model update failed');
            }

            // Return success response with updated account ID
            return $this->response->setStatusCode(ResponseInterface::HTTP_OK)
                ->setJSON(['success' => true, 'message' => 'Account Type Updated', 'data' => ['id' => $post['id']]]);
        } catch (\Throwable $e) {
            // Handle any unexpected errors during update process
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON(['success' => false, 'message' => 'Server error while updating account: ' . $e->getMessage()]);
        }
    }
}
