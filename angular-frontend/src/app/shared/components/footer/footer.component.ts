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
    { path: '/', fragment: 'servicios', label: 'Centro de Operaciones' },
    { path: '/', fragment: 'about', label: 'La iniciativa' },
    { path: '/', fragment: 'ourTeams', label: 'Amenazas' },
    { path: '/', fragment: 'horarios', label: 'Cronologia' },
    { path: 'login', fragment: undefined, label: 'Acceso S.H.I.E.L.D' },
  ];
}
