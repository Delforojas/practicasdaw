import { Component, signal } from "@angular/core";
import { RouterOutlet, RouterModule, Router } from "@angular/router";
import { CommonModule } from "@angular/common";
import { ToastComponent } from './shared/components/toast/toast.component';



@Component({

selector: 'app-root',
standalone: true,     // Se añade sntandalone para que el componente sea autonomo. DRE
imports: [RouterOutlet, RouterModule, CommonModule, ToastComponent],
templateUrl: './app.component.html',
styleUrls: ['./app.component.css']


})

// AppComponent como contenedor de rutas (usa <router-outlet> para mostrar componentes según la URL DRE 

export class AppComponent {
 currentUrl = signal('');

// Inyecta el servicio Router DRE
 constructor(public router: Router) {


    

 }

/*
import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';


@Component({
  selector: 'app-root',
  imports: [
    RouterOutlet,
  ],
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent {
  title = 'angular-frontend';
}*/ 


}