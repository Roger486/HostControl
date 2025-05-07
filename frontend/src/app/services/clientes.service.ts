import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class ClientesService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  // Buscar cliente por email y/o documento
  buscarCliente(filtros: {
    email?: string;
    document_number?: string;
  }): Observable<any> {
    const token = localStorage.getItem('authToken');

    const params = new HttpParams({ fromObject: filtros });

    return this.http.get(`${this.apiUrl}/users/search`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
      params,
    });
  }

  // Actualizar cliente
  actualizarCliente(id: number, datos: any): Observable<any> {
    const token = localStorage.getItem('authToken');

    return this.http.put(`${this.apiUrl}/users/${id}`, datos, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
  }

  // Eliminar cliente
  eliminarCliente(id: number): Observable<any> {
    const token = localStorage.getItem('authToken');

    return this.http.delete(`${this.apiUrl}/users/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
  }
}
