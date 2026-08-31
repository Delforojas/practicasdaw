# 🐳 Docker

El entorno local se ha trabajado mediante Docker Compose.

## Arquitectura

```text
┌─────────────────────┐
│      Angular        │
│      Frontend       │
└──────────┬──────────┘
           │
           │ HTTP / JSON
           ▼
┌─────────────────────┐
│      Symfony        │
│      REST API       │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│     PostgreSQL      │
│      Database       │
└─────────────────────┘
```

Servicios independientes para:

- frontend Angular;
- backend Symfony;
- PostgreSQL.

Esto permite trabajar con entornos reproducibles y aislar cada servicio.

---