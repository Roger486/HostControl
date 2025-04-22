import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { AuthService } from 'app/services/auth.service';


@Component({
  selector: 'app-admin-clientes',
  imports: [CommonModule, ReactiveFormsModule],
  standalone: true,
  templateUrl: './admin-clientes.component.html',
  styleUrl: './admin-clientes.component.css'
})
export class AdminClientesComponent {
  busquedaForm!: FormGroup;
  cliente: any = null;
  busquedaRealizada: boolean = false;

  constructor(private fb: FormBuilder, private auth: AuthService) {
    this.busquedaForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      document_number: ['', [Validators.required]]
  });
}

buscarCliente() {
  const email = this.busquedaForm.value.email?.trim();
  const document_number = this.busquedaForm.value.document_number?.trim();

  if (!email && !document_number) {
    alert('Introduce al menos un dato para buscar.');
    return;
  }

  this.auth.buscarCliente({ email, document_number }).subscribe({
    next: (res) => {
      const cliente = res.data;
      //this.cliente = res.data;
      //this.busquedaRealizada = true;

      if (
        cliente.email === email &&
        cliente.document_number === document_number
      ) {
        this.cliente = cliente;
      } else {
        this.cliente = null;
      }

      this.busquedaRealizada = true;
    },
    error: (err) => {
      if (err.status === 422) {
        this.cliente = null;
        this.busquedaRealizada = true;
      } else {
        console.error('Error inesperado:', err);
      }
    }
  });
}



}
