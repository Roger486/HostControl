import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root',
})
export class ReservaUsuarioService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  // Metodo para que usuario pueda reservar
  crearReserva(reserva: any): Observable<any> {
    const token = localStorage.getItem('authToken');
    return this.http.post(`${this.apiUrl}/reservations`, reserva, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
  }
}
