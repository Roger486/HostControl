import { Routes } from '@angular/router';
import { MainLayoutComponent } from './components/main-layout/main-layout.component';
import { HomeComponent } from './pages/home/home.component';
import { LoginComponent } from './pages/login/login.component';
import { RegisterComponent } from './pages/register/register.component';
import { ReservaComponent } from './pages/reserva/reserva.component';
import { ConfirmacionReservaComponent } from './pages/confirmacion-reserva/confirmacion-reserva.component';
import { ReservaConfirmadaComponent } from './pages/reserva-confirmada/reserva-confirmada.component';
import { RegistroConfirmadoComponent } from './pages/registro-confirmado/registro-confirmado.component';


export const routes: Routes = [
    {
        path: '',
        component: MainLayoutComponent,
        children: [
            // Aqui se añadiran paginas como Home, Login, etc..
            { path: '', component: HomeComponent }, // Ruta a home
            { path: 'login', component: LoginComponent }, // Ruta a login
            { path: 'register', component: RegisterComponent }, // Ruta a register
            { path: 'reserva', component: ReservaComponent }, // Ruta a reserva
            { path: 'confirmacion', component: ConfirmacionReservaComponent }, // Ruta a confirmacion-reserva
            { path: 'reserva-confirmada', component: ReservaConfirmadaComponent }, // Ruta a reserva-confirmada  
            {path: 'registro-confirmado', component: RegistroConfirmadoComponent} // Ruta a registro-confirmado
            
        ]
        },
        {
            path: '***',
            redirectTo: ''
        }

];
