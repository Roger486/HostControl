import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { AuthService } from 'app/services/auth.service';
import { PerfilTemporalService } from 'app/services/perfil-temporal.service';

@Component({
  selector: 'app-perfil-modificado',
  imports: [CommonModule],
  standalone: true,
  templateUrl: './perfil-modificado.component.html',
  styleUrl: './perfil-modificado.component.css'
})
export class PerfilModificadoComponent implements OnInit{

  mensaje: string = '';
  error: boolean = false;

  constructor(
    private perfilTemporal: PerfilTemporalService,
    private auth: AuthService,
    private router: Router
  ) {}

  ngOnInit(): void {
    //Si no se verifica la identidad se redirige a perfil
    if (!this.perfilTemporal.getIdentidadVerificada()) {
      this.router.navigate(['/']);
      return;
    }

    const datosActualizados = this.perfilTemporal.getDatos();


    // Enviamos los datos al backend
    this.auth.actualizarPerfil(datosActualizados).subscribe({
      next: () => {
        this.perfilTemporal.clearDatos();
        this.perfilTemporal.clearVerificacion();
        this.mensaje = 'Tus datos han sido modificados correctamente.';
        this.error = false;
      },
      error: (err) => {
        console.error('Error al actualizar los datos:', err);
        // Manejo de errores según el código de estado
        if (err.status === 0) {
          this.mensaje = 'No se pudo conectar con el servidor. Verifica tu conexión.';
        } else if (err.status >= 500) {
          this.mensaje = 'Error en el servidor. Intenta más tarde.';
        } else if (err.status === 422) {
          this.mensaje = 'Los datos enviados no son válidos. Revisa el formulario.';
        } else {
          this.mensaje = 'Ocurrió un error al guardar los cambios.';
        }
  
        this.error = true;
      }
    });
  }

  volverInicio() {
    this.router.navigate(['/']);
  }

  cerrarSesion() {
    this.auth.logout().subscribe(() => {
      localStorage.removeItem('authToken');
      localStorage.removeItem('usuarioLogueado');
      this.router.navigate(['/login']);
    });
  }


}
