import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) { }
    
  registrarUsuario(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/users`, data);
  }

  login(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/login`, data);

  }

  logout(): Observable<any> {
    // Obtenemos el token del usuario almacenado en el localStorage
    const token = localStorage.getItem('authToken');
    // Realizamos peticion POST a backend para cerrar sesion
    // Enviamos el token en la cabecera 'Authorization' como pide Laravel Sanctum
    return this.http.post(`${this.apiUrl}/logout`, {}, {
      headers: {
        Authorization: `Bearer ${token}` // Token para identificar sesion activa
      }
    });
  }
}
