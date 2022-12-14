import Inicio__SvelteComponent_ from "./Principal/UsuarioFinal/Inicio.svelte";
import Test__SvelteComponent_ from "./Principal/Administrador/test.svelte";
import ControlMenu__SvelteComponent_ from "./Principal/Administrador/ControlMenu.svelte";
import Error404__SvelteComponent_ from "./Errores/Error404.svelte";

const routes = {
    // UsuarioFinal
    '/':Inicio__SvelteComponent_,
    // Administrador
    '/test':Test__SvelteComponent_,
    '/ControlMenu':ControlMenu__SvelteComponent_,
    // Errores
    '*':Error404__SvelteComponent_
}

export default routes