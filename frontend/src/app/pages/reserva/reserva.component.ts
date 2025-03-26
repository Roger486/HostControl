import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  selector: 'app-reserva',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './reserva.component.html',
  styleUrl: './reserva.component.css'
})

export class ReservaComponent {
  // Formulario de búsqueda
  reservaForm!: FormGroup;

  // Datos mock de alojamientos
  alojamientosDisponibles: any[] = [];
  todosLosAlojamientos = [
    {
      id: 1,
      tipo: 'bungalow',
      nombre: 'Bungalow Sol',
      capacidad: 4
    },
    {
      id: 2,
      tipo: 'parcela',
      nombre: 'Parcela Sombra',
      capacidad: 6
    },
    {
      id: 3,
      tipo: 'casa-rural',
      nombre: 'Casa Rural Montaña',
      capacidad: 8
    },
    {
      id: 4,
      tipo: 'bungalow',
      nombre: 'Bungalow Mar',
      capacidad: 2
    }
  ];

  constructor(private fb: FormBuilder, private router: Router) {
    this.reservaForm = this.fb.group({
      fechaInicio: ['', Validators.required],
      fechaFin: ['', Validators.required],
      tipo: ['', Validators.required]
    });
  }
  
  // Método que se llama al enviar el formulario
  onSubmit(): void {
    if (this.reservaForm.valid) {
      const { fechaInicio, fechaFin, tipo } = this.reservaForm.value;
      // Simulacion de filtro por tipo
      this.alojamientosDisponibles = this.todosLosAlojamientos.filter(aloj => aloj.tipo === tipo);
      console.log('Resultados simulados:', this.alojamientosDisponibles);
    } else {
      console.log('Formulario no válido');
    }
  }

  irAConfirmacion(alojamiento: any) {
    this.router.navigate(['/confirmacion'], { state: { alojamiento } });
  }	
  
}
