import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { PasswordResetService } from '../../shared/service/password-reset.service';
import { ToastService } from '../../shared/service/toast.service';
import { handleHttpError } from '../../shared/utils/http-error';
import { showToast } from '../../shared/utils/test-messages';
import { HttpErrorResponse } from '@angular/common/http';
import { NavigationService } from '../../shared/service/navigation.service';

@Component({
  selector: 'app-reset-password',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './password-reset.html',
  styleUrls: []
})
export class ResetPasswordComponent implements OnInit {
  form!: FormGroup;
  isLoading = false;

  constructor(
    private fb: FormBuilder,
    private resetSrv: PasswordResetService,
    private toast: ToastService,
    private router: Router,
    private route: ActivatedRoute,
    private navigation: NavigationService
  ) {}

  ngOnInit(): void {
    const tokenFromUrl = this.route.snapshot.queryParamMap.get('token') || '';

    this.form = this.fb.group({
      token: [tokenFromUrl, [Validators.required]],
      password: ['', [Validators.required, Validators.minLength(8)]],
    });
  }

  submit(): void {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      showToast(this.toast, 'registerFormInvalid');
      return;
    }

    this.isLoading = true;

    const { token, password } = this.form.value;

    this.resetSrv.reset(token, password).subscribe({
      next: () => {
        this.isLoading = false;
        showToast(this.toast, 'passwordResetSuccess');
        this.navigation.goTo('/login');;
      },
      error: (err: HttpErrorResponse) => {
        this.isLoading = false;

         handleHttpError(err, this.toast, this.form);
      
        if ((err?.error?.message || '').toLowerCase().includes('token')) {
          const c = this.form.get('token');
          c?.setErrors({ api: true });
          c?.markAsTouched();
        }

      }
    });
  }

  get token() { return this.form.get('token'); }
  get password() { return this.form.get('password'); }
}