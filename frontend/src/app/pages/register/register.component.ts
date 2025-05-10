import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';


@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterModule],
  templateUrl: './register.component.html',
  styleUrl: './register.component.css'
})
export class RegisterComponent {
  registerForm!: FormGroup;

  constructor(private fb: FormBuilder, private auth: AuthService, private router: Router) {
    this.registerForm = this.fb.group({
      name: ['', Validators.required],
      papellido: ['', Validators.required],
      sapellido: [''],
      fecha_nacimiento: ['', Validators.required],
      documento: ['dni', Validators.required],
      num_documento: ['', Validators.required],
      telefono: ['', Validators.required],
      direccion: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      password1: ['', [Validators.required, Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/)]],
      password2: ['', [Validators.required]]
    },{validators: this.passwordsIguales })
  }

  passwordsIguales(form: FormGroup){
    const pass1 = form.get('password1')?.value;
    const pass2 = form.get('password2')?.value;

    return pass1 === pass2 ? null : {passwordMismatch: true};
  }

  onSubmit() {
    if (this.registerForm.valid) {
      const form = this.registerForm.value;
      const fechaNacimiento = new Date(form.fecha_nacimiento);
      const hoy = new Date();
      hoy.setHours(0, 0, 0, 0); // seteamos la hora para comparar solo fecha

      // controlamos que fecha nacimiento no sea igual o posterior a hoy
      if (fechaNacimiento >= hoy) {
        alert('La fecha de nacimiento no puede ser igual o posterior a hoy.');
        return;
      }
  
      const datos = {
        first_name: form.name,
        last_name_1: form.papellido,
        last_name_2: form.sapellido || '',
        email: form.email,
        password: form.password1,
        birthdate: form.fecha_nacimiento,
        address: form.direccion,
        document_type: form.documento.toUpperCase(), // DNI, NIE, PASSPORT
        document_number: form.num_documento,
        phone: form.telefono,
        comments: null // Comentario opcional
      };
  
      this.auth.registrarUsuario(datos).subscribe({
        next: (res) => {
          console.log('Usuario registrado correctamente:', res);
          this.router.navigate(['/registro-confirmado']);
        },
        error: (err) => {
          console.error('Error al registrar usuario:', err);

          if (err.status === 422) {
            alert('No se pudo completar el registro. Revisa que todos los datos sean correctos.');
          } else {
            alert('Ha ocurrido un error inesperado. Intenta de nuevo más tarde.');
          }
        }
      });
    }
  }
}
