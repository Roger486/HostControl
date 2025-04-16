import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, BehaviorSubject, tap } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = environment.apiUrl;

  // BehaviorSubject almacena el estado del usuario
  private usuarioLogueadoSubject = new BehaviorSubject<any>(this.getUsuarioActual());

  // Observable que pueden escuchar otros componentes
  usuarioLogueado$ = this.usuarioLogueadoSubject.asObservable();

  constructor(private http: HttpClient) { }
  
  //obtener usuario desde Localstorage al iniciar
  private getUsuarioActual() {
    const data = localStorage.getItem('usuarioLogueado');
    return data ? JSON.parse(data) : null;
  }
  
  registrarUsuario(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/users`, data);
  }


// Iniciar sesion del usuario
  login(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/login`, data).pipe(
      tap((res: any) => {
        // Guardamos el token recibido y el usuario
        localStorage.setItem('authToken', res.token);
        localStorage.setItem('usuarioLogueado', JSON.stringify({ email: data.email }));
  
        // Notificamos al resto de la app que hay un usuario logueado
        this.usuarioLogueadoSubject.next({ email: data.email });
      })
    );

  }

  // Cerrar sesion del usuario
  logout(): Observable<any> {
    // Obtenemos el token del usuario almacenado en el localStorage
    const token = localStorage.getItem('authToken');
    // Realizamos peticion POST a backend para cerrar sesion
    // Enviamos el token en la cabecera 'Authorization' como pide Laravel Sanctum
    return this.http.post(`${this.apiUrl}/logout`, {}, {
      headers: {
        Authorization: `Bearer ${token}` // Token para identificar sesion activa
      }
    }).pipe(
      tap(() => {
        // Limpiamos el localstorage al finalizar sesion
        localStorage.removeItem('authToken');
        localStorage.removeItem('usuarioLogueado');
        // Emitimoos null para avisar que no hay usuario 
        this.usuarioLogueadoSubject.next(null);
      })
    );
  }

  // Obtenemos el perfil del usuario logueado
  getPerfil(): Observable<any> {
    // Obtenemos el token del usuario almacenado en el localStorage
    const token = localStorage.getItem('authToken');
    // Realizamos peticion GET al bakend (endpoint /api/user) para obtener el perfil del usuario
    // Enviamos el token en la cabecera 'Authorization' para autentificacion
    return this.http.get(`${this.apiUrl}/user`, {
      headers: {
        Authorization: `Bearer ${token}` // Token para identificar sesion activa
      }
    });
  }
}
