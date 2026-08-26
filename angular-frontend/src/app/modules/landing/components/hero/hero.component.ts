import { Component } from '@angular/core';

import { RouterLink } from '@angular/router';

import { TechnicalDetailsComponent } from '../technical-details/technical-details.component';

@Component({

  selector: 'app-hero',

  standalone: true,

  imports: [

    RouterLink,

    TechnicalDetailsComponent

  ],

  templateUrl: './hero.component.html',

})

export class HeroComponent {

  showTechnicalDetails = false;

  openTechnicalDetails(): void {

    this.showTechnicalDetails = true;

  }

  closeTechnicalDetails(): void {

    this.showTechnicalDetails = false;

  }

}