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
}
