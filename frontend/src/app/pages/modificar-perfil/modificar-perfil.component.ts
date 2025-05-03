import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AuthService } from 'app/services/auth.service';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { PerfilTemporalService } from 'app/services/perfil-temporal.service';

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
  datosOriginales: any = null; // Guardamos los datos originales del usuario

  constructor( 
    private auth: AuthService, 
    private fb: FormBuilder, 
    private router: Router, 
    private perfilTemporal: PerfilTemporalService
  ) { }

  // Comprobamos que las contraseñas son iguales
  passwordsIguales(form: FormGroup) {
    const pass1 = form.get('password1')?.value;
    const pass2 = form.get('password2')?.value;
    return pass1 === pass2 ? null : { passwordMismatch: true };
  }

  ngOnInit(): void {
    // Pedimos los datos del usuario al backend
    this.auth.getPerfil().subscribe({
      next: (res) => {
        this.usuario = res.data;

        // Guardamos os datos originales para compararlos
        this.datosOriginales = { ...res.data,
          birthdate: res.data.birthdate?.substring(0, 10) // Formateamos la fecha para la comparacion
        }
        

        // Creamos el formulario con los datos actuales
        this.modificarForm = this.fb.group({
          first_name: [this.usuario.first_name, Validators.required],
          last_name_1: [this.usuario.last_name_1, Validators.required],
          last_name_2: [this.usuario.last_name_2],
          birthdate: [this.usuario.birthdate?.substring(0, 10), Validators.required], 
          address: [this.usuario.address, Validators.required],
          phone: [this.usuario.phone, Validators.required],
          password1: ['', Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/)],
          password2: ['']
        }, { validators: this.passwordsIguales });
      },
      error: (err) => {
        console.error('Error al cargar el perfil:', err);
      }
    });
  }

  // Al hacer clic en Aceptar
  onSubmit() {
    if (this.modificarForm.valid) {
      const nuevosDatos = this.modificarForm.value;
  
      //Comparamos campo a campo con los datos originales
      const sinCambios = Object.keys(nuevosDatos).every(key =>
        nuevosDatos[key] === this.datosOriginales[key]
      );
      // No hay cambios se avisa al usuario y sale
      if (sinCambios) {
        alert('No se ha modificado ningún dato.');
        return;
      }

      // Comprobamos que la fecha de nacimiento no sea posterior igual o posterior a hoy
      const fechaNacimiento = new Date(nuevosDatos.birthdate);
      const hoy = new Date();
      hoy.setHours(0, 0, 0, 0); // Establecemos la hora a 00:00:00 para comparar solo la fecha

      if (fechaNacimiento >= hoy) {
        alert('La fecha de nacimiento no puede ser igual o posterior a hoy.');
        return;
      }

      // Verificamos si se ha introducido contraseña y si son iguales. Si es así, la añadimos a los datos a enviar (nuevosDatos)
      if (nuevosDatos.password1 && nuevosDatos.password1 === nuevosDatos.password2) {
        nuevosDatos.password = nuevosDatos.password1;
      }
      // Si no se ha introducido contraseña, la eliminamos de los datos a enviar
      delete nuevosDatos.password1;
      delete nuevosDatos.password2;

      console.log('Datos a enviar desde modificar-perfil:', nuevosDatos); //testing

      // Si hay cambios guardamos los datos en el servicio temporal y vamos a verificar 
      this.perfilTemporal.setDatos(nuevosDatos);
      this.router.navigate(['/verificar']);
    }
  }

  // Botón Cancelar
  cancelar() {
    this.router.navigate(['/perfil']);
  }

}
