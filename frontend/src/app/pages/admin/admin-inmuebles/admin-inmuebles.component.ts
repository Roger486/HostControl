import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { InmueblesService } from 'app/services/inmuebles.service';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-admin-inmuebles',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './admin-inmuebles.component.html',
  styleUrl: './admin-inmuebles.component.css'
})
export class AdminInmueblesComponent implements OnInit {
  inmuebles: any[] = [];
  cargando = false;
  error: string | null = null;
  tipoSeleccionado: string = '';
  currentPage: number = 1;
  lastPage: number = 1;
  nuevoPrecio: number | null = null;
  nuevaCapacidad: number | null = null;
  idInmuebleActualizar: number | null = null;
  mostrarFormularioCrear = false;


  // Variable para almacenar el tipo de inmueble seleccionado
  nuevoInmueble: any = {
    accommodation_code: '',
    section: '',
    capacity: null,
    price_per_day: null,
    is_available: true,
    comments: '',
    type: '',
    // Campos específicos opcionales
    bed_amount: null,
    has_air_conditioning: false,
    has_kitchen: false,
    room_amount: null,
    has_private_wc: false,
    area_size_m2: null,
    has_electricity: false,
    accepts_caravan: false
  };

  constructor(private inmueblesService: InmueblesService) { }

  ngOnInit(): void {
    this.obtenerInmuebles();
  }

  // Método para obtener los inmuebles desde el servicio
  obtenerInmuebles(): void {
    this.cargando = true;
    this.error = null;
    this.inmueblesService.getInmuebles(this.tipoSeleccionado, this.currentPage).subscribe({
      next: (response) => {
        this.inmuebles = response.data;
        this.lastPage = response.meta.last_page; // Guardamos el número de última página
        this.cargando = false;
      },
      error: (err) => {
        console.error(err);
        this.error = 'Error al cargar los inmuebles.';
        this.cargando = false;
      }
    });
  }

  // Método para filtrar inmuebles por tipo

  buscarPorTipo(): void {
    this.currentPage = 1; // Reiniciamos la página a 1 al buscar por tipo
    this.obtenerInmuebles();
  }

  // Método para cambiar de página

  siguientePagina(): void {
    if (this.currentPage < this.lastPage) {
      this.currentPage++;
      this.obtenerInmuebles();
    }
  }

  anteriorPagina(): void {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.obtenerInmuebles();
    }
  }

  // Método para eliminar un inmueble
  eliminarInmueble(id: number): void {
    if (confirm('¿Seguro que quieres eliminar este inmueble? Esta acción supondrá la eliminacion de las reservas asociadas a este inmueble. Esta acción no se puede deshacer!')) {
      this.inmueblesService.deleteInmueble(id).subscribe({
        next: () => {
          this.inmuebles = this.inmuebles.filter(i => i.id !== id);
        },
        error: (err) => {
          console.error('Error al eliminar el inmueble', err);
          alert('Error al eliminar el inmueble.');
        }
      });
    } else {
      alert('El inmueble no ha sido eliminado.');
    }
  }

  // Método para actualizar precio x dia del inmueble
  actualizarPrecioInmueble(): void {
    if (this.idInmuebleActualizar !== null && this.nuevoPrecio !== null) {
      const id = Number(this.idInmuebleActualizar);
      const PrecioEuros = parseFloat(String(this.nuevoPrecio).replace(',', '.'));
  
      if (isNaN(id) || isNaN(PrecioEuros)) {
        alert('Por favor introduce valores numéricos válidos.');
        return;
      }

      const PrecioCentimos = Math.round(PrecioEuros * 100);
  
      this.inmueblesService.actualizarPrecio(id, PrecioCentimos).subscribe({
        next: () => {
          alert('Precio actualizado correctamente.');
          this.obtenerInmuebles(); // Recargar la lista
          this.nuevoPrecio = null;
          this.idInmuebleActualizar = null;
        },
        error: (err) => {
          console.error('Error al actualizar precio', err);
          alert('Error al actualizar precio.');
        }
      });
    } else {
      alert('Por favor completa el campo id y precio.');
    }
  }
  // Método para actualizar la capacidad de un inmueble
  actualizarCapacidadInmueble(): void {
    if (this.idInmuebleActualizar !== null && this.nuevaCapacidad !== null) {
      const id = Number(this.idInmuebleActualizar);
      const nuevaCapacidad = Number(this.nuevaCapacidad);
  
      if (isNaN(id) || isNaN(nuevaCapacidad)) {
        alert('Por favor introduce valores numéricos válidos.');
        return;
      }
  
      this.inmueblesService.actualizarCapacidad(id, nuevaCapacidad).subscribe({
        next: () => {
          alert('Capacidad actualizada correctamente.');
          this.obtenerInmuebles(); // Recargar lista
          this.nuevaCapacidad = null;
        },
        error: (err) => {
          console.error('Error al actualizar capacidad', err);
          alert('Error al actualizar capacidad.');
        }
      });
    } else {
      alert('Por favor completa ID y nueva capacidad.');
    }
  }

  // Método para crear un nuevo inmueble
  crearInmueble(): void {
    if (!this.nuevoInmueble.type) {
      alert('Por favor selecciona un tipo de alojamiento.');
      return;
    }
  
    const datos = {
      accommodation_code: this.nuevoInmueble.accommodation_code,
      section: this.nuevoInmueble.section,
      capacity: this.nuevoInmueble.capacity,
      price_per_day: this.nuevoInmueble.price_per_day,
      is_available: this.nuevoInmueble.is_available,
      comments: this.nuevoInmueble.comments,
      type: this.nuevoInmueble.type,
      // Campos segun tipo de inmueble
      ...(this.nuevoInmueble.type === 'bungalow' && {
        bed_amount: this.nuevoInmueble.bed_amount,
        has_air_conditioning: this.nuevoInmueble.has_air_conditioning,
        has_kitchen: this.nuevoInmueble.has_kitchen
      }),
      ...(this.nuevoInmueble.type === 'house' && {
        bed_amount: this.nuevoInmueble.bed_amount,
        room_amount: this.nuevoInmueble.room_amount,
        has_air_conditioning: this.nuevoInmueble.has_air_conditioning
      }),
      ...(this.nuevoInmueble.type === 'room' && {
        bed_amount: this.nuevoInmueble.bed_amount,
        has_air_conditioning: this.nuevoInmueble.has_air_conditioning,
        has_private_wc: this.nuevoInmueble.has_private_wc
      }),
      ...(this.nuevoInmueble.type === 'camping_spot' && {
        area_size_m2: this.nuevoInmueble.area_size_m2,
        has_electricity: this.nuevoInmueble.has_electricity,
        accepts_caravan: this.nuevoInmueble.accepts_caravan
      })
    };
  
    this.inmueblesService.crearInmueble(datos).subscribe({
      next: () => {
        alert('Inmueble creado correctamente.');
        this.mostrarFormularioCrear = false;
        this.obtenerInmuebles(); // recargar lista
      },
      error: (err) => {
        console.error('Error al crear inmueble', err);
        alert('Error al crear inmueble.');
      }
    });
  }
}
