import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './register.component.html',
  styleUrl: './register.component.css'
})
export class RegisterComponent {
  registerForm!: FormGroup;

  constructor(private fb: FormBuilder, private router: Router) {
    this.registerForm = this.fb.group({
      name: ['', Validators.required],
      papellido: ['', Validators.required],
      sapellido: ['',],
      fecha_nacimiento: ['', Validators.required],
      documento: ['dni', Validators.required],
      num_documento: ['', Validators.required],
      telefono: ['', Validators.required],
      direccion: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      password1: ['', [Validators.required, Validators.minLength(6)]],
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
      console.log('Datos registrados:', this.registerForm.value);
      // Aqui se añadiria la llamada a la API para registrar al usuario
      const usuarios = JSON.parse(localStorage.getItem('usuarios') || '[]');
      usuarios.push(this.registerForm.value);
      localStorage.setItem('usuarios', JSON.stringify(usuarios));
      console.log('Usuario registrado:', this.registerForm.value);
      this.router.navigate(['/registro-confirmado']);
    } else {
      console.log('Formulario inválido');
    }
  }
}
