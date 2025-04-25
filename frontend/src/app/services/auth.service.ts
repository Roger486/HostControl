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
        console.log('Respuesta del login:', res); // Para depurar la respuesta del login
        // Guardamos el token recibido del usuario
        localStorage.setItem('authToken', res.token);

        //obtenido el token, hacemos una peticion GET al backend para obtener el perfil del usuario
      this.http.get(`${this.apiUrl}/user`, {
        headers: {
          Authorization: `Bearer ${res.token}`
        }
      }).subscribe((user: any) => {
        // Guardamos email y rol en localStorage
        localStorage.setItem('usuarioRol', JSON.stringify(user.data.role));
        localStorage.setItem('usuarioLogueado', JSON.stringify({ email: user.data.email }));

        // Notificamos al resto de la app
        this.usuarioLogueadoSubject.next({ email: user.data.email });
      });  
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
        localStorage.removeItem('usuarioRol'); // Limpiamos el rol del usuario
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

  //Enviamos los datos del usuario al backend para actualizar el perfil
  actualizarPerfil(datos: any): Observable<any> {
    const token = localStorage.getItem('authToken');
    return this.http.put(`${this.apiUrl}/user`, datos, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  // Desde admin obtenemos datos de un cliente
  buscarCliente(filtros: { email?: string, document_number?: string }): Observable<any> {
    const token = localStorage.getItem('authToken');
  
    return this.http.get(`${this.apiUrl}/users/search`, {
      headers: {
        Authorization: `Bearer ${token}`
      },
      params: filtros
    });
  }

  // Actualizar datos de un cliente desde admin
  actualizarCliente(id: number, datos: any): Observable<any> {
    const token = localStorage.getItem('authToken');
  
    return this.http.put(`${this.apiUrl}/users/${id}`, datos, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }
}
