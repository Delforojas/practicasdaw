import { Component, inject } from '@angular/core';
import { Router, RouterLink, RouterLinkActive } from '@angular/router';
import { AuthService } from '../../service/auth.service';

@Component({
  selector: 'app-private-navbar',
  standalone: true,
  imports: [RouterLink, RouterLinkActive],
  templateUrl: './private-navbar.component.html'
})
export class PrivateNavbarComponent {
  menuOpen = false;
  isLoggingOut = false;
  logoutError = '';

  private readonly authService = inject(AuthService);
  private readonly router = inject(Router);

  toggleMenu(): void {
    this.menuOpen = !this.menuOpen;
  }

  closeMenu(): void {
    this.menuOpen = false;
  }

  logout(): void {
    if (this.isLoggingOut) {
      return;
    }

    this.closeMenu();
    this.logoutError = '';
    this.isLoggingOut = true;

    this.authService.logout().subscribe({
      next: () => {
        this.isLoggingOut = false;
        void this.router.navigateByUrl('/login', { replaceUrl: true });
      },
      error: () => {
        this.isLoggingOut = false;
        this.logoutError = 'No se pudo cerrar la sesión. Inténtalo de nuevo.';
      }
    });
  }
}
