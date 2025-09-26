import { Component } from '@angular/core';
import { RouterLink,} from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-footer',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './footer.component.html',
})
export class FooterComponent {
  footerLinks = [
    { path: '/', fragment: 'inicio', label: 'Inicio' },
    { path: '/', fragment: 'servicios', label: 'Servicios' },
    { path: '/', fragment: 'about', label: '¿Qué ofrecemos?' },
    { path: '/', fragment: 'horarios', label: 'Horarios y Tarifas' },
    { path: '/', fragment: 'ourTeams', label: 'Sobre Nosotros' },
    { path: 'login', fragment: undefined, label: 'Log in' },
  ];
}
