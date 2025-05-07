import { Routes } from '@angular/router';
import { MainLayoutComponent } from './components/main-layout/main-layout.component';
import { HomeComponent } from './pages/home/home.component';
import { LoginComponent } from './pages/login/login.component';
import { RegisterComponent } from './pages/register/register.component';
import { ReservaComponent } from './pages/reserva/reserva.component';
import { ConfirmacionReservaComponent } from './pages/confirmacion-reserva/confirmacion-reserva.component';
import { ReservaConfirmadaComponent } from './pages/reserva-confirmada/reserva-confirmada.component';
import { RegistroConfirmadoComponent } from './pages/registro-confirmado/registro-confirmado.component';
import { PerfilComponent } from './pages/perfil/perfil.component';
import { ModificarPerfilComponent } from './pages/modificar-perfil/modificar-perfil.component';
import { VerificarIdentidadComponent } from './pages/verificar-identidad/verificar-identidad.component';
import { PerfilModificadoComponent } from './pages/perfil-modificado/perfil-modificado.component';
import { AdminComponent } from './pages/admin/admin.component';
import { AdminClientesComponent } from './pages/admin/admin-clientes/admin-clientes.component';
import { AdminReservasComponent } from './pages/admin/admin-reservas/admin-reservas.component';
import { AdminInmueblesComponent } from './pages/admin/admin-inmuebles/admin-inmuebles.component';
import { PoliticaCookiesComponent } from './pages/politica-cookies/politica-cookies.component';
import { SobreNosotrosComponent } from './pages/sobre-nosotros/sobre-nosotros.component';
export const routes: Routes = [
    {
        path: '',
        component: MainLayoutComponent,
        children: [
            { path: '', component: HomeComponent }, // Ruta a home
            { path: 'login', component: LoginComponent }, // Ruta a login
            { path: 'register', component: RegisterComponent }, // Ruta a register
            { path: 'reserva', component: ReservaComponent }, // Ruta a reserva
            { path: 'confirmacion', component: ConfirmacionReservaComponent }, // Ruta a confirmacion-reserva
            { path: 'reserva-confirmada', component: ReservaConfirmadaComponent }, // Ruta a reserva-confirmada  
            { path: 'registro-confirmado', component: RegistroConfirmadoComponent}, // Ruta a registro-confirmado
            { path: 'perfil', component: PerfilComponent }, // Ruta a perfil de cliente
            { path: 'perfil/editar', component: ModificarPerfilComponent }, // Ruta a modificar perfil de cliente
            { path: 'verificar', component: VerificarIdentidadComponent }, // Ruta a verificar identidad
            { path: 'perfil/modificado', component: PerfilModificadoComponent }, // Ruta a perfil modificado
            { path: 'admin', component: AdminComponent }, // Ruta a panel administrador
            { path: 'admin/clientes', component: AdminClientesComponent }, // Ruta a gestion de clientes
            { path: 'admin/reservas', component: AdminReservasComponent }, // Ruta a gestion de reservas
            { path: 'admin/inmuebles', component: AdminInmueblesComponent }, // Ruta a gestion de inmuebles
            { path: 'politica-cookies', component: PoliticaCookiesComponent }, // Ruta a politica de cookies
            { path: 'sobre-nosotros', component: SobreNosotrosComponent } // Ruta a sobre nosotros
        ]
        },
        {
            path: '**',
            redirectTo: ''
        }

];
