import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ReservasService } from 'app/services/reservas.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-admin-reservas',
  imports: [CommonModule, FormsModule],
  standalone: true,
  templateUrl: './admin-reservas.component.html',
  styleUrl: './admin-reservas.component.css'
})
export class AdminReservasComponent {
  emailUsuario = '';
  usuario: any = null;
  reservas: any[] = [];
  cargando = false;
  error = '';
  idUsuarioSeleccionado!: number; 
  editarReserva: number | null = null;

  constructor(private reservasService: ReservasService) {}
  buscarUsuario(): void {
    this.error = '';
    this.usuario = null;
    this.reservas = [];
    this.idUsuarioSeleccionado = 0; 
    this.cargando = true;
  
    this.reservasService.buscarUsuarioPorEmail(this.emailUsuario).subscribe({
      next: (res) => {
        const usuarioData = res.data;
        this.idUsuarioSeleccionado = usuarioData.id;
  
        this.usuario = {
          id: usuarioData.id,
          nombre: `${usuarioData.first_name} ${usuarioData.last_name_1} ${usuarioData.last_name_2 || ''}`.trim(),
          documento: `${usuarioData.document_type} ${usuarioData.document_number}`
        };
  
        console.log('ID del usuario buscado:', this.idUsuarioSeleccionado);
  
        this.reservasService.getReservasDeUsuario(this.idUsuarioSeleccionado).subscribe({
          next: (r) => {
            this.reservas = r.data;
            console.log(`Reservas del usuario ${this.idUsuarioSeleccionado}:`, this.reservas);
            this.cargando = false;
          },
          error: () => {
            this.error = 'Error al cargar las reservas del usuario.';
            this.cargando = false;
          }
        });
      },
      error: () => {
        this.error = 'No se encontró ningún usuario con ese email.';
        this.cargando = false;
      }
    });
  }

  // Formateamos fechas
  formatearFecha(dateStr: string): string {
    const date = new Date(dateStr);
    return date.toISOString().split('T')[0]; 
  }

  ModificarReserva(reserva: any): void {
    if (!reserva.log_detail || reserva.log_detail.trim() === '') {
      alert('Por favor, indica un motivo en el campo Log.');
      return;
    }
  
    const checkIn = new Date(reserva.check_in_date);
    const checkOut = new Date(reserva.check_out_date);
  
    if (checkOut <= checkIn) {
      alert('La fecha de salida no puede ser igual o anterior a la de entrada.');
      return;
    }
  
    const confirmar = window.confirm('¿Quieres guardar los cambios en esta reserva?');
    if (!confirmar) return;
  
    const datos = {
      check_in_date: this.formatearFecha(reserva.check_in_date),
      check_out_date: this.formatearFecha(reserva.check_out_date),
      status: reserva.status,
      log_detail: reserva.log_detail,
      guest_id: this.idUsuarioSeleccionado
    };
    console.log('Datos enviados al PUT:', datos);
    this.reservasService.modificarReserva(reserva.id, datos).subscribe({
      next: () => {
        alert('Reserva actualizada correctamente.');
        this.editarReserva = null;
      },
      error: () => {
        alert('Error al actualizar la reserva.');
      }
    });
  }
}
  


