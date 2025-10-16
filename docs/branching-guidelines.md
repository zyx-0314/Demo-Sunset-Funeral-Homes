# Branching Strategy

This document defines how we name, create, and merge branches so work stays small, reviewable, and aligned with our SOPs.

## Goals
- Keep main stable and deployable at all times.
- Make branches small, short-lived, and easy to review.
- Align branch names with our commit scopes and documentation.

## Default branches
- main — protected, production-ready. Only merge via Pull Request (PR).
- demo — integration/preview branch. Optional staging for demos; merges also via PR.

## Naming convention
Use: `<category>/<short-description>`
- Allowed categories: `frontend`, `backend`, `databases`, `documents`
- Example names:
  - `backend/add-user-service`
  - `frontend/update-service-cards`
  - `databases/create-obituaries-table`
  - `documents/branching-guidelines`

Guidelines:
- Use kebab-case for the short description.
- Keep it under ~4 words; reflect the primary scope of change.
- One branch = one coherent change; avoid mixing unrelated work.

## Workflow
1) Branch from main (or demo if expressly targeting demo-only changes).
2) Make small, incremental commits following our commit rules: types `feat`, `fix`, `docs`; scope should match the branch category.
3) Keep the branch rebased on main to minimize merge conflicts.
4) Open a Draft PR early for visibility; convert to Ready when tests/docs are done.
5) Merge via PR after review and CI passes; delete the branch after merge.

## Recommended commands (Windows CMD)
```cmd
:: Start a new branch from main
git fetch origin
git checkout main
git pull --ff-only origin main
git checkout -b backend/add-user-service

:: Sync your branch with main periodically (rebase preferred)
git fetch origin
git rebase origin/main

:: Push your branch and open a PR
git push -u origin backend/add-user-service
```

## PR requirements
- Title uses commit style: `feat(scope): short summary` (scope = category).
- Description includes: what changed, why, screenshots (if UI), and risks.
- Checkboxes: tests added/updated, docs updated, migration impact noted.
- CI must pass; at least one review approval required.

## Hotfixes
- For urgent production fixes, branch from `main` using the same naming rule (e.g., `backend/fix-auth-race`), open a PR to `main`, then cherry-pick or merge-forward to `demo` if needed.

## Releases and tags
- We practice trunk-based with lightweight tags. Tag main after notable merges, e.g., `v0.1.0`.
- If a hardening phase is required, temporarily use `release/x.y.z` from main and only accept fixes; fast-forward main on release.

## Do and Don’t
- Do keep branches short-lived (aim: ≤ 3 days of work, ≤ ~500 LOC diff).
- Do rebase your feature branch onto main; avoid merge commits inside feature branches.
- Don’t commit directly to main or demo.
- Don’t mix refactors and features in the same branch unless the refactor is strictly local and required for the feature.

## Examples
- Good: `databases/add-services-indexes` (adds DB indexes only)
- Good: `frontend/improve-obituary-card-a11y` (view-only change)
- Avoid: `backend/frontend/mixed-changes` (multiple categories in one branch)

## Relationship with commits
- Commit types allowed: `feat`, `fix`, `docs`.
- Commit scope should match the branch category: e.g., `feat(backend): add ServiceService`.
- Keep commits small; prefer many small commits over one giant commit.

---

- Last update: 2025-10-17
- Who: Maintainers
- TL;DR: Branch from main using `<category>/<short-description>`, keep it small, rebase often, open a PR with tests/docs, and merge only via reviewed PRs.