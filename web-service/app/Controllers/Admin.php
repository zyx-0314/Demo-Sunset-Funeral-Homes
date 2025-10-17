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
            // Use UsersModel to load active accounts
            $userModel = new UsersModel();

            // Query Builder: active users ordered by id asc
            $accounts = $userModel->where('account_status', 1)->orderBy('id', 'ASC')->findAll();

            // Number of all active accounts
            $accountsCount = $userModel->where('account_status', 1)->countAllResults();

            // Filter Number of active accounts
            $verifiedEmailAccountsCount = $userModel->where('account_status', 1)->where('email_activated', 1)->countAllResults();
            $nonVerfiedEmailAccountsCount = $accountsCount - $verifiedEmailAccountsCount;
        } catch (\Exception $e) {
            $accounts = "Server Issue: " . $e;
        }

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
        // Access service request
        $request = service('request');
        // Initialize Session
        $session = session();

        // Basic validation using CI's Validation service
        $validation = \Config\Services::validation();
        $validation->setRule('first_name', 'First name', 'required|min_length[2]|max_length[100]');
        $validation->setRule('middle_name', 'Middle name', 'permit_empty|max_length[100]');
        $validation->setRule('last_name', 'Last name', 'required|min_length[2]|max_length[100]');
        $validation->setRule('email', 'Email', 'required|valid_email');
        $validation->setRule('password', 'Password', 'required|min_length[8]');
        $validation->setRule('password_confirm', 'Password Confirmation', 'required|matches[password]');

        // Assign value from post to variable
        $post = $request->getPost();

        // If no value found from post, notify it is required
        if (! $validation->run($post)) {
            $errors = $validation->getErrors();

            // If AJAX/JSON request, keep JSON behavior
            $wantsJson = $request->isAJAX() || stripos((string)$request->getHeaderLine('Accept'), 'application/json') !== false;
            if ($wantsJson) {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors'  => $errors,
                        'old'     => $post,
                    ]);
            }

            $session->setFlashdata('errors', array_values($errors));
            $session->setFlashdata('fieldErrors', $errors);
            $session->setFlashdata('old', $post);

            return redirect()->back()->withInput();
        }

        // Persist user to database using UsersModel
        $userModel = new UsersModel();

        // Prevent duplicate emails
        if ($userModel->where('email', $post['email'])->first()) {
            $wantsJson = $request->isAJAX() || stripos((string)$request->getHeaderLine('Accept'), 'application/json') !== false;
            if ($wantsJson) {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_CONFLICT)
                    ->setJSON(['success' => false, 'message' => 'Email already registered', 'errors' => ['email' => 'Email already registered']]);
            }

            $session->setFlashdata('errors', ['email' => 'Email already registered']);
            $session->setFlashdata('old', $post);
            return redirect()->back()->withInput();
        }

        // Handle profile image upload
        $profileImagePath = null;
        try {
            $file = $request->getFile('profile_image');
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $allowed = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
                $mime = $file->getClientMimeType();
                if (! in_array($mime, $allowed)) {
                    return $this->response->setStatusCode(ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE)
                        ->setJSON(['success' => false, 'message' => 'Invalid image type']);
                }

                $maxBytes = 5 * 1024 * 1024;
                if ($file->getSize() > $maxBytes) {
                    return $this->response->setStatusCode(413)
                        ->setJSON(['success' => false, 'message' => 'Image too large']);
                }

                $sub = date('Y') . DIRECTORY_SEPARATOR . date('m');
                $publicUploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'profiles' . DIRECTORY_SEPARATOR . $sub . DIRECTORY_SEPARATOR;
                if (! is_dir($publicUploadDir)) mkdir($publicUploadDir, 0755, true);

                $newName = $file->getRandomName();
                $moved = $file->move($publicUploadDir, $newName);
                if ($moved) {
                    $profileImagePath = 'uploads/profiles/' . str_replace(DIRECTORY_SEPARATOR, '/', $sub) . '/' . $newName;
                }
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON(['success' => false, 'message' => 'Failed to process profile image']);
        }

        try {
            // Prepare data
            $data = [
                'first_name' => $post['first_name'],
                'middle_name' => $post['middle_name'] ?? null,
                'last_name' => $post['last_name'],
                'email' => $post['email'],
                'password_hash' => password_hash($post['password'], PASSWORD_DEFAULT),
                'type' => $post['type'] ?? 'client',
                'account_status' => 1,
                'email_activated' => 0,
                'newsletter' => isset($post['newsletter']) ? 1 : 0,
                'gender' => $post['gender'] ?? null,
                'profile_image' => $profileImagePath,
            ];

            $inserted = $userModel->insert($data);

            if ($inserted === false) {
                return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                    ->setJSON(['success' => false, 'message' => 'Could not create account']);
            }

            return $this->response->setStatusCode(ResponseInterface::HTTP_CREATED)
                ->setJSON(['success' => true, 'message' => 'Account created']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON(['success' => false, 'message' => 'Server error while creating account: ' . $e->getMessage()]);
        }
    }
}
