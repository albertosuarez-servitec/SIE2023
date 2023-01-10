// Usuario no registrado
import Inicio from "./Principal/UsuarioFinal/Inicio.svelte";
import Acerca from "./Principal/UsuarioFinal/Acerca.svelte";
// Administrador
import Test from "./Principal/Administrador/test.svelte";
import ControlMenu from "./Principal/Administrador/ControlMenu.svelte";
import ControlUsuarios from "./Principal/Administrador/ControlUsuarios.svelte";
import PerfilesUsuario from "./Principal/Administrador/PerfilesUsuario.svelte";
import ControlPerfiles from "./Principal/Administrador/ControlPerfiles.svelte";
import ModulosPerfil from "./Principal/Administrador/ModulosPerfil.svelte";
import ControlModulos from "./Principal/Administrador/ControlModulos.svelte";
// Usuarios
import Tablero from "./Principal/Usuario/Tablero.svelte";
import CapturaAlumnos from "./Principal/Usuario/CapturaAlumnos.svelte";
// Errores
import Error404 from "./Errores/Error404.svelte";

const routes = {
    // Usuario no registrado
        '/':       Inicio,
        '/Acerca': Acerca,
    // Administrador
        '/test':            Test,
        '/ControlMenu':     ControlMenu,        // Módulo
        '/ControlUSuarios': ControlUsuarios,    // Módulo
        '/PerfilesUsuario': PerfilesUsuario,    // Sub módulo de ControlUsuarios
        '/ControlPerfiles': ControlPerfiles,    // Módulo
        '/ModulosPerfil':   ModulosPerfil,      // Sub módulo de ControlPerfiles
        '/ControlModulos':  ControlModulos,     // Módulo
    // Usuarios
        '/Tablero': Tablero,
        '/CapturaAlumnos': CapturaAlumnos,
    // Errores
        '*': Error404
}

export default routes