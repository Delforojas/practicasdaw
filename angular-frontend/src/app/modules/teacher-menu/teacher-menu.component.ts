import { Component, OnInit } from '@angular/core';
import { User } from '../../shared/interfaces/User';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router'; 
import { Observable } from 'rxjs';
import { ConfirmModalComponent } from '../../shared/logout/logout';
import { AuthService } from '../../shared/service/auth.service';

@Component({
  selector: 'app-teacher-menu',
  imports: [],
  templateUrl: './teacher-menu.component.html',
})


export class TeacherMenuComponent {
 userData: User | null = null;
 showLogoutModal: boolean = false;


 constructor(
    private authService: AuthService,
    public router: Router ) 
    {
 }


ngOnInit(): void {
  this.authService.loadUser().subscribe(u => {
    this.userData = u;
  });
}

 
logout(): void {
  this.authService.logout();           
  this.router.navigate(['/login']);  
}

handleLogoutClick() {
   this.showLogoutModal = true;
 }


}
 



