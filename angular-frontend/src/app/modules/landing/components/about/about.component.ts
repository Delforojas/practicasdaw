import { Component } from '@angular/core';

@Component({
  selector: 'app-about',
  imports: [],
  templateUrl: './about.component.html',
})
export class AboutComponent {

  title = '¿Qué ofrecemos?';
  primaryText = `
    En Sannu Fisioterapia, ofrecemos una experiencia única donde puedes encontrar equilibrio físico y mental. 
    Desde clases de Yoga, Pilates y Ejercicio Terapéutico, consultas de Fisioterapia. 
    Nuestro espacio multifuncional está diseñado para satisfacer todas tus necesidades de bienestar y trabajo.
  `;

  secondaryText = `
    Sumérgete en la tranquilidad de nuestras clases, donde nuestros instructores expertos te guiarán a través de prácticas 
    que te ayudarán a fortalecer tu cuerpo y calmar tu mente. Con una variedad de estilos y niveles, hay algo para todos, 
    ya seas un principiante entusiasta o un yogui experimentado.
  `;

  extraText = `
    Además de nuestras clases y consultas, ofrecemos alquiler de salas completamente equipadas para tus necesidades profesionales. 
    Ya sea que estés organizando una reunión, un taller o un evento, nuestras salas son el lugar perfecto para inspirar la creatividad 
    y fomentar la colaboración.
  `;

}
