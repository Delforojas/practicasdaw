# 🦸 Avengers Training

Aplicación web **Full Stack** inspirada en el universo Avengers, desarrollada inicialmente durante mis prácticas profesionales de DAW y ampliada posteriormente como proyecto personal.

El objetivo del proyecto es trabajar una arquitectura real separando **frontend, backend, autenticación, base de datos y despliegue en producción**.

---

## 📌 Evolución del proyecto

Este proyecto comenzó durante mis prácticas profesionales de Desarrollo de Aplicaciones Web.

Durante esa etapa trabajé principalmente en el desarrollo de funcionalidades backend y frontend utilizando **Symfony, Angular, PostgreSQL y Docker**.

Posteriormente he continuado desarrollándolo de forma independiente, ampliándolo con nuevas funcionalidades, mejoras de arquitectura, autenticación con Google, recuperación de contraseña, despliegue en producción y una base de datos PostgreSQL gestionada en la nube.

Actualmente el proyecto funciona tanto en **entorno local** como en **producción**.
## 🛠️ Stack principal

### Frontend

- Angular

- TypeScript

- Tailwind CSS

- RxJS

### Backend

- PHP

- Symfony

- Doctrine ORM

- REST API

### Base de datos

- PostgreSQL

- Neon

### Autenticación

- JWT

- Google Identity Services

- Role-Based Access Control

- Cookies

### Infraestructura

- Docker

- Docker Compose

- Apache

- Alwaysdata

- Git / GitHub
---

 
---

## 🎨 Frontend — Angular + TypeScript + Tailwind CSS

- Desarrollo de una **Single Page Application** con Angular.
- Uso de TypeScript.
- Arquitectura basada en componentes standalone.
- Creación de componentes reutilizables.
- Uso de servicios Angular para centralizar:
  - autenticación;
  - navegación;
  - comunicación HTTP;
  - usuario autenticado;
  - mensajes;
  - notificaciones.
- Comunicación con API REST mediante `HttpClient`.
- Gestión de autenticación mediante `withCredentials`.
- Uso de Observables.
- Uso de RxJS.
- Gestión del estado del usuario mediante `BehaviorSubject`.
- Implementación de formularios reactivos.
- Validación de:
  - email;
  - contraseñas;
  - campos obligatorios;
  - teléfono;
  - confirmación de contraseña;
  - reglas personalizadas.
- Gestión de estados de carga.
- Gestión de errores del backend.
- Sistema reutilizable de Toasts.
- Navegación según rol del usuario.
- Desarrollo de flujos de:
  - login;
  - registro;
  - login con Google;
  - recuperación de contraseña;
  - restablecimiento de contraseña;
  - perfil de usuario;
  - navegación privada.
- Centralización de rutas de API.
- Refactorización de servicios.
- Refactorización de Observables.
- Organización modular del código.
- Mejora de mantenibilidad y legibilidad.
- Desarrollo de interfaces responsive.
- Adaptación para móvil, tablet y escritorio.
- Diseño de interfaz mediante Tailwind CSS.
- Mejora progresiva de UX/UI.

---

# 🔐 Autenticación y seguridad

El proyecto implementa diferentes mecanismos relacionados con autenticación y autorización.

## 🔑 Login tradicional

- Autenticación mediante email y contraseña.
- Contraseñas gestionadas de forma segura desde Symfony.
- Generación de JWT.
- Gestión de sesión mediante cookies.
- Acceso a recursos privados.
- Recuperación del usuario autenticado mediante `/api/me`.

---

## 🪪 JWT Authentication

- Generación de JWT desde Symfony.
- Uso del token para mantener la sesión.
- Gestión de autenticación mediante cookies.
- Protección de rutas.
- Protección de endpoints.
- Diferenciación de permisos según rol.
- Configuración distinta entre desarrollo y producción.

---

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

# 🔑 Login con Google

Se ha integrado **Google Identity Services** para permitir autenticación mediante cuenta de Google.

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

