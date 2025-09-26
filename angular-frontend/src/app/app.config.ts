import { ApplicationConfig, provideZoneChangeDetection } from '@angular/core';
import { provideRouter, withInMemoryScrolling } from '@angular/router';
import { routes } from './app.routes';
import { provideHttpClient, withInterceptorsFromDi } from '@angular/common/http';

export const appConfig: ApplicationConfig = {
  providers: [
    provideZoneChangeDetection({ eventCoalescing: true }),
    provideRouter(
      routes,
      withInMemoryScrolling({
        anchorScrolling: 'enabled', 
        scrollPositionRestoration: 'enabled', 
      })
    ),
    provideHttpClient(withInterceptorsFromDi()),
  ],
};


//Se registra  HttpClient , ya que viene con el Servicio y es el modulo que realiza las peticiones a la API.DRE 