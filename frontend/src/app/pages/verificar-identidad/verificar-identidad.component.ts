import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from 'app/services/auth.service';

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

  constructor(private fb: FormBuilder, private auth: AuthService, public router: Router
  ) {
    // 🧱 Creamos el formulario
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

          //Redirigimos a la pantalla de confirmación o acción final
          this.router.navigate(['/perfil/modificado']);
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
