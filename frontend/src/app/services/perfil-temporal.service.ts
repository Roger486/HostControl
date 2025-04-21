import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class PerfilTemporalService {

  constructor() { }
  private datosModificados: any = null;

  // Guardamos temporalmente los datos
  setDatos(datos: any) {
    this.datosModificados = datos;
  }

  // Devolvemos los datos guardados
  getDatos(): any {
    return this.datosModificados;
  }

  // Limpiamos los datos guardados
  clearDatos() {
    this.datosModificados = null;
  }

  // Guardamos el estado de verificacion de identidad
  private identidadVerificada = false;

setIdentidadVerificada(valor: boolean) {
  this.identidadVerificada = valor;
}

getIdentidadVerificada(): boolean {
  return this.identidadVerificada;
}

clearVerificacion() {
  this.identidadVerificada = false;
}

}
