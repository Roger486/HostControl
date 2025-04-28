import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';


@Injectable({
  providedIn: 'root'
})
export class InmueblesService {

  private apiUrl = 'http://localhost:8000/api/accommodations'; 

  constructor(private http: HttpClient) {}

  // Obtenemos los inmuebles de la API
  getInmuebles(tipo?: string, page: number = 1): Observable<any> {
    let params = new HttpParams()
      .set('page', page); // Establecemos el número de página por defecto

    if (tipo) {
      params = params.set('type', tipo);
    }
    return this.http.get<any>(this.apiUrl, { params });
  }

  // Borramos inmuebles de la API
  deleteInmueble(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }

  // Actualizamos el precio de un inmueble
  actualizarPrecio(id: number, nuevoPrecio: number): Observable<any> {
    const token = localStorage.getItem('authToken');
  
    return this.http.put(`${this.apiUrl}/${id}`, { price_per_day: nuevoPrecio }, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  actualizarCapacidad(id: number, nuevaCapacidad: number): Observable<any> {
    const token = localStorage.getItem('authToken');
  
    return this.http.put(`${this.apiUrl}/${id}`, { capacity: nuevaCapacidad }, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }
}