import { ToastService } from '../service/toast.service';

type ToastKey =
  | 'loginSuccess'
  | 'loginError'
  | 'registerSuccess'
  | 'registerFormInvalid'
  | 'passwordMismatch'
  | 'registerConflict'
  | 'invalidEmail'
  | 'forgotPasswordSuccess'
  | 'passwordResetSuccess'
  | 'passwordResetError'
  | 'unexpectedError';

export function showToast(
  toast: ToastService,
  key: ToastKey,
  arg?: string
) {
  switch (key) {
    case 'loginSuccess':
      toast.showToast(`¡Bienvenido, ${arg || 'Usuario'}!`, 'success');
      break;

    case 'loginError':
      toast.showToast('Credenciales incorrectas. Intenta de nuevo.', 'error');
      break;

    case 'registerSuccess':
      toast.showToast('Usuario registrado con éxito', 'success');
      break;

    case 'registerFormInvalid':
      toast.showToast('Completa los campos obligatorios.', 'error');
      break;

    case 'passwordMismatch':
      toast.showToast('Las contraseñas no coinciden, por favor verifica.', 'error');
      break;

    case 'registerConflict':
      toast.showToast(arg || 'El email o el usuario ya existe.', 'error');
      break;

    case 'invalidEmail':
      toast.showToast('Introduce un email válido.', 'error');
      break;

    case 'forgotPasswordSuccess':
      toast.showToast(arg || 'Si el email existe, recibirás un enlace de recuperación.', 'success');
      break;

    case 'passwordResetSuccess':
      toast.showToast('Contraseña actualizada. Inicia sesión.', 'success');
      break;

    case 'passwordResetError':
      toast.showToast(arg || 'No se pudo restablecer la contraseña.', 'error');
      break;

    default:
      toast.showToast('Error inesperado. Vuelve a intentarlo más tarde.', 'error');
      break;
  }
}
/*
export function showLoginSuccess(toast: ToastService, username: string) {
  toast.showToast(`¡Bienvenido, ${username}!`, 'success');
}


export function showLoginError(toast: ToastService) {
  toast.showToast('Credenciales incorrectas. Intenta de nuevo.', 'error');
}


export function showRegisterSuccess(toast: ToastService) {
  toast.showToast('Usuario Registrado con exito ', 'success');
}

export function showRegisterFormInvalid(toast: ToastService) {
  toast.showToast('Completa los campos obligatorios.', 'error');
}

export function showPasswordMismatch(toast: ToastService) {
  toast.showToast('Las contraseñas no coinciden, por favor verifica.', 'error');
}
export function showRegisterConflict(toast: ToastService, msg?: string) {
  toast.showToast(msg || 'El email o el usuario ya existe.', 'error');
}
export function showInvalidEmail(toast: ToastService) {
  toast.showToast('Introduce un email válido.', 'error');
}

export function showForgotPasswordSuccess(toast: ToastService, msg?: string) {
  toast.showToast(
    msg || 'Si el email existe, recibirás un enlace de recuperación.',
    'success'
  );
}
export function showPasswordResetSuccess(toast: ToastService) {
  toast.showToast('Contraseña actualizada. Inicia sesión.', 'success');
}

export function showPasswordResetError(toast: ToastService, msg?: string) {
  toast.showToast(msg || 'No se pudo restablecer la contraseña.', 'error');
}

export function showUnexpectedError(toast: ToastService) {
  toast.showToast('Error inesperado. Vuelve a intentarlo más tarde.', 'error');
}

*/