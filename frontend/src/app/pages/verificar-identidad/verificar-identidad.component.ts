import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from 'app/services/auth.service';
import { PerfilTemporalService } from 'app/services/perfil-temporal.service';

@Component({
  selector: 'app-verificar-identidad',
  imports: [CommonModule, ReactiveFormsModule],
  standalone: true,
  templateUrl: './verificar-identidad.component.html',
  styleUrl: './verificar-identidad.component.css'
})
export class VerificarIdentidadComponent {
  verificacionForm!: FormGroup;
  errorVerificacion: boolean = false;
  errorActualizacion: boolean = false;

  constructor(
    private fb: FormBuilder, 
    private auth: AuthService, 
    private router: Router,
    private perfilTemporal: PerfilTemporalService
  ) {
    // Creamos el formulario
    this.verificacionForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required]
    });
  }

  onSubmit() {
    if (this.verificacionForm.valid) {
      const { email, password } = this.verificacionForm.value;

      //Llamamos al login del backend para verificar
      this.auth.login({ email, password }).subscribe({
        next: () => {
          this.errorVerificacion = false;

          //Obtenemos los datos pendientes de modificar del servicio temporal
          const datosActualizados = this.perfilTemporal.getDatos();

          //Llamamos al backend para modificar el perfil
          this.auth.actualizarPerfil(datosActualizados).subscribe({
            next: () => {
              this.perfilTemporal.setIdentidadVerificada(true); // Guardamos el estado de verificacion
              this.perfilTemporal.clearDatos(); // Limpiamos los datos temporales
              this.router.navigate(['/perfil/modificado']); // Redirigimos a la pantalla confirmacion de modificacion
            },
            error: () => {
              console.error('Error al actualizar el perfil');
              this.errorActualizacion = true; // Mostramos mensaje de error
            }
          })
        },
        error: () => {
          //Si hay error, mostramos mensaje
          this.errorVerificacion = true;
        }
      });
    }
  }

  cancelar() {
    // Si el usuario cancela, redirigimos a la pantalla de perfil
    this.router.navigate(['/perfil']);
  }
}
