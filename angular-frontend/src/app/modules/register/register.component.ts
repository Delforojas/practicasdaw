import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, FormsModule, Validators, AbstractControl, ReactiveFormsModule } from '@angular/forms';
import { Router, RouterLink, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../shared/service/auth.service';
import { RegisterDTO } from '../../shared/interfaces/RegisterDTO';
import { ToastService } from '../../shared/service/toast.service'; 
import { handleHttpError } from '../../shared/utils/http-error';
import { HttpErrorResponse } from '@angular/common/http';
import { showToast } from '../../shared/utils/test-messages';
import { NavigationService } from '../../shared/service/navigation.service';



@Component({
  selector: 'app-register',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
  ],
  templateUrl: './register.component.html',
  styleUrls: []

})
export class RegisterComponent implements OnInit {

  registerForm!: FormGroup;
  isLoading: boolean = false;
  selectedFile: File | null = null;


  constructor(
    private fb: FormBuilder,
    private auth: AuthService,
    private router: Router,
    private toast: ToastService,
    private navigation :NavigationService
  ) { }

      ngOnInit(): void {
        this.registerForm = this.fb.group({
          username: ['', Validators.required],
          full_name: ['', Validators.required],
          email: ['', [Validators.required, Validators.email]],
          phone: ['', [Validators.pattern(/^\d{9}$/)]],
          password: [
                    '',
                    [
                      Validators.required,
                      Validators.minLength(6),
                      Validators.pattern(/^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>]).{6,}$/)
                    ]
                  ],
          confirmPassword: ['', Validators.required],
          surname: [''],
          address: [''],
          postal_code: [''],
          city: [''],
          profileImage: [null]  
        }, { validators: this.passwordMatchValidator });
      }

      onSubmit(): void {
          if (this.registerForm.invalid) { /* ... */ }

          this.isLoading = true;

          const { full_name, username, email, password, phone, surname, address, postal_code, city  ,} = this.registerForm.value;

          // 3.1 DTO tipado (útil para autocompletado/validación)
          const dto: RegisterDTO = {
            full_name: String(full_name).trim(),
            username: String(username).trim(),
            email: String(email).trim().toLowerCase(),
            password,
            phone: phone ? String(phone).trim() : undefined,
            surname: surname ? String(surname).trim() : undefined,
            address: address ? String(address).trim() : undefined,
            postal_code: postal_code ? String(postal_code).trim() : undefined,
            city: city ? String(city).trim() : undefined,
            profileImage: this.selectedFile ?? undefined, // 👈 importante
          };

          const formData = new FormData();
          formData.append('full_name', dto.full_name);
          formData.append('username', dto.username);
          formData.append('email', dto.email);
          formData.append('password', dto.password);

          if (dto.phone) formData.append('phone', dto.phone);
          if (dto.surname) formData.append('surname', dto.surname);
          if (dto.address) formData.append('address', dto.address);
          if (dto.postal_code) formData.append('postal_code', dto.postal_code);
          if (dto.city) formData.append('city', dto.city);
          if (dto.profileImage) formData.append('profileImage', dto.profileImage);

          this.auth.register(formData).subscribe({
            next: () => {
              this.isLoading = false;
              showToast(this.toast, 'registerSuccess');
              this.navigation.goTo('/login');
            },
            error: (err: HttpErrorResponse) => {
              this.isLoading = false;
              handleHttpError(err, this.toast, this.registerForm);
            },
          });
        }

        onFileSelected(ev: Event) {
            const input = ev.target as HTMLInputElement;
            if (input.files?.length) {
              this.selectedFile = input.files[0];
              this.registerForm.patchValue({ profileImage: this.selectedFile });
            }
          }

        passwordMatchValidator(control: AbstractControl): { [key: string]: boolean } | null {
          const password = control.get('password');
          const confirmPassword = control.get('confirmPassword');
          if (!password || !confirmPassword) return null;
          return password.value === confirmPassword.value ? null : { mismatch: true };
        }

        get confirmPassword() { return this.registerForm.get('confirmPassword'); }
        get full_name() { return this.registerForm.get('full_name'); }
        get username() { return this.registerForm.get('username'); }
        get email() { return this.registerForm.get('email')}
        get phone() { return this.registerForm.get('phone'); }
        get password() { return this.registerForm.get('password'); }
      }
