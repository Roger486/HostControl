import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AuthService } from 'app/services/auth.service';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  selector: 'app-modificar-perfil',
  imports: [CommonModule, ReactiveFormsModule],
  standalone: true,
  templateUrl: './modificar-perfil.component.html',
  styleUrl: './modificar-perfil.component.css'
})
export class ModificarPerfilComponent {
  modificarForm!: FormGroup;
  usuario: any = null;

  constructor( private auth: AuthService, private fb: FormBuilder, private router: Router) { }

  ngOnInit(): void {
    // Pedimos los datos del usuario al backend
    this.auth.getPerfil().subscribe({
      next: (res) => {
        this.usuario = res.data;

        // Creamos el formulario con los datos actuales
        this.modificarForm = this.fb.group({
          first_name: [this.usuario.first_name, Validators.required],
          last_name_1: [this.usuario.last_name_1, Validators.required],
          last_name_2: [this.usuario.last_name_2],
          birthdate: [this.usuario.birthdate, Validators.required],
          address: [this.usuario.address, Validators.required],
          phone: [this.usuario.phone, Validators.required]
        });
      },
      error: (err) => {
        console.error('Error al cargar el perfil:', err);
      }
    });
  }

  // Al hacer clic en Aceptar
  onSubmit() {
    if (this.modificarForm.valid) {
      const datosPendientes = this.modificarForm.value;

      // Redirigimos a la verificación con los datos a actualizar
      this.router.navigate(['/verificar'], {
        state: { nuevosDatos: datosPendientes }
      });
    }
  }

  // Botón Cancelar
  cancelar() {
    this.router.navigate(['/perfil']);
  }

}