## ✅ Funcionalidades implementadas

- Integración de Google Identity Services.
- Configuración mediante Google Client ID.
- Obtención de Google ID Token.
- Envío del token al backend.
- Verificación del token mediante Google API.
- Validación de:
  - audience;
  - issuer;
  - expiración;
  - email verificado;
  - identificador de Google.
- Búsqueda de usuario mediante `googleId`.
- Asociación con usuario existente mediante email.
- Creación de usuario si no existe.
- Generación del JWT propio de la aplicación.
- Gestión de sesión mediante cookie.
- Recuperación del usuario mediante `/api/me`.

---

# 🔄 Recuperación de contraseña

Se ha desarrollado un flujo completo de recuperación de contraseña.

## ✅ Incluye

- solicitud de recuperación mediante email;
- generación de token temporal;
- almacenamiento del token;
- expiración del token;
- envío de correo electrónico;
- validación del token;
- establecimiento de nueva contraseña;
- gestión de errores;
- mensajes al usuario.

## 🔄 Flujo

```text
Usuario
   │
   ▼
Forgot Password
   │
   ▼
Symfony
   │
   ├── genera token
   ├── almacena token
   └── envía email
   │
   ▼
Reset Password
   │
   ▼
Nueva contraseña
```

---

# 🗄️ Base de datos

## PostgreSQL

La aplicación utiliza PostgreSQL como sistema principal de persistencia.

Durante el desarrollo se han trabajado:

- entidades;
- repositories;
- relaciones;
- constraints;
- consultas;
- migraciones;
- índices;
- validaciones;
- persistencia mediante Doctrine ORM.

## Entornos

### Local

```text
PostgreSQL
Docker
```

### Producción

```text
PostgreSQL
Neon
```

---

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

# 🧠 Problemas técnicos resueltos

Durante el desarrollo y despliegue se han resuelto diferentes problemas reales de integración.

Entre ellos:

- configuración de CORS;
- configuración de cookies;
- cookies `Secure`;
- diferencias entre HTTP local y HTTPS en producción;
- autenticación JWT;
- routing entre Angular y Symfony;
- configuración de `.htaccess`;
- configuración de Apache;
- fallback SPA;
- configuración de variables de entorno;
- diferencias entre `.env` y `.env.local`;
- configuración de `APP_ENV`;
- configuración de `APP_DEBUG`;
- configuración del endpoint de producción;
- configuración del endpoint local;
- builds Angular;
- bundles antiguos desplegados;
- uso incorrecto de environments;
- configuración de `fileReplacements`;
- despliegue del frontend dentro de Symfony;
- migraciones PostgreSQL;
- conexión con Neon;
- dependencias Composer;
- configuración de Google Client ID;
- Authorized JavaScript Origins;
- Google Identity Services;
- carga asíncrona del SDK de Google;
- inicialización repetida de `google.accounts.id.initialize()`;
- control de inicialización de Google GIS;
- integración Google frontend/backend;
- depuración de `/api/auth/google`;
- gestión de sesión después del login Google;
- convivencia entre `index.html` e `index.php`;
- recuperación de `public/index.php`;
- diagnóstico de respuestas `text/html` incorrectas desde endpoints API;
- comprobaciones mediante `curl`;
- diagnóstico mediante Network del navegador;
- revisión de headers HTTP;
- debugging mediante Symfony Console;
- debugging mediante logs PHP y Apache;
- gestión de ramas Git;
- despliegues entre local y producción.

---

# 🚀 Estado del proyecto

Actualmente el proyecto dispone de:

- frontend Angular;
- backend Symfony;
- PostgreSQL;
- autenticación JWT;
- login tradicional;
- login mediante Google;
- recuperación de contraseña;
- roles de usuario;
- rutas privadas;
- perfiles;
- API REST;
- diseño responsive;
- despliegue en producción.

El proyecto continúa en evolución.