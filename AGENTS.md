# AGENTS.md

## Project Context

- Angular frontend

- Symfony backend

- PostgreSQL

- Docker

- JWT authentication

## Development Rules

- Preserve existing architecture.

- Do not introduce breaking changes without justification.

- Validate backend changes with PHPUnit.

- Validate frontend changes with production build.

- Never commit secrets or local environment files.

- Follow existing naming and directory conventions.

## Security

- Never expose credentials.

- JWT private keys must not be tracked.

- Validate user input.

- Respect role-based authorization.

- Do not weaken CORS or cookie security.

## Git Workflow

- Work on focused changes.

- Review diff before commit.

- Avoid force push unless explicitly required.

- Use descriptive commit messages.

## Definition of Done

- Tests pass.

- Build succeeds.

- No secrets added.

- Git diff reviewed.