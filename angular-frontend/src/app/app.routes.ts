import { Routes } from '@angular/router';
import { LandingComponent } from './modules/landing/landing.component';
import { LoginComponent } from './modules/login/login.component';
import { RegisterComponent } from './modules/register/register.component';
import { UserMenuComponent } from './modules/user-menu/user-menu.component';
import { AdminMenuComponent } from './modules/admin-menu/admin-menu.component';
import { TeacherMenuComponent } from './modules/teacher-menu/teacher-menu.component';
import { UserProfileComponent } from './modules/user-profile/user-profile.component';
import { AboutUsComponent } from './modules/about-us/about-us.component';


import { ResetPasswordComponent } from './modules/password-reset/password-reset';
import { ForgotPasswordComponent } from './modules/password-forgot/password-forgot';
import { EventsFormsComponent } from './modules/events-form/events-forms.component';




//import { RoleGuard } from './modules/guards/role.guards';
export const routes: Routes = [
  { path: '', component: LandingComponent },
 
  { path: 'login', component: LoginComponent }, // ruta de login

  { path: 'register', component: RegisterComponent }, // ruta de registro

  { path: 'user', component: UserMenuComponent },// ruta de usuario
  { path: 'events', component: EventsFormsComponent}, // ruta de eventos 2
  { path: 'user-profile', component: UserProfileComponent },

  {
    path: 'admin',
    component: AdminMenuComponent,
    /*canActivate: [RoleGuard],
    data: { expectedRole: 'ROLE_ADMIN', }*/
  }, 
  { path: 'teacher', component: TeacherMenuComponent,
    /*canActivate: [RoleGuard],
    data: { expectedRole: 'ROLE_TEACHER' }*/
   }, 


  { path: 'about-us', component: AboutUsComponent},// ruta para el sobre nosotros


  { path: 'forgot-password', component: ForgotPasswordComponent },
  { path: 'reset-password', component: ResetPasswordComponent }, 

  
  { path: '**', redirectTo: '' },
 

];
