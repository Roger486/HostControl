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
    this.router.navigate(['/admin/clientes']);
  }

  irAReservas() {
    this.router.navigate(['/admin/reservas']);
  }

  irAInmuebles() {
    this.router.navigate(['/admin/inmuebles']);
  }

  volverInicio() {
    this.router.navigate(['/']);
  }
}
