import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Router } from '@angular/router';

@Component({
  selector: 'app-header',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './header.component.html',
  styleUrl: './header.component.css'
})
export class HeaderComponent {
  usuarioLogueado: any = null;

  constructor(private router: Router) {
    this.actualizarEstado();
  }
  actualizarEstado() {
    const user = localStorage.getItem('usuarioLogueado');
    this.usuarioLogueado = user ? JSON.parse(user) : null;
  }
  logout() {
    localStorage.removeItem('usuarioLogueado');
    this.usuarioLogueado = null;
    this.router.navigate(['/']);
  }
  
}
