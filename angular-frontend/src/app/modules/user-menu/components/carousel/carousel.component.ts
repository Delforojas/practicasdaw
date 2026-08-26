import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';

@Component({
  selector: 'app-carousel',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './carousel.component.html'
})
export class CarouselComponent {
  

  images = [
    { name: 'Capitan América', img: 'user-menu-assets/carrousel-yogamultinivel.webp', path: '/clase/multinivel' },
    { name: 'Ironman', img: 'user-menu-assets/carrousel-yogaflow.webp', path: '/clase/flow' },
    { name: 'Thor', img: 'user-menu-assets/carrousel-rocketyoga.webp', path: '/clase/rocket' },
    { name: 'Viuda Negra', img: 'user-menu-assets/carrousel-yinyoga.webp', path: '/clase/yin' },
    { name: 'Spiderman', img: 'user-menu-assets/carrousel-pilates.webp', path: '/clase/pilates' },
  ];


  currentIndex = 1; // la del medio inicialmente

  get visibleImages() {
    // Devuelve las 3 imágenes a mostrar: [prev, current, next]
    const len = this.images.length;
    const prev = (this.currentIndex - 1 + len) % len;
    const next = (this.currentIndex + 1) % len;
    return [this.images[prev], this.images[this.currentIndex], this.images[next]];
  }

  constructor(private router: Router) {}

  next() {
    this.currentIndex = (this.currentIndex + 1) % this.images.length;
  }

  prev() {
    this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
  }

  goTo(path: string) {
    // Las rutas aún no existen, pero esto funcionará cuando se creen
    this.router.navigate([path]);
  }
}
