# 📄 Test Manual

This manual outlines key manual test flows to validate critical paths. Prefer adding PHPUnit tests where feasible; use this as a quick smoke checklist.

Base URL: http://localhost:8090

---

## Database

### Migration Tests

#### Users Table Migration

- **Check if users table exists**: Verify the `users` table was created successfully
- **Check users table structure**: Verify all required columns exist (id, first_name, middle_name, last_name, email, password_hash, type, account_status, email_activated, newsletter, created_at, updated_at)
- **Check users table constraints**: Verify primary key, unique email constraint, and proper data types

#### Services Table Migration

- **Check if services table exists**: Verify the `services` table was created successfully
- **Check services table structure**: Verify all required columns exist (id, title, description, cost, is_available, is_active, inclusions, banner_image, created_at, updated_at, deleted_at)
- **Check services table constraints**: Verify primary key, proper data types, and nullable fields

### Seeding Tests

#### Users Seeding

- **Check users seed data count**: Verify correct number of users were seeded (10 total: 3 clients, 1 embalmer, 1 driver, 3 staff, 1 florist, 1 manager)
- **Check client users**: Verify Alice Carson, Bob Dawson, Cara Evans exist with correct data
- **Check employee users**: Verify Ethan Miller (embalmer), Darren Rios (driver), Sofia Kent/Tina Ng/Marco Reed (staff), Flora Bloom (florist) exist
- **Check manager user**: Verify Martin Gale exists with manager type
- **Check user types distribution**: Verify correct count per user type (client: 3, embalmer: 1, driver: 1, staff: 3, florist: 1, manager: 1)
- **Check account statuses**: Verify active/inactive status distribution
- **Check password hashing**: Verify all passwords are properly hashed

#### Services Seeding

- **Check services seed data count**: Verify correct number of services were seeded (8 total)
- **Check available services**: Verify 6 services are marked as available (is_available = 1)
- **Check unavailable services**: Verify 2 services are marked as unavailable (is_available = 0)
- **Check active services**: Verify 7 services are active (is_active = 1)
- **Check inactive services**: Verify 1 service is inactive (is_active = 0)
- **Check service data integrity**: Verify all services have required fields (title, description, cost, inclusions)
- **Check cost ranges**: Verify services have appropriate cost values (from $5,000 to $60,000)

## Authentication

URL: http://localhost:8090/login

### Wrong Email

- Email: wrong.martin.manager@example.test
- Password: Password123!
- Expected: Show error indicator on Email input

### Wrong Password

- Email: martin.manager@example.test
- Password: WrongPassword123!
- Expected: Show error indicator on Password input

### Inactive/Deleted Account

- Email: bob@example.test
- Password: Password123!
- Expected: Show error indicator on Email input (or generic auth error)

### Correct Login

- Manager
  - Email: martin.manager@example.test
  - Password: Password123!
  - Expected: Redirect to Manager Dashboard → http://localhost:8090/admin/dashboard
- Employee
  - Email: ethan.embalmer@example.test
  - Password: Password123!
  - Expected: Redirect to Employee Dashboard → http://localhost:8090/employee/dashboard
- Client
  - Email: alice@example.test
  - Password: Password123!
  - Expected: Redirect to Landing Page with logged header → http://localhost:8090/

### Sign Up — Client

URL: http://localhost:8090/signup

- Required: First Name and Last Name must have at least 2 characters
- Required: Password and Confirmation Password match

---

## Error Handling

### 403 Forbidden

- As a non-admin user (e.g., client or employee), log in and try to access admin pages like http://localhost:8090/admin/dashboard or http://localhost:8090/admin/services
- Expected: 403 Forbidden error page

### 404 Not Found

- Access a non-existent URL, e.g., http://localhost:8090/nonexistent
- Expected: 404 Not Found error page

### 400 Bad Request

- Submit a form with invalid data, e.g., POST to /signup with empty required fields or malformed data
- Expected: 400 Bad Request error or validation errors displayed

---

## Services

### Read Services

- Admin (available and not): http://localhost:8090/admin/services
  - Sort: Cost and Name
  - Filter: Available
  - Search: Title
- Public (available only): http://localhost:8090/services
  - Sort: Cost and Name
  - Search: Title
- Specific service: http://localhost:8090/services/1

### Create Service

- Go to http://localhost:8090/admin/services → click “Create Service” button
- Expected: Modal/form opens; validation errors shown for missing/invalid fields; successful create shows in list

### Update Service

- Go to http://localhost:8090/admin/services → click Update icon button
- Expected: Edit modal/form opens; changes persist and reflect in list/detail

