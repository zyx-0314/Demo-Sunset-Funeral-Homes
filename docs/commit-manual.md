# 📄 Commit Manual

This guide defines how to write consistent, meaningful commits aligned with our branching strategy.

## Commit Types (allowed)

* **feat** → Add a new feature or functionality.
* **fix** → Correct a bug, error, or unintended behavior.
* **docs** → Documentation updates (manuals, notes, diagrams, README, etc.).

---

## Branch Categories

Branches follow the pattern:

```
<category>/<short-description>
```

* **frontend/** → Tailwind/UI-related work (server-rendered views, JS snippets).

  * Example: `frontend/login-form-validation`
  * Example: `frontend/mobile-first-layout`

* **backend/** → CodeIgniter 4 backend code (controllers, services, repositories, API).

  * Example: `backend/user-auth-service`
  * Example: `backend/jwt-token-refresh`

* **databases/** → Migrations, seeds, schema updates, or DB-specific fixes.

  * Example: `databases/add-posts-table`
  * Example: `databases/pg-uuid-migration`

* **documents/** → Project manuals, SOPs, technical notes, instructional docs.

  * Example: `documents/update-dev-manual`
  * Example: `documents/add-copilot-instructions`

---

## Commit Examples

### **feat**

**Short only:**

```
feat(backend): implement user login with JWT
```

**With body:**

```
feat(frontend): add Zustand store for session handling

Introduced a Zustand store to manage user sessions and JWT tokens
on the client. This simplifies state management and removes the
need for prop drilling across components.
```

---

### **fix**

**Short only:**

```
fix(backend): correct validation error handling in UsersController
```

**With body:**

```
fix(frontend): resolve mobile navbar overlap on small screens

Adjusted Tailwind classes to fix z-index and flex layout issues.
The navbar now properly collapses into a hamburger menu and
no longer hides the main content on iPhone SE screens.
```

---

### **docs**

**Short only:**

```
docs(documents): update commit-manual.md with branching rules
```

**With body:**

```
docs(documents): add rollback steps to sop-manual.md

Expanded the SOP manual to include rollback instructions
after a failed migration. This helps ensure database
consistency during staging releases.
```

---

---

✅ **Rule of Thumb:**

* **Header** → concise summary (`<type>(<scope>): <short description>`).
* **Body** → *why + what changed* (wrap at \~72 chars per line).
* **Scope** → optional, but use (`frontend`, `backend`, `databases`, `documents`).

---

## Relationship with branches

- Branch names follow `<category>/<short-description>`; the commit scope should match the category (e.g., `feat(backend): ...`).
- See `docs/branching.md` for the end-to-end workflow, PR requirements, and examples.

---

- Last update: 2025-10-17
- Who: Maintainers
- TL;DR: Use feat/fix/docs only; keep messages small and scoped, and align the commit scope with your branch category.
