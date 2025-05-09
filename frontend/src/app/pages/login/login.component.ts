import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterModule],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  loginForm!: FormGroup;
  loginError = false;

  constructor(private fb: FormBuilder, private router: Router, private auth: AuthService) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required]
    });
  }

  onSubmit() {
    if (this.loginForm.valid) {
      const { email, password } = this.loginForm.value;
      // Conectamos con el backend usando el servicio AuthService
      // Disparamos una petición POST a http://localhost:8000/api/login con los datos
      this.auth.login({ email, password }).subscribe({
        // Si el login es correcto
        next: (res) => {
          // Guardamos el token que devuelve el backend en el localStorage
          localStorage.setItem('authToken', res.token);
          // Guardamos el mail del usuario logueado
          localStorage.setItem('usuarioLogueado', JSON.stringify({ email }));
          // Redirigimos al usuario 
          this.loginError = false;
          this.router.navigate(['/']);
        },
        // Si el login falla activa el mensaje en la vista
        error: (err) => {
          console.error('Error al hacer login:', err);
          this.loginError = true;
        }
      });
    }
  }
}

