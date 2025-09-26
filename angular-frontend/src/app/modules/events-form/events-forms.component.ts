import { Component } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { EventsFormsService } from '../../shared/service/events-forms.service';

@Component({
  selector: 'app-events-forms',
  imports: [ReactiveFormsModule],
  templateUrl: './events-forms.component.html',
})
export class EventsFormsComponent {
  formulario: FormGroup;

  constructor(
    private fb: FormBuilder,
    private eventsFormsService: EventsFormsService
  ) {
    this.formulario = this.fb.group({
      nombre: ['', Validators.required],
      telefono: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      evento: ['', Validators.required],
      fecha: ['', Validators.required],
      mensaje: [''],
      acepta: [false, Validators.requiredTrue],
    });
  }

  onSubmit() {
    if (this.formulario.valid) {
      this.eventsFormsService.sendEventForm(this.formulario.value).subscribe({
        next: (response) => {
          console.log('Formulario enviado con éxito', response);
          this.formulario.reset();
        },
        error: (error) => {
          console.error('Error al enviar el formulario', error);
        },
      });
    } else {
      this.formulario.markAllAsTouched();
    }
  }
}
