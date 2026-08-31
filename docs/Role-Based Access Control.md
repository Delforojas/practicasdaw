## 👥 Role-Based Access Control

El sistema diferencia entre distintos tipos de usuario:

```text
ROLE_ADMIN
ROLE_TEACHER
ROLE_USER
```

La navegación del frontend se adapta al rol recibido desde el backend:

```text
ROLE_ADMIN   → /admin
ROLE_TEACHER → /teacher
ROLE_USER    → /user
```

---