import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AuthService } from 'app/services/auth.service';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';


@Component({
  selector: 'app-perfil',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './perfil.component.html',
  styleUrl: './perfil.component.css'
})
export class PerfilComponent implements OnInit{
  // Guardamos los datos del usuario recibidos del backend
  usuario: any = null;
  // Guardamos el formulario de perfil
  perfilForm!: FormGroup;

  // Logica para pop up de confirmacion al modificar datos
  mostrarPopup = false;
  verificacionForm!: FormGroup;


  constructor(private auth: AuthService, private fb: FormBuilder, private router: Router) {}

  ngOnInit(): void {
      // Al iniciar el componente se pide al backend los datos del perfil de usuario
      this.auth.getPerfil().subscribe({
        next: (res) => {
          // Si la respuesta es correctaa guardamos los datos en la variable usuario
          this.usuario = res.data;

          /* Formulario para modificar perfil de usuario desde usuario
          this.perfilForm = this.fb.group({
            first_name: [this.usuario.first_name, Validators.required],
            last_name_1: [this.usuario.last_name_1, Validators.required],
            last_name_2: [this.usuario.last_name_2],
            birthdate: [this.usuario.birthdate, Validators.required],
            address: [this.usuario.address, Validators.required],
            phone: [this.usuario.phone, Validators.required]
          });
          */
        },
        error: (err) => {
          // Si hay error se muestra por consola
          console.error('Error al cargar el perfil de usuario:', err);

        }
      });   
  }

  volverInicio() {
    this.router.navigate(['/']);
  }

  irAEditarPerfil() {
    this.router.navigate(['/perfil/editar']);
  }

  /*
  // Pop up de confirmacion al modificar datos
  mostrarVerificacion(){
    this.verificacionForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required]
    });
    this.mostrarPopup = true;
  }
  // Si el usuario finalmente no quiere modificar
  cerrarPopup() {
    this.mostrarPopup = false;
    this.router.navigate(['/']);
  }

  confirmarCambios() {
    // TODO validacion email y contraseña
    console.log('Confirmar cambios');
  }
  */

}
