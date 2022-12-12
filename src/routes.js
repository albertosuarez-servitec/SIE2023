import Inicio__SvelteComponent_ from "./Principal/UsuarioFinal/Inicio.svelte";
import Test__SvelteComponent_ from "./Principal/Administrador/test.svelte";
import MenusAdmin__SvelteComponent_ from "./Principal/Administrador/MenusAdmin.svelte";
import Error404__SvelteComponent_ from "./Errores/Error404.svelte";

const routes = {
    // UsuarioFinal
    '/':Inicio__SvelteComponent_,
    // Administrador
    '/test':Test__SvelteComponent_,
    '/MenusAdmin':MenusAdmin__SvelteComponent_,
    // Errores
    '*':Error404__SvelteComponent_
}

export default routes