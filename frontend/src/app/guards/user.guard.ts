import { CanActivateFn } from '@angular/router';
import { inject } from '@angular/core';
import { Router } from '@angular/router';

export const userGuard: CanActivateFn = (route, state) => {
  const router = inject(Router);
  const token = localStorage.getItem('authToken');
  const rol = localStorage.getItem('usuarioRol');
  


  if (token && rol === '"user"') {
    return true;
  } 
  router.navigate(['/']);

  return false;

};
