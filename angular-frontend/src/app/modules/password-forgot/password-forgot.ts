import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { PasswordResetService } from '../../shared/service/password-reset.service';
import { ToastService } from '../../shared/service/toast.service';
import { HttpErrorResponse } from '@angular/common/http';
import { handleHttpError } from '../../shared/utils/http-error';
import { showToast } from '../../shared/utils/test-messages';
import { NavigationService } from '../../shared/service/navigation.service';

@Component({
  selector: 'app-forgot-password',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './password-forgot.html',
  
})
export class ForgotPasswordComponent implements OnInit {
  form!: FormGroup;
  isLoading = false;

  constructor(
    private fb: FormBuilder,
    private passwordResetService: PasswordResetService,
    private toast: ToastService,
    private navigation: NavigationService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.form = this.fb.group({
      email: ['', [Validators.required, Validators.email]]
    });
  }

  get email() {
    return this.form.get('email');
  }

  submit(): void {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      showToast(this.toast, 'invalidEmail');
      return;
    }

    this.isLoading = true;
    const email = this.form.value.email;

    this.passwordResetService.forgot(email).subscribe({
      next: (res) => {
        this.isLoading = false;
        showToast(this.toast, 'forgotPasswordSuccess', res?.message);
        this.navigation.goTo('/reset-password');
      },
      error: (err: HttpErrorResponse) => {
        this.isLoading = false;
        handleHttpError(err, this.toast, this.form); 
      }
    });
  }
}

