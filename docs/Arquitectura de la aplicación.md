# 🌐 Arquitectura de la aplicación

```text
Browser
   │
   ▼
Angular SPA
   │
   │ HTTP / JSON
   ▼
Symfony REST API
   │
   ├── Authentication
   ├── Authorization
   ├── Business Logic
   └── Doctrine ORM
   │
   ▼
PostgreSQL
```

---