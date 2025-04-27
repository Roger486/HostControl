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
  reservaSeleccionada: any = null; // guardar la reserva a mostrar
  reservaAModificar: any = null; // guardar la reserva en edición
  modificarForm!: FormGroup;

  constructor(private fb: FormBuilder, private auth: AuthService, private router: Router) {
    this.busquedaForm = this.fb.group({
      email: [''],
      fecha_checkin: [''],
      fecha_checkout: ['']
    });
    this.modificarForm = this.fb.group({
      nuevoCheckin: [''],
      nuevoCheckout: [''],
      comentarios: ['']
    });
    
  }
  // Método para inicializar el formulario de búsqueda
  buscarReservas() {
    const filtros = this.busquedaForm.value;

    // TODO get user id by email -> get reservas by guest_user id
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

  // Método para inicializar el formulario de modificación
  guardarCambiosReserva() {
    const cambios = this.modificarForm.value;
  
    // Validación: check-in debe ser anterior a check-out
    if (!cambios.nuevoCheckin || !cambios.nuevoCheckout) {
      alert('Debes introducir ambas fechas.');
      return;
    }
  
    if (cambios.nuevoCheckin >= cambios.nuevoCheckout) {
      alert('La fecha de entrada debe ser anterior a la fecha de salida y no pueden ser el mismo día.');
      return;
    }
  
    const datosActualizados = {
      check_in_date: cambios.nuevoCheckin,
      check_out_date: cambios.nuevoCheckout,
      log_detail: cambios.comentarios
    };
  
    if (confirm('¿Confirmas los cambios en la reserva?')) {
      this.auth.modificarReserva(this.reservaAModificar.id, datosActualizados).subscribe({
        next: () => {
          alert('Reserva modificada correctamente.');
          this.reservaAModificar = null;
          this.buscarReservas(); // Recargar reservas
        },
        error: (err) => {
          if (err.status === 422) {
            alert('Las fechas seleccionadas no están disponibles o hay un error en la modificación.');
          } else {
            console.error('Error al modificar reserva:', err);
            alert('Error inesperado al modificar la reserva.');
          }
        }
      });
    }
  }

  verDetalles(reserva: any) {
    this.reservaSeleccionada = reserva;
  }

  cerrarDetalles() {
    this.reservaSeleccionada = null;
  }

  modificarReserva(reserva: any) {
    this.reservaAModificar = reserva;
  }

  volverAdmin() {
    this.router.navigate(['/admin']);
  }

  cancelarModificacion() {
    this.reservaAModificar = null;
    this.modificarForm.reset();
  }

}
