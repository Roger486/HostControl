import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AuthService } from 'app/services/auth.service';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { ReservasService } from 'app/services/reservas.service';


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

  // Las reservas del user
  reservas: any[] = [];


  constructor(private auth: AuthService, private fb: FormBuilder, private router: Router, private reservasService: ReservasService) {}

  ngOnInit(): void {
      // Al iniciar el componente se pide al backend los datos del perfil de usuario
      this.auth.getPerfil().subscribe({
        next: (res) => {
          // Si la respuesta es correctaa guardamos los datos en la variable usuario
          this.usuario = res.data;
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

}
