import { CommonModule } from '@angular/common';

import { Component, HostListener } from '@angular/core';
import { NavigationEnd, Router, RouterLink,RouterLinkActive} from '@angular/router';



@Component({
  selector: 'app-navbar',
  imports: [RouterLink, CommonModule, RouterLinkActive],
  templateUrl: './navbar.component.html',
})
export class NavbarComponent {
  // Clases compartidas
  activeClasses = 'font-bold underline text-[#E8DBC4]';
  linkClasses = 'text-[#C4C4C4] hover:text-white no-underline font-normal';

  // Menú móvil

  menuOpen = false;

  // Lista de enlaces

  navLinks = [
    { path: '/', fragment: 'inicio', label: 'Inicio' },
    { path: '/', fragment: 'servicios', label: 'Servicios' },
    { path: '/', fragment: 'about', label: '¿Qué ofrecemos?' },
    { path: '/', fragment: 'horarios', label: 'Horarios y Tarifas' },
    { path: '/', fragment: 'ourTeams', label: 'Sobre Nosotros' },
    { path: '/', fragment: 'schedules', label: 'Horarios' },
    { path: 'login', fragment: undefined, label: 'Log in' },
  ];

  activeFragment: string | null = null;

  constructor(private router: Router) {
    this.router.events.subscribe((event) => {
      if (event instanceof NavigationEnd) {
        const tree = this.router.parseUrl(this.router.url);
        this.activeFragment = tree.fragment;
      }
    });
  }

  isActive(link: any): boolean {
    // Caso 1: login → ruta exacta
    if (link.path === 'login') {
      return this.router.url === '/login';
    }

    // Caso 2: secciones de la home con fragmento
    if (link.path === '/') {
      return this.router.url.includes(`#${link.fragment}`);
    }

    return false;
  }

  // Abrir/cerrar menú móvil

  toggleMenu() {
    this.menuOpen = !this.menuOpen;
  }

  closeMenu() {
    this.menuOpen = false;
  }

  // Cerrar con ESC
}
