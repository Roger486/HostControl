import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators, FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { ReservasService } from 'app/services/reservas.service';
import { ReservaUsuarioService } from 'app/services/reserva-usuario.service';
import { AuthService } from 'app/services/auth.service';

@Component({
  selector: 'app-reserva',
  standalone: true,
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './reserva.component.html',
  styleUrl: './reserva.component.css'
})

export class ReservaComponent {
  tipo: string = '';
  check_in_date: string = '';
  check_out_date: string = '';
  alojamientos: any[] = [];
  resultados: any[] = [];
  alojamientosDisponibles: any[] = [];
  busquedaRealizada: boolean = false;
  paginaActual: number = 1;
  ultimaPagina: number = 1;
  mostrarFormulario: boolean = false;
  alojamientoSeleccionado: any = null;
  reservaForm!: FormGroup;
  idAlojamientoSeleccionado: number | null = null;
  usuarioActual: any = null;

  constructor(
    private reservasService: ReservasService, 
    private reservaUsuarioService: ReservaUsuarioService,
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router
  ) {
    this.reservaForm = this.fb.group({
      accommodation_id: ['', Validators.required],
      check_in_date: ['', Validators.required],
      check_out_date: ['', Validators.required],
      comments: [''],
      companion_first_name: [''],
      companion_last_name: [''],
      companion_document_type: ['DNI'],
      companion_document_number: [''],
      companion_birthdate: ['']
    });
  }

  // Método para obtener el perfil del usuario al iniciar el componente
  // Se suscribe al servicio de autenticación para obtener el perfil del usuario
  // y lo almacena en la variable usuarioActual
  ngOnInit(): void {
    this.authService.getPerfil().subscribe({
      next: (res) => {
        this.usuarioActual = res.data;
        console.log('Usuario logueado:', this.usuarioActual);
      },
      error: (err) => {
        console.error('Error al obtener perfil:', err);
      }
    });
  }

  obtenerUsuarioId(): number {
    const usuario = JSON.parse(localStorage.getItem('usuarioLogueado') || '{}');
    return usuario.id;
  }

  buscarAlojamientos(): void {
    console.log('Valores recibidos:');
    console.log('Tipo:', this.tipo);
    console.log('Check-in:', this.check_in_date);
    console.log('Check-out:', this.check_out_date);

    

    if (!this.tipo || !this.check_in_date || !this.check_out_date) {
      alert('Debes completar todos los campos.');
      return;
    }

    this.reservasService.obtenerAlojamientosDisponibles({
      type: this.tipo,
      check_in_date: this.check_in_date,
      check_out_date: this.check_out_date,
      
    }, this.paginaActual).subscribe({
      next: (res) => {
        console.log('Alojamientos disponibles:', res);
        this.alojamientosDisponibles = res.data;
        this.paginaActual = res.meta.current_page;
        this.ultimaPagina = res.meta.last_page;
      },
      error: (err) => {
        console.error('Error al obtener alojamientos:', err);
        alert('Hubo un error al buscar alojamientos.');
      }
    });
  }

  cambiarPagina(nuevaPagina: number): void {
    if (nuevaPagina >= 1 && nuevaPagina <= this.ultimaPagina) {
      this.paginaActual = nuevaPagina;
      this.buscarAlojamientos(); 
    }
  }

  seleccionarAlojamiento(alojamiento: any): void {
    this.alojamientoSeleccionado = alojamiento;
    this.idAlojamientoSeleccionado = alojamiento.id;
    this.mostrarFormulario = true;
    this.reservaForm.patchValue({ accommodation_id: alojamiento.id });
  }

  cancelarReserva(): void {
    this.idAlojamientoSeleccionado = null;
    this.alojamientoSeleccionado = null;
    this.reservaForm.reset();
  }

  enviarReserva(): void {
    if (this.reservaForm.valid && this.usuarioActual) {
      const form = this.reservaForm.value;
  
      const reserva: any = {
        booked_by_id: this.usuarioActual.id,
        guest_id: this.usuarioActual.id,
        accommodation_id: form.accommodation_id,
        check_in_date: form.check_in_date,
        check_out_date: form.check_out_date,
        comments: form.comments || null,
        companions: [] 
      };

      // Verificamos si se ha introducido al menos el nombre para incluirlo
      if (form.companion_first_name?.trim()) {
        const companion = {
          first_name: form.companion_first_name,
          last_name_1: form.companion_last_name,
          document_type: form.companion_document_type,
          document_number: form.companion_document_number,
          birthdate: form.companion_birthdate
        };
        reserva.companions = [companion];
      }
  
      console.log('Reserva preparada para enviar:', reserva);
      // Llamamos al servicio para crear la reserva
      this.reservaUsuarioService.crearReserva(reserva).subscribe({
        next: () => {
          alert('Reserva realizada correctamente.');
          this.reservaForm.reset();
          this.alojamientoSeleccionado = null;
          this.router.navigate(['/reserva-confirmada']);
        },
        error: (err) => {
          console.error('Error al realizar la reserva:', err);
          if (err.status === 422) {
            alert('No se ha podido completar la reserva. Revisa los datos introducidos en el formulario.');
          } else {
            alert('Ocurrió un error al realizar la reserva.');
          }
        }
      });
    }

    }
  }




  
  

  
  

