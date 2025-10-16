<a name="readme-top"></a>

<br/>
<br/>

<div align="center">
  <a href="https://github.com/zyx-0314/">
    <img src="./docs/nyebe_white.png" alt="Nyebe" width="130" height="100">
  </a>
<!-- * Title Section -->
  <h3 align="center">AD - CI4 Demo — Sunset Funeral Homes</h3>
</div>

<!-- * Description Section -->
<div align="center">
This repository contains the actively maintained teaching demo for "Sunset Funeral Homes" — a CodeIgniter 4-based web system built to demonstrate structured MVC architecture, CRUD operations, memorial page rendering, and admin dashboards.  
It serves as both a learning sample for students studying CI4 application design and a portfolio-ready reference for recruiters evaluating backend and DevOps skills.
</div>


<br/>

![](https://visit-counter.vercel.app/counter.png?page=zyx-0314/ci4-template)

<!-- ! Make sure it was similar to your github -->

---

<br/>
<br/>

<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#overview">Overview</a>
      <ol>
        <li>
          <a href="#key-components">Key Components</a>
        </li>
        <li>
          <a href="#technology">Technology</a>
        </li>
      </ol>
    </li>
    <li>
      <a href="#rules-practices-and-principles">Rules, Practices and Principles</a>
    </li>
    <li>
      <a href="#resources">Resources</a>
    </li>
  </ol>
</details>

---

## Overview

This repository is an actively maintained instructional and portfolio demo showing how to implement a small-scale funeral-home application using CodeIgniter 4.
It emphasizes clean architecture and process alignment, modeling a production-style workflow for learning and professional demonstration purposes.

Included examples and behavior:

- Service listing (public pages showing offered services)
- Service scheduling (request form, preferred date/time, admin assignment)
- ituary pages (two visual treatments)
- Admin dashboard (DB health, management UI)
- Employee assignment by scheduled service
- Simple calendar view for scheduled services
- CI/CD integration via Docker + GitHub Actions
- Testing environment using PHPUnit under Docker

---

- **Purpose**: teach and demonstrate practical CI4 architecture patterns (Controllers → Services → Models/Repositories) with maintainable conventions.
- **Audience**: recruiters reviewing CodeIgniter 4 project structure and students learning disciplined backend development.

---

### Key Components

These are the main features implemented or intended for the demo site:

| Component / Feature                     | Purpose / Notes |
|-----------------------------------------|-----------------|
| Service listing                         | Public listing of offered services and prices
| Service scheduling                      | Request form + scheduling flow with preferred date/time
| Obituary pages (2 designs)   | Two layout templates for memorial pages (simple / gallery)
| Admin dashboard & functionality         | Admin view with DB health card and basic management
| Employee assignment per schedule        | Assign staff to scheduled service requests (admin)
| Calendar system                         | Simple calendar view to visualize scheduled services

 <!-- ! Start simple. Use these modules as **learning samples**; extend or replace them based on your project’s needs. -->

### Technology

#### Language

![HTML](https://img.shields.io/badge/HTML-E34F26?style=for-the-badge\&logo=html5\&logoColor=white)
![CSS](https://img.shields.io/badge/CSS-1572B6?style=for-the-badge\&logo=css3\&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge\&logo=javascript\&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge\&logo=php\&logoColor=white)

#### Framework/Library

![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=for-the-badge\&logo=tailwindcss\&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-EF4223?style=for-the-badge\&logo=codeigniter\&logoColor=white)

#### Databases

![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge\&logo=mysql\&logoColor=white)

<!-- ! Keep only the used technology -->

---

## Quick Start (Docker)

Run the development stack and the app (rebuild if needed):

```cmd
docker compose up --watch
```
Can see the preview using the following link: [Sunset Funeral Homes](http://sunset-funeral-homes.localtest.me/)

Common utility commands (run inside the project root):

- Run migrations:
```cmd
docker compose exec php composer migrate
```
- Run seeders:
```cmd
docker compose exec php composer seed
```
- Run tests:
```cmd
docker compose exec php composer test
```

- Create a migration (using CodeIgniter's spark tool):
```cmd
docker compose exec php php spark make:migration CreateUsersTable
```

- Create a model (using CodeIgniter's spark tool):

```cmd
docker compose exec php php spark make:model UserModel
```

- Create an entity (value object for a single record) (using CodeIgniter's spark tool):
```cmd
docker compose exec php php spark make:entity User
```

- Create a controller (add --resource to scaffold resourceful methods if you like) (using CodeIgniter's spark tool):
```cmd
docker compose exec php php spark make:controller Users
```

- Create a seeder (for test/dev data) (using CodeIgniter's spark tool):
```cmd
docker compose exec php php spark make:seeder UsersSeeder
```

If you prefer, you can include `-f "compose.yaml"` explicitly; the shorter commands above work when running from the repo root.

## Ports & Database

Defaults used in this project (host mapping):

| Service     | Host port  |
|-------------|-----------:|
| nginx (app) | 80         |
| phpMyAdmin  | 8095       |
| MySQL       | 3395       |

Database credentials used in examples and CI:

- Host: localhost
- Port: 3390
- Database: app
- User: root
- Password: root

Be careful: seeding and truncating are destructive operations — run only on local/dev environments unless you know what you're doing.


## Rules, Practices and Principles

<!-- ! Dont Revise this -->

1. Always prefix project titles with `AD-`.
2. Place files in their **respective CI4 folders** (`Controllers/`, `Services/`, `Repositories/`, `Views/`).
3. Naming conventions:

   | Type             | Case        | Example                   |
   | ---------------- | ----------- | ------------------------- |
   | Classes          | PascalCase  | `UserService.php`         |
   | Interfaces       | PascalCase  | `UserRepositoryInterface` |
   | DB tables/fields | snake\_case | `users`, `created_at`     |
   | Docs             | kebab-case  | `dev-manual.md`           |

4. Git commits use: `feat`, `fix`, `docs`, `refactor`.
5. Use **Controller → Service → Repository** pattern.
6. Assets (CSS/JS/img) live under `public/`.
7. Docker configs are at the repo root (`docker-compose.yml`, `nginx.conf`).
8. Docs are maintained in `/docs` (dev, technical, sop, commit, principles, copilot).

Example structure:

```
AD-ProjectName/
├─ backend/ci4/
│  ├─ app/Controllers/
│  ├─ app/Services/
│  ├─ app/Repositories/
│  ├─ app/Views/
│  ├─ public/
│  ├─ writable/
│  ├─ .env
│  └─ composer.json
├─ docker/               # Docker configs at root
├─ docs/                 # Manuals and project docs
├─ .gitignore
└─ readme.md
```

<!-- ! Dont Revise this -->

---

## Resources

| Title                   | Purpose                                                       | Link                                                                                             |
| ----------------------- | ------------------------------------------------------------- | ------------------------------------------------------------------------------------------------ |
| ChatGPT                 | AI assistance for architecture planning and documentation.    | [https://chat.openai.com](https://chat.openai.com)                                               |
| GitHub Copilot          | In-IDE code suggestions and boilerplate generation.           | [https://github.com/features/copilot](https://github.com/features/copilot)                       |
| Docker Desktop          | Local container runtime for PHP, MySQL, and CI/CD testing.    | [https://www.docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop) |
| VS Code                 | Primary IDE for CI4 and Dockerized development workflow.      | [https://code.visualstudio.com](https://code.visualstudio.com)                                   |
| GitHub Actions Runner   | CI/CD automation runner for build and test pipelines.         | [https://docs.github.com/actions](https://docs.github.com/actions)                               |
| YouTube “UI/UX Design”  | Tutorials on modern web layouts and interaction patterns.     | [https://www.youtube.com](https://www.youtube.com)                                               |
| Pinterest Design Boards | Inspiration for color schemes and component layouts.          | [https://www.pinterest.com](https://www.pinterest.com)                                           |
| Google Photos (Assets)  | Stock imagery and visuals used for mockups and documentation. | [https://photos.google.com](https://photos.google.com)                                           |
| System Documentation    | Internal PHP and CI4 manuals located in the `/docs` folder.   | — (see `/docs` folder in repo)                                                                   |


<!-- ! Add what tools aided you -->
