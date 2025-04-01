import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Router } from '@angular/router';
import { AuthService } from 'app/services/auth.service';

@Component({
  selector: 'app-header',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent {
  usuarioLogueado: any = null;

  constructor(private auth: AuthService, private router: Router) {
    // Obtenemos el usuario logueado del localStorage al cargar el componente
    const stored = localStorage.getItem('usuarioLogueado');
    // Al cargar el componente comprobamos si hay usuario logueado
    // Esto nos permite mostrar "cerrar sesion" y ocultar "login" en el header
    this.usuarioLogueado = stored ? JSON.parse(stored) : null;
  }
  
  logout() {
    // Llamamos a backend para cerrar la sesion
    this.auth.logout().subscribe({
      next: () => {
        // Limpiar datos sesión local
        localStorage.removeItem('authToken');
        localStorage.removeItem('usuarioLogueado');

        // Redirigimos en este caso a login
        this.router.navigate(['/login']);
      },
      error: (err) => {
        // Mostramos error por consola si lo hay
        console.error('Error al cerrar sesión:', err);
      }
    });
  }
}
  

