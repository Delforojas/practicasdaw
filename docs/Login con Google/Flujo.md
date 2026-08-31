## 🔄 Flujo de autenticación

```text
Usuario
   │
   ▼
Google Identity Services
   │
   │ ID Token
   ▼
Angular
   │
   │ POST /api/auth/google
   ▼
Symfony
   │
   ├── verifica token
   ├── valida usuario
   ├── crea o vincula cuenta
   └── genera JWT
   │
   ▼
Cookie de autenticación
   │
   ▼
GET /api/me
   │
   ▼
Angular
   │
   ▼
Redirección según rol
```