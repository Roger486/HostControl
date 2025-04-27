import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { AuthService } from 'app/services/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-admin-reservas',
  imports: [CommonModule, ReactiveFormsModule],
  standalone: true,
  templateUrl: './admin-reservas.component.html',
  styleUrl: './admin-reservas.component.css'
})
export class AdminReservasComponent {
  busquedaForm: FormGroup;
  reservas: any[] = [];
  busquedaRealizada: boolean = false;
  reservaSeleccionada: any = null; // Variable para guardar la reserva a mostrar

  constructor(private fb: FormBuilder, private auth: AuthService, private router: Router) {
    this.busquedaForm = this.fb.group({
      email: [''],
      fecha_checkin: [''],
      fecha_checkout: ['']
    });
  }

  buscarReservas() {
    const filtros = this.busquedaForm.value;

    this.auth.getReservas().subscribe({
      next: (res) => {
        console.log('Reservas obtenidas:', res.data); // Para depurar las reservas obtenidas
        let todasReservas = res.data || [];

        // Filtro en cliente
        if (filtros.email) {
          todasReservas = todasReservas.filter((r: any) =>
            r.booked_by?.email?.toLowerCase() === filtros.email.toLowerCase()
          );
        }

        if (filtros.fecha_checkin) {
          todasReservas = todasReservas.filter((r: any) =>
            r.check_in_date?.substring(0, 10) === filtros.fecha_checkin
          );
        }
        
        if (filtros.fecha_checkout) {
          todasReservas = todasReservas.filter((r: any) =>
            r.check_out_date?.substring(0, 10) === filtros.fecha_checkout
          );
        }

        this.reservas = todasReservas;
        this.busquedaRealizada = true;
      },
      error: (err) => {
        console.error('Error al buscar reservas:', err);
      }
    });
  }

  verDetalles(reserva: any) {
    this.reservaSeleccionada = reserva;
  }

  cerrarDetalles() {
    this.reservaSeleccionada = null;
  }

  volverAdmin() {
    this.router.navigate(['/admin']);
  }


}
