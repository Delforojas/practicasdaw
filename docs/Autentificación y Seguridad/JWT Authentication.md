## 🪪 JWT Authentication

- Generación de JWT desde Symfony.
- Uso del token para mantener la sesión.
- Gestión de autenticación mediante cookies.
- Protección de rutas.
- Protección de endpoints.
- Diferenciación de permisos según rol.
- Configuración distinta entre desarrollo y producción.

## Sesiones y revocación

- El logout elimina la cookie `authtoken` del navegador.
- Los JWT no se revocan en el servidor actualmente; un token copiado fuera del navegador sigue siendo válido hasta su expiración.
- Cambiar la contraseña tampoco invalida globalmente los JWT emitidos anteriormente.
- Como mejora futura, se recomienda implementar token versioning o una estrategia explícita de invalidación de tokens.

---
