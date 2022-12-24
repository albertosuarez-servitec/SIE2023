<script>
    // @ts-nocheck
    // LIBRERIAS O COMPONENTES CON VARIABLE EXTRA
    import Spinner from "../Componentes/Spinner.svelte";
    let spinner = false

    // LIBRERIAS O COMPONENTES SIN VARIABLE EXTRA
    import { push } from 'svelte-spa-router'
    import Lugar from "../lugares"
    import Modal from "../Componentes/Modal.svelte";
    import Swal from "sweetalert2"

    // CAMBIO EN MENÚ
    let ubicacion = window.location.hash
    const cambiar = async (sitio) => {
        await push(sitio)
        ubicacion = window.location.hash
    }

    // ENTRAR
    let modEntrar = false
    let correo = ''
    let clave = ''
    const entrar = async () => {
        modEntrar = true
    }

    const resModEntrar = async (data) => {
        correo = correo.trim()
        modAgregarRegistro = false
        if (data == 'save'){
        } else {
            Swal.fire({icon: 'error',title: 'Faltan datos',text: 'El/Los Nombre(s) y el primer apellido deben ser válidos, así como el correo.'})
        }
	}

    let correoValido = false
    const validarCorreo = async () => {
        correo = correo.toLowerCase()
        if ( /^\w+([\.\-_]?\w+)*@\w+\.\w+(\.\w+)*$/.test(correo) ) {
            correoValido = true
        } else {
            correoValido = false
        }
    }

    let claveValida = false
    const validarClave = async (valor) => {
        if ( /^(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8}$/.test(valor) ) {
            claveValida = true
        } else {
            claveValida = false
        }
    }

     // DEBUG
     let debug = true
</script>

<main>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <button class="btn btn-outline-success {ubicacion == '#/' ? 'active' : ''}" on:click={()=>cambiar('/')}>Inicio</button>
                </li>
                <li class="nav-item">
                    <button class="btn btn-outline-success {ubicacion == '#/Acerca' ? 'active' : ''}" on:click={()=>cambiar('/Acerca')}>Acerca de este software</button>
                </li>
            </ul>
            <form class="d-flex">
                <button class="btn btn-outline-success" on:click={entrar}>Entrar</button>
            </form>
            </div>
        </div>
    </nav>

    <!-- MODALES -->
    <Modal open={modEntrar} onClosed={(data) => resModEntrar(data)}
        title="Entrar al SIENCRyM:" 
        saveButtonText="Registrarse" 
        closeButtonText="Cancelar">
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Correo:*</strong></span>
            <input type="text" class="form-control {correoValido ? 'is-valid' : 'is-invalid'}" bind:value={correo} on:input={validarCorreo}>
        </div>
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Clave:*</strong></span>
            <input type="text" class="form-control {claveValida ? 'is-valid' : 'is-invalid'}" bind:value={clave} on:input={()=>validarClave(clave)}>
        </div>
    </Modal>

    {#if debug}
        <div class="debug">
            <div class="input-group mb-1">
                <span class="input-group-text">correoValido</span><input type="text" class="form-control" bind:value={correoValido}>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text">claveValida</span><input type="text" class="form-control" bind:value={claveValida}>
            </div>
        </div>
    {/if}
</main>

<style>
    .nav-item {
        margin: 0 3px;
    }
    /* DEBUG */
    .debug {
        position: absolute;
        bottom: 10px;
        right: 10px;
    }
    .debug .input-group {
        margin-bottom: 0px !important;
    }
</style>