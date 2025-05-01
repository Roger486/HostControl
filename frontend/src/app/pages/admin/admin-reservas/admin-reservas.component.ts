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
}
  