### Delete Service

- Go to http://localhost:8090/admin/services → click Delete icon button
- Expected: Confirmation shown; item removed from list on success

---

## Accounts

### Read Accounts

- Summarized table: http://localhost:8090/admin/accounts
  - Sort: Type, Name, Email
  - Search: Name and Email

#### Sorting Tests

- **Name Sorting (A-Z)**: Click "Name A → Z" → Verify accounts are sorted alphabetically by full name (first + middle initial + last)
- **Name Sorting (Z-A)**: Click "Name Z → A" → Verify accounts are sorted reverse alphabetically by full name
- **Email Sorting (A-Z)**: Click "Email A → Z" → Verify accounts are sorted alphabetically by email address
- **Email Sorting (Z-A)**: Click "Email Z → A" → Verify accounts are sorted reverse alphabetically by email address
- **Default Sorting**: Click "Sort — default" → Verify accounts are sorted by ID ascending
- **Sort Persistence**: Apply sort, change page, then return → Verify sort is maintained

#### Filtering Tests

- **Type Filter - All**: Select "Type — all" → Verify all account types are displayed
- **Type Filter - Client**: Select "Client" → Verify only client accounts are shown
- **Type Filter - Manager**: Select "Manager" → Verify only manager accounts are shown
- **Type Filter - Employee**: Select "Employee (non-client)" → Verify only non-client accounts (driver, embalmer, staff, florist) are shown
- **Filter Persistence**: Apply filter, change page, then return → Verify filter is maintained
- **Combined Filter + Sort**: Apply both filter and sort → Verify results are both filtered and sorted correctly

#### Search Tests

- **Search by Name**: Enter partial/full name (e.g., "John") → Verify only accounts with matching names are shown
- **Search by Email**: Enter partial/full email (e.g., "gmail") → Verify only accounts with matching emails are shown
- **Search Case Insensitive**: Enter mixed case search → Verify search works regardless of case
- **Search Multiple Matches**: Enter term that matches multiple accounts → Verify all matching accounts are shown
- **Search No Results**: Enter term with no matches → Verify "No accounts match your search" message appears
- **Search Persistence**: Apply search, change page, then return → Verify search is maintained
- **Combined Search + Filter + Sort**: Apply all three → Verify results are filtered, searched, and sorted correctly

#### Pagination Tests

- **Per Page Selection**: Change "Show X per page" → Verify page resets to 1 and shows correct number of items
- **Page Navigation**: Click page numbers → Verify correct page loads with maintained filters/search/sort
- **Previous/Next Links**: Click "Prev"/"Next" → Verify navigation works and disables appropriately at boundaries
- **Page Reset on Filter**: Apply new filter/search → Verify page resets to 1
- **Page Bounds**: Navigate beyond available pages → Verify stays within valid page range
- **Pagination Display**: With many results → Verify pagination shows current page highlighted and reasonable page range

#### Combined Feature Tests

- **Filter + Search + Sort + Pagination**: Apply all features simultaneously → Verify everything works together correctly
- **URL Parameter Handling**: Manually modify URL parameters (?search=term&sort=name_asc&type=client&page=2&per_page=10) → Verify page loads with correct state
- **Browser Back/Forward**: Use browser navigation → Verify state is maintained
- **Form Reset**: Click "Reset" button → Verify all filters/search/sort are cleared and page resets to 1

### Create Account

- Client: http://localhost:8090/signup
- Employee: http://localhost:8090/admin/accounts → “Create Account” button

### Update Account

- http://localhost:8090/admin/accounts → Update icon button

### Delete Account

- http://localhost:8090/admin/accounts → Delete icon button

---

## Inquiries

### Read Inquiries

- All inquiries: http://localhost:8090/admin/inquiries
  - Sort: Start Date and End Date
  - Filter: Status
  - Search: Name and Service

### Create Inquiries

- Format: http://localhost:8090/reservation/<id>
- Sample using 4: http://localhost:8090/reservation/4

### Update Inquiries

- http://localhost:8090/admin/inquiries → Update icon button

---

## Notes

- These scenarios assume seeded data with sample users and services.
- If IDs differ (e.g., specific service id), adjust URLs accordingly.
- Convert stable, repeatable flows to automated PHPUnit tests to reduce manual effort over time.

---

- Last update: 2025-10-18
- Who: AI Assistant
- TL;DR: Use this as a quick smoke test list including error handling (403, 404, 400); convert stable flows into automated PHPUnit tests and keep credentials/URLs in sync with seeds and routes.
