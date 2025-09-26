import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, FormsModule, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../shared/service/auth.service';
import { LoginDTO } from '../../shared/interfaces/LoginDTO';
import { NavbarComponent } from '../landing/components/navbar/navbar.component';
import { FooterComponent } from "../../shared/components/footer/footer.component";
import { handleHttpError } from '../../shared/utils/http-error';
import { ToastService } from '../../shared/service/toast.service';
import {  showToast } from '../../shared/utils/test-messages';
import { HttpErrorResponse } from '@angular/common/http';
import { NavigationService } from '../../shared/service/navigation.service';




@Component({
 selector: 'app-login',
 standalone: true,
 imports: [
    CommonModule,
    ReactiveFormsModule, NavbarComponent,
    FooterComponent
],
 templateUrl: './login.component.html',
 styleUrls: []
})

export class LoginComponent {
  form!: FormGroup;
  isLoading = false;

  constructor(
    private fb: FormBuilder,
    private auth: AuthService,
    private router: Router,
    private toast: ToastService,
    private navigation: NavigationService
    ) {
    this.form = this.fb.group({
    email: ['', [Validators.required, Validators.email]],
    password: ['', [
      Validators.required,
      Validators.minLength(8),
      Validators.pattern('^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[^A-Za-z0-9]).{8,}$')
    ]]
  });
    }

submit() {
  if (this.form.invalid) {
    this.form.markAllAsTouched();
    return;
  }

  const creds = this.form.value as LoginDTO;

  this.isLoading = true;
 
  this.auth.login(creds).subscribe({
    next: () => {
      this.auth.getMe().subscribe({
        next: (u) => {
          this.isLoading = false;
          showToast(this.toast, 'loginSuccess', u.username); 
          this.navigation.goToRole(u.role);
       
          },
        error: () => {
        this.isLoading = false;
         showToast(this.toast, 'unexpectedError');
        
      },

      });
    },
      error: (err: HttpErrorResponse) => {
        this.isLoading = false;
        handleHttpError(err, this.toast, this.form);
      },
    
  });
}


get email() { return this.form.get('email'); }
get password() { return this.form.get('password'); }


}