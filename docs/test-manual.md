# 📄 Test Manual

This manual outlines key manual test flows to validate critical paths. Prefer adding PHPUnit tests where feasible; use this as a quick smoke checklist.

Base URL: http://localhost:8090

---

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

- Last update: 2025-10-17
- Who: Maintainers
- TL;DR: Use this as a quick smoke test list; convert stable flows into automated tests and keep credentials/URLs in sync with seeds and routes.
