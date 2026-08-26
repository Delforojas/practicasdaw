import { Component } from '@angular/core';

@Component({
  selector: 'app-about',
  imports: [],
  templateUrl: './about.component.html',
})
export class AboutComponent {

  title = '¿Estás preparado para la iniciativa?';
  primaryText = `
    S.H.I.E.L.D. busca agentes capaces de enfrentarse a amenazas que van más allá de lo convencional. Accede a misiones, entrenamientos especializados y tecnología desarrollada para proteger el planeta.
  `;

  secondaryText = `
    Forma equipo con los Vengadores, mejora tus habilidades y participa en operaciones junto a héroes como Iron Man, Thor, Captain America o Black Widow.
  `;

  extraText = `
    Cada misión supondrá un nuevo desafío. Consulta las operaciones disponibles y descubre dónde se necesita tu ayuda.
  `;

}
