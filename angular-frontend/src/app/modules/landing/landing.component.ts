import { Component } from '@angular/core';
import { OurTeamComponent } from './components/our-team/our-team.component';
import { OurServicesComponent } from './components/our-services/our-services.component';
import { HeroComponent } from './components/hero/hero.component';
import { NavbarComponent } from './components/navbar/navbar.component';
import { AboutComponent } from './components/about/about.component';
import { ScheduleComponent } from './components/schedule/schedule.component';
import { FooterComponent } from '../../shared/components/footer/footer.component';

@Component({
  selector: 'app-landing',
  imports: [
    NavbarComponent,
    HeroComponent,
    OurTeamComponent,
    OurServicesComponent,
    AboutComponent,
    ScheduleComponent,
    FooterComponent,
  ],

  templateUrl: './landing.component.html',
})
export class LandingComponent {}
