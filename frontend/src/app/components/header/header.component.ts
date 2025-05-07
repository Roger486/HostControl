import { Component} from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Router } from '@angular/router';
import { AuthService } from 'app/services/auth.service';

@Component({
  selector: 'app-header',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css'],
})
export class HeaderComponent {
  usuarioLogueado: any = null;
  rolUsuario: string = '';

  constructor(private auth: AuthService, private router: Router) {
    
    // Nos suscribimos a los cambios del usuario logueado
    this.auth.usuarioLogueado$.subscribe((usuario) => {
      
      console.log('Usuario logueado recibido en header:', usuario); // Para depurar el usuario logueado
      this.usuarioLogueado = usuario;

      if (usuario) {
        this.rolUsuario = JSON.parse(localStorage.getItem('usuarioRol') || '""');
      }
    });
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
  

