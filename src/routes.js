import Inicio from "./Principal/UsuarioFinal/Inicio.svelte";
import Acerca from "./Principal/UsuarioFinal/Acerca.svelte";
import Test from "./Principal/Administrador/test.svelte";
import ControlMenu from "./Principal/Administrador/ControlMenu.svelte";
import ControlUsuarios from "./Principal/Administrador/ControlUsuarios.svelte";
import ControlPerfiles from "./Principal/Administrador/ControlMenu.svelte";
import Tablero from "./Principal/Usuario/Tablero.svelte";
import Error404 from "./Errores/Error404.svelte";

const routes = {
    // UsuarioFinal
        '/':       Inicio,
        '/Acerca': Acerca,
    // Administrador
        '/test':            Test,
        '/ControlMenu':     ControlMenu,
        '/ControlUSuarios': ControlUsuarios,
        '/ControlPerfiles': ControlPerfiles,
    // Usuarios
        '/Tablero': Tablero,
    // Errores
        '*': Error404
}

export default routes