import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { AuthService } from 'app/services/auth.service';
import { ClientesService } from 'app/services/clientes.service';
import { RouterModule } from '@angular/router';


@Component({
  selector: 'app-admin-clientes',
  imports: [CommonModule, ReactiveFormsModule, RouterModule],
  standalone: true,
  templateUrl: './admin-clientes.component.html',
  styleUrl: './admin-clientes.component.css'
})
export class AdminClientesComponent {
  busquedaForm!: FormGroup;
  edicionForm!: FormGroup;
  cliente: any = null;
  busquedaRealizada: boolean = false;
  mostrarEdicion: boolean = false;
  hoy: string = new Date().toISOString().substring(0, 10);

  constructor(private fb: FormBuilder, private auth: AuthService, private clientesService: ClientesService) {
    this.busquedaForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      document_number: ['', [Validators.required]]
    });
    this.busquedaForm.valueChanges.subscribe(() => {
      this.busquedaRealizada = false;
    });
}

buscarCliente() {
  const email = this.busquedaForm.value.email?.trim();
  const document_number = this.busquedaForm.value.document_number?.trim();

  if (!email && !document_number) {
    alert('Introduce email y numero de documento del cliente para buscar.');
    return;
  }

  this.clientesService.buscarCliente({ email, document_number }).subscribe({
    next: (res) => {
      const cliente = res.data;
      

      if (cliente.email === email && cliente.document_number === document_number) {
        this.cliente = cliente;
        this.mostrarEdicion = true;
        this.edicionForm = this.fb.group({
          first_name: [cliente.first_name, Validators.required],
          last_name_1: [cliente.last_name_1, Validators.required],
          last_name_2: [cliente.last_name_2],
          birthdate: [cliente.birthdate?.substring(0, 10), Validators.required],
          address: [cliente.address, Validators.required],
          phone: [cliente.phone, Validators.required],
          email: [cliente.email, [Validators.required, Validators.email]],
          document_type: [cliente.document_type, Validators.required],
          document_number: [cliente.document_number, Validators.required],
          password1: [''],
          password2: ['']
        }, { validators: this.passwordsIguales });
      } else {
        this.cliente = null;
        this.busquedaRealizada = true;
      }

      
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
passwordsIguales(form: FormGroup) {
  const pass1 = form.get('password1')?.value;
  const pass2 = form.get('password2')?.value;
  return pass1 === pass2 ? null : { passwordMismatch: true };
}

// Llama cuando hace clic en "Guardar cambios"
confirmarCambios() {
  const cambios = this.edicionForm.value;
  // Controlamos que la fecha de nacimiento no sea posterior a hoy
  if (cambios.birthdate > this.hoy) {
    alert('La fecha de nacimiento no puede ser posterior a hoy.');
    return;
  }
  // Preparamos los datos a enviar
  const datosActualizados: any = {
    first_name: cambios.first_name,
    last_name_1: cambios.last_name_1,
    last_name_2: cambios.last_name_2,
    birthdate: cambios.birthdate,
    address: cambios.address,
    phone: cambios.phone,
    email: cambios.email,
    document_type: cambios.document_type,
    document_number: cambios.document_number
  };


  // Si se ha introducido una nueva contraseña válida, la añadimos
  if (cambios.password1 && cambios.password1 === cambios.password2) {
    datosActualizados.password = cambios.password1;
  }
  
  // Mostramos confirmación con los nuevos datos
  if (confirm('¿Confirmas que quieres guardar los siguientes cambios?\n\n' + JSON.stringify(cambios, null, 2))) {
    console.log('Datos a actualizar:', cambios);
    console.log('Datos enviados al backend:', datosActualizados);
    // Llamamos al servicio para actualizar el cliente
    this.clientesService.actualizarCliente(this.cliente.id, datosActualizados).subscribe({
      next: () => {
        alert('Cliente actualizado correctamente.');
      },
      error: (err) => {
        console.error('Error al actualizar el cliente:', err);
        if (err.status === 422 && err.error?.errors) {
          const errores = err.error.errors;
          const mensajes: string[] = [];
      
          for (const campo in errores) {
            if (errores.hasOwnProperty(campo)) {
              errores[campo].forEach((mensaje: string) => {
                mensajes.push(mensaje);
              });
            }
          }
          alert(`No se pudo actualizar al cliente:\n\n${mensajes.join('\n')}`);
        } else {
          alert('Error al actualizar al cliente.');
        }
      }
    });
  }
}

// Llama cuando hace clic en "Cancelar"
cancelarEdicion() {
  // Simplemente reseteamos el formulario con los datos originales
  if (confirm('¿Cancelar la edición de datos?')) {
    this.mostrarEdicion = false;
    this.edicionForm.patchValue({
      first_name: this.cliente.first_name,
      last_name_1: this.cliente.last_name_1,
      last_name_2: this.cliente.last_name_2,
      birthdate: this.cliente.birthdate,
      address: this.cliente.address,
      phone: this.cliente.phone,
      email: this.cliente.email,
      document_type: this.cliente.document_type,
      document_number: this.cliente.document_number
    });
  }
}

confirmarEliminacion() {
  if (confirm('¿Eliminar a este cliente? Esto tambien eliminará todas sus reservas! Esta acción no se puede deshacer!')) {
    this.clientesService.eliminarCliente(this.cliente.id).subscribe({
      next: () => {
        alert('Cliente eliminado correctamente.');
        // Limpiamos pantalla
        this.mostrarEdicion = false;
        this.cliente = null;
        this.busquedaRealizada = false;
      },
      error: (err) => {
        if (err.status === 404) {
          alert('El usuario no existe o ya fue eliminado.');
        } else if (err.status === 403) {
          alert('No tienes permisos para eliminar este usuario.');
        } else {
          alert('Ocurrió un error inesperado al eliminar el usuario.');
        }
      }
    });
  }
}
}

