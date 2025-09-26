import { HttpErrorResponse } from '@angular/common/http';
import { FormGroup } from '@angular/forms';
import { ToastService } from '../service/toast.service';
import { showToast } from './test-messages';


export function handleHttpError(
  err: HttpErrorResponse,
  toast: ToastService,
  form?: FormGroup
) {
  switch (err.status) {
    case 0: 
      showToast(toast, 'unexpectedError');
      break;

    case 400: 
      showToast(toast, 'passwordResetError', err.error?.message || err.error?.error);
      break;

    case 401: 
      showToast(toast, 'loginError');
      break;

    case 409: 
      showToast(toast, 'registerConflict', err.error?.message || err.error?.error);
      break;

    default: 
      showToast(toast, 'unexpectedError');
      break;
  }
}