# AGENTS.md

## Project Overview

This repository contains a Full Stack web application originally developed
during a DAW internship and later adapted as a portfolio project.

The current frontend uses an Avengers / S.H.I.E.L.D. theme.

The project demonstrates practical experience with:

- Angular
- TypeScript
- TailwindCSS
- Symfony
- PHP
- PostgreSQL
- Doctrine ORM
- REST APIs
- JWT authentication
- Docker

The application is primarily a portfolio and learning project.
Preserve existing working functionality unless a task explicitly requires
changing it.


## Repository Structure

practicasdaw/
├── angular-frontend/
│   ├── src/app/modules/
│   ├── src/app/shared/
│   ├── src/app/environments/
│   ├── public/
│   ├── angular.json
│   └── package.json
│
├── symfony-backend/
│   ├── src/Controller/
│   ├── src/Entity/
│   ├── src/Repository/
│   ├── src/EventListener/
│   ├── src/Command/
│   ├── config/
│   ├── migrations/
│   └── composer.json
│
└── docker-compose.yml


## Frontend

Location:

angular-frontend/

Main technologies:

- Angular 19
- TypeScript
- Standalone Components
- TailwindCSS
- Angular Router
- HttpClient
- RxJS

The frontend is organized mainly by feature under:

src/app/modules/

Shared functionality is located under:

src/app/shared/


## Frontend Conventions

When working on Angular:

- Prefer standalone components.
- Follow the existing project structure.
- Use TypeScript with explicit and meaningful types.
- Reuse existing services and components before creating new ones.
- Avoid unnecessary duplication.
- Keep business/API logic out of templates.
- Preserve existing routing unless the task explicitly requires changes.
- Do not modify backend code for frontend-only tasks.


## Styling

TailwindCSS is the primary styling system.

The current portfolio design uses an Avengers / S.H.I.E.L.D. visual theme.

Main colors currently used include:

- Dark background: #0B1524
- S.H.I.E.L.D. blue: #172A46
- Secondary blue: #243B6B
- Avengers red: #B51F2E
- Light gray: #E3E8ED
- Secondary text: #B8C4D6

When modifying UI:

- Reuse existing CSS classes when possible.
- Do not duplicate styles unnecessarily.
- Prefer reusable CSS classes for repeated button styles.
- Preserve responsive behavior.
- Check mobile, tablet and desktop layouts.
- Maintain sufficient contrast and readable text.
- Keep the Avengers/S.H.I.E.L.D. visual language consistent.
- Do not redesign unrelated components unless explicitly requested.


## Backend

Location:

symfony-backend/

Main technologies:

- PHP
- Symfony 7.2
- Doctrine ORM
- PostgreSQL
- LexikJWTAuthenticationBundle
- REST API

Backend requests generally follow:

Angular
→ HttpClient
→ Symfony API
→ Controller
→ Doctrine
→ PostgreSQL


## Backend Conventions

When modifying Symfony:

- Follow Symfony conventions.
- Keep controllers focused.
- Use Doctrine entities and repositories consistently.
- Validate external input.
- Preserve API contracts unless explicitly asked to change them.
- Do not change database schema without explaining why.
- Do not create or modify migrations unless required.
- Do not modify authentication or authorization as a side effect of another task.


## Authentication

The application currently uses JWT authentication.

The JWT is handled through an HttpOnly cookie.

Roles currently include:

- ROLE_USER
- ROLE_TEACHER
- ROLE_ADMIN

Authentication and authorization changes are considered sensitive.

Do not modify:

- JWT configuration
- password handling
- role authorization
- authentication cookies
- security.yaml

unless the task explicitly concerns authentication or security.


## Database

Database:

PostgreSQL

ORM:

Doctrine ORM

Before changing entities or relationships:

1. Inspect the existing entity.
2. Inspect related entities.
3. Inspect existing migrations.
4. Explain the proposed schema change.
5. Avoid destructive database operations unless explicitly requested.


## Docker

The repository contains a root Docker Compose configuration for the Full Stack
development environment.

The stack contains:

- Angular frontend
- Symfony backend
- PostgreSQL database

Do not make unrelated Docker changes during frontend or styling tasks.


## Working Rules

Before modifying code:

1. Read the relevant component/file.
2. Inspect related files when necessary.
3. Understand existing behavior.
4. Keep the requested scope narrow.

For significant changes, explain which files will be modified before editing.

When implementing a task:

- Make the smallest reasonable change.
- Preserve working functionality.
- Do not perform unrelated refactors.
- Do not remove code unless its removal is justified.
- Do not introduce new dependencies unless necessary.
- Do not modify unrelated files.
- Reuse existing project patterns.


## Git Safety

Do not automatically:

- commit
- push
- force push
- rewrite Git history
- delete branches
- create pull requests

unless explicitly requested.

Before large modifications, check:

git status

Never overwrite unrelated uncommitted work.


## Security

Never:

- add credentials to source code
- expose passwords
- commit private keys
- print secrets
- introduce secrets into configuration files intended for Git

Treat authentication and security-related changes as high-risk operations.


## Verification

After frontend changes, when appropriate:

```bash
cd angular-frontend
npm run build
After backend changes, when appropriate:

```bash

cd symfony-backend

php bin/console lint:container

```

For Full Stack changes, verify that the relevant Docker services start correctly.

Do not claim that a feature works unless it has been verified.

If verification was not possible, state that clearly.

## Task Scope

If a task is specifically about frontend styling:

- Only modify the frontend components and styles required for that task.

- Do not modify Symfony.

- Do not modify PostgreSQL.

- Do not modify Docker.

- Do not modify authentication.

- Do not modify API contracts.

If a task is specifically about backend functionality:

- Do not redesign frontend components unless explicitly requested.

If a task affects both frontend and backend:

- Inspect both sides of the API contract before making changes.

- Preserve compatibility unless the requested task requires otherwise.

## Current Project Goal

The current priority is to finish and polish the frontend portfolio experience.

Primary goals:

1. Complete the Avengers-themed frontend.

2. Maintain responsive behavior.

3. Improve visual consistency.

4. Reuse existing styles and components.

5. Preserve current Full Stack functionality.

6. Keep the project understandable and presentable as a DAW portfolio project.

Backend refactoring, security hardening, dependency upgrades and larger

architectural changes will be handled separately.

## Agent Behavior

When a request is ambiguous:

- Inspect the relevant existing code before proposing a solution.

- Do not assume that a large refactor is desired.

- Prefer the smallest change that solves the requested problem.

For visual changes:

- Inspect the existing component and surrounding design first.

- Preserve the Avengers / S.H.I.E.L.D. theme.

- Reuse existing classes such as shared button styles when available.

- Keep responsive behavior.

- Avoid introducing a new design system unless explicitly requested.

When the task is complete:

- Briefly explain what changed.

- List the files modified.

- Report the verification performed.

- Mention any remaining issue relevant to the requested task.