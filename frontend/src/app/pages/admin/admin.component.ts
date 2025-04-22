import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';

@Component({
  selector: 'app-admin',
  imports: [],
  templateUrl: './admin.component.html',
  styleUrl: './admin.component.css'
})
export class AdminComponent {
  constructor(private router: Router) {}

  irAClientes() {
    // TODO ruta a la gestion de clientes
    this.router.navigate(['/admin/clientes']);
  }

  irAReservas() {
    // TODO ruta a gestion de reservas
    this.router.navigate(['/admin/reservas']);
  }

  irAInmuebles() {
    // TODO ruta a gestion de inmuebles
    this.router.navigate(['/admin/inmuebles']);
  }

  volverInicio() {
    this.router.navigate(['/']);
  }
}
