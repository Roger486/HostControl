import { CanActivateFn } from '@angular/router';
import { inject } from '@angular/core';
import { Router } from '@angular/router';


export const authGuard: CanActivateFn = (route, state) => {
  const router = inject(Router);
  const token = localStorage.getItem('authToken');
  const rol = localStorage.getItem('usuarioRol');



  // Comprobamos si el token existe y si el rol es admin
  // Si el token y el rol son válidos, permitimos el acceso a la ruta
  if (token && rol === '"admin"') { 
  return true;
  }

  router.navigate(['/']);
  return false;
};
