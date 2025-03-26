import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivatedRoute } from '@angular/router';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, FormArray, FormControl, Validators, ReactiveFormsModule } from '@angular/forms';

@Component({
  selector: 'app-confirmacion-reserva',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './confirmacion-reserva.component.html',
  styleUrl: './confirmacion-reserva.component.css'
})
export class ConfirmacionReservaComponent {
  reservaForm!: FormGroup;
  alojamiento: any;
  tiposDocumento = ['dni', 'nie', 'passport'];

  constructor(private route: ActivatedRoute, private fb: FormBuilder, private router: Router) {
   const state = history.state;
   if (state?.alojamiento) {
     this.alojamiento = state.alojamiento;
   }
   this.reservaForm = this.fb.group({
    acompanantes: this.fb.array([]), // array de acompañantes
    comentarios: ['']
   });
  }

  get acompanantes() {
    return this.reservaForm.get('acompanantes') as FormArray;
  }

  agregarAcompanante() {
    const nuevo = this.fb.group({
      nombre: ['', Validators.required],
      papellido: ['', Validators.required],
      sapellido: [''],
      documento: ['', Validators.required],
      num_documento: ['', Validators.required]
    });
    this.acompanantes.push(nuevo);
  }

  eliminarAcompanante(index: number) {
    this.acompanantes.removeAt(index);
  }

  confirmarReserva() {
    const datosReserva = {
      alojamiento: this.alojamiento,
      acompanantes: this.acompanantes.value,
      comentarios: this.reservaForm.get('comentarios')?.value
    };

    console.log('Reserva confirmada:', datosReserva);
    // Futura llamada a la API
    this.router.navigate(['/reserva-confirmada']);

  }
}
