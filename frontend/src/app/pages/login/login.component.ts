import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css'
})
export class LoginComponent {
  loginForm!: FormGroup;
  loginError = false;

  constructor(private fb: FormBuilder, private router: Router) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required]
    });
  }

  onSubmit() {
    const { email, password } = this.loginForm.value;

    const usuarios = JSON.parse(localStorage.getItem('usuarios') || '[]');

    const usuarioEncontrado = usuarios.find((u: any) =>
      u.email === email && u.password1 === password
    );

    if (usuarioEncontrado) {
      // Guardar estado de login
      localStorage.setItem('usuarioLogueado', JSON.stringify(usuarioEncontrado));
      this.loginError = false;
      this.router.navigate(['/']); // redirigir al home o panel
    } else {
      this.loginError = true;
    }
  }
}
