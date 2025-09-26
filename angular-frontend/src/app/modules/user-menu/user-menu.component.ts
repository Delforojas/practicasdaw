import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../shared/service/auth.service';
import { User } from '../../shared/interfaces/User';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router'; 
import { Observable } from 'rxjs';
import { ConfirmModalComponent } from '../../shared/logout/logout';

import { NavbarComponent } from '../../modules/landing/components/navbar/navbar.component';
import { CarouselComponent } from './components/carousel/carousel.component';
import { FisioUmComponent } from "./components/fisio-um/fisio-um.component";
import { FooterComponent } from "../../shared/components/footer/footer.component";
import { EventsUmComponent } from "./components/events-um/events-um.component";



@Component({
 selector: 'app-user-menu',
 templateUrl: './user-menu.component.html',
 standalone: true,

 imports: [CommonModule, CarouselComponent, FisioUmComponent, FooterComponent, EventsUmComponent]

})

export class UserMenuComponent implements OnInit {



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
 
