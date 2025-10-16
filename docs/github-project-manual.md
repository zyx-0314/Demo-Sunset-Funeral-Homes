# 📄 GitHub Project Manual

This manual describes how we plan, track, and report work using GitHub Projects (Projects v2), aligned with our SOP, branching, and commit rules.

## Purpose
- Give the team a single place to view roadmap, priorities, status, and owners.
- Keep Issues and PRs organized with consistent fields and automation.
- Enable lightweight reporting for stand-ups and reviews.

---

## Project setup
- Project type: GitHub Projects (v2) at the org or repo level.
- Views to create:
  - Board — kanban view by Status (default).
  - Table — spreadsheet view for triage, sorting, and bulk edits.
  - Roadmap — timeline view grouped by Milestone or Target Release.
  - Charts (optional) — throughput/cycle time if enabled.

---

## Standard fields
Add these fields to the Project and make them visible in Board/Table/Roadmap views.
- Status (single-select): Todo, In Progress, Blocked, In Review, Done
- Priority (single-select): P1 (High), P2 (Medium), P3 (Low)
- Area (single-select): frontend, backend, databases, documents
- Size (single-select): S, M, L
- Target Release (text or iteration): e.g., v0.1.0, v0.2.0
- Owner (assignee): the person responsible
- Due date (date): optional; use for time-sensitive items

Label alignment (GitHub labels):
- category: frontend, backend, databases, documents
- type: feat, fix, docs
- status: blocked (only when Status=Blocked)

---

## Columns (Board view)
- Todo — committed work not yet started
- In Progress — actively being worked on
- Blocked — waiting on dependency or decision (must include comment)
- In Review — PR opened and awaiting review/CI
- Done — merged and validated (or accepted by QA/PO)

Notes:
- Only move to Done after merge and basic verification.
- Keep Blocked items few and add a next step/date in a comment.

---

## Issue workflow
1) Create an Issue (see SOP) with a clear title and description.
2) Set Area, Priority, Size; add it to the Project (auto-add preferred).
3) Assign Owner; set Status=Todo.
4) When starting work: set Status=In Progress and create a branch following `docs/branching.md`.
5) Open a Draft PR early; link it to the Issue (`Closes #<id>` in PR).
6) When ready for review: Status=In Review.
7) After merge: Status=Done; ensure docs/tests are updated.

---

## Branching, commits, and PRs
- Branch names must follow `<category>/<short-description>`; see `docs/branching.md`.
- Commit types allowed: feat, fix, docs; see `docs/commit-manual.md`.
- PR requirements: clear title, description (what/why), link Issue, tests/docs updated, CI green.

---

## Automation (recommended)
- Auto-add Issues/PRs from this repo to the Project.
- On Issue added: set Status=Todo by default.
- On PR opened that links an Issue: set Status=In Review.
- On PR merged/Issue closed: set Status=Done.
- If no activity for 7 days and Status=In Progress: add a comment prompting an update.

Implementation: configure these in the Project’s Workflows (Projects v2) and repository settings.

---

## Grooming and cadence
- Weekly triage (15–30 mins):
  - Review new Issues → set Area, Priority, Size; assign or park in Todo.
  - Reorder Todo by Priority.
- Stand-up (every 2 weeks; see SOP):
  - Review In Progress and Blocked; decide next steps.
  - Demo Done items; update docs and close out.

---

## Milestones & releases
- Optional Milestones: use when batching features for a release tag (e.g., v0.1.0).
- Target Release field should reflect the planned tag; tag `main` after notable merges.

---

## Views configuration
- Board: Group by Status; Sort by Priority; Filter `-label:stale` (if used).
- Table: Show all fields; quick-edit Priority/Area/Owner; save filters by Area.
- Roadmap: Group by Target Release or Milestone; show date ranges if Due date is set.

---

## Roles and permissions
- Maintainers: configure fields, automation, and default views; approve PRs; manage priorities.
- Contributors: update Status and fields on their Issues; keep PRs linked and descriptions clear.

---

## Best practices
- Keep items small and testable; avoid mega-Issues.
- Always link PRs to Issues; keep the Project as the single source of truth.
- Update Status promptly; avoid work-in-progress drifting without visibility.
- Prefer comments over silent status changes to capture decisions.

---

## References
- SOP: `docs/sop-manual.md`
- Branching: `docs/branching.md`
- Commits: `docs/commit-manual.md`
- Engineering principles: `docs/core-engineering-principles.md`

---

- Last update: 2025-10-17
- Who: Maintainers
- TL;DR: Use GitHub Projects as the source of truth—standard fields, clear Status flow, auto-add Issues/PRs, and link branches/PRs per our SOP and branching rules.