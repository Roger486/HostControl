import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ReservasService {
  private apiUrl = 'http://localhost:8000/api';

  constructor(private http: HttpClient) {}

  // Buscar cliente por email
  buscarUsuarioPorEmail(email: string): Observable<any> {
    const token = localStorage.getItem('authToken');
    const params = new HttpParams().set('email', email);

    return this.http.get(`${this.apiUrl}/users/search`, {
      headers: { Authorization: `Bearer ${token}` },
      params
    });
  }

  // Obtener reservas del usuario por id
  getReservasDeUsuario(userId: number): Observable<any> {
    const token = localStorage.getItem('authToken');

    return this.http.get(`${this.apiUrl}/reservations/guest/${userId}`, {
      headers: { Authorization: `Bearer ${token}` }
    });
  }
 // Llamar a la API para modificar una reserva
  modificarReserva(id: number, datos: any): Observable<any> {
    const token = localStorage.getItem('authToken');
  
    return this.http.put(`${this.apiUrl}/reservations/${id}`, datos, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  // Llamamos a la API para mostrar todas las reservas
  obtenerAlojamientosDisponibles(
    filtros: { type: string; check_in_date: string; check_out_date: string },
    pagina: number = 1
  ): Observable<any> {
    return this.http.get(`${this.apiUrl}/accommodations`, {
      params: {
        ...filtros,
        page: pagina
      }
    });
  }

  // Llamar a la API para crear una reserva
  crearReserva(datos: any): Observable<any> {
    const token = localStorage.getItem('authToken');
    return this.http.post(`${this.apiUrl}/reservations`, datos, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

}


