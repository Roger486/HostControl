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

  constructor(private inmueblesService: InmueblesService) { }

  ngOnInit(): void {
    this.obtenerInmuebles();
  }

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

  buscarPorTipo(): void {
    this.currentPage = 1; // Reiniciamos la página a 1 al buscar por tipo
    this.obtenerInmuebles();
  }

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

  eliminarInmueble(id: number): void {
    if (confirm('¿Seguro que quieres eliminar este inmueble?')) {
      this.inmueblesService.deleteInmueble(id).subscribe({
        next: () => {
          this.inmuebles = this.inmuebles.filter(i => i.id !== id);
        },
        error: (err) => {
          console.error('Error al eliminar el inmueble', err);
        }
      });
    }
  }

  actualizarPrecioInmueble(): void {
    if (this.idInmuebleActualizar !== null && this.nuevoPrecio !== null) {
      const id = Number(this.idInmuebleActualizar);
      const nuevoPrecio = Number(this.nuevoPrecio);
  
      if (isNaN(id) || isNaN(nuevoPrecio)) {
        alert('Por favor introduce valores numéricos válidos.');
        return;
      }
  
      this.inmueblesService.actualizarPrecio(id, nuevoPrecio).subscribe({
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
      alert('Por favor completa ambos campos.');
    }
  }

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
}
