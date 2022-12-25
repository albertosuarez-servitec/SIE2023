<script>
    // @ts-nocheck
    // LIBRERIAS O COMPONENTES CON VARIABLE EXTRA
    import Spinner from "../Componentes/Spinner.svelte";
    let spinner = false

    // LIBRERIAS O COMPONENTES SIN VARIABLE EXTRA
    import { push, location } from 'svelte-spa-router'
    import Lugar from "../lugares"
    import Modal from "../Componentes/Modal.svelte";
    import Swal from "sweetalert2"
    import axios from "axios";

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
        modEntrar = false
        if ( data == 'save' && correo != '' && clave != '' ) {

        }
	}

    // RECUPERAR CLAVE
    let codigo = ''
    let codigoValido = false
    let modRecuperarClave = false
    let pasoRecuperarClave = 3
    let claveNueva1 = ''
    let claveNueva2 = ''
    let claveNueva1Valida = false
    let claveNueva2Valida = false
    let claveNuevaValida = false
    let verClaveNueva1 = false
    let verClaveNueva2 = false
    const recuperarClave = () => {
        modEntrar = false
        correo = ''
        correoValido = false
        codigo = ''
        codigoValido = false
        modRecuperarClave =  true
    }

    const solicitarCodigo = async () => {
        pasoRecuperarClave = 2
        try {
            spinner = true
            rs = await axios.post(Lugar.backend+'solicitar_codigo.php',{
                correo: correo
            })
            spinner = false
        } catch (e) {}
    }

    const numerosSinCero = (string) => {
        let out = '';
        let filtro = '123456789';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out += string.charAt(i);
        return out
    }

    const validarCodigo = () => {
        codigo = numerosSinCero(codigo)
        if ( codigo.length > 6 ) {
            codigo = codigo.substring(0,6)
        }
        if ( codigo.length == 6 ) {
            codigoValido = true
        } else {
            codigoValido = false
        }
    }

    const verificarCodigo = async () => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'verificar_codigo.php',{
                correo: correo,
                codigo: codigo
            })
            spinner = false
            if ( rs.data.codigo_correcto ) {
                pasoRecuperarClave = 3
            } else {
                Swal.fire({icon: 'error',title: 'Datos erroneos',text: 'Algo no coincide, por favor revisa de nuevo.'})
            }
        } catch (e) {}
    }

    const validarClaveNueva1 = async () => {
        claveNueva1 = claveNueva1.trim()
        if ( contarAltas(claveNueva1)>0 && contarBajas(claveNueva1)>0 && contarNumeros(claveNueva1)>0 && contarEspeciales(claveNueva1)>0 && claveNueva1.length>7 ) {
            claveNueva1Valida =  true
        } else {
            claveNueva1Valida = false
        }
        if ( claveNueva1Valida && claveNueva2Valida && claveNueva1 === claveNueva2) {
            claveNuevaValida = true
        } else {
            claveNuevaValida = false
        }
    }

    const validarClaveNueva2 = async () => {
        claveNueva2 = claveNueva2.trim()
        if ( contarAltas(claveNueva2)>0 && contarBajas(claveNueva2)>0 && contarNumeros(claveNueva2)>0 && contarEspeciales(claveNueva2)>0 && claveNueva2.length>7 ) {
            claveNueva2Valida =  true
        } else {
            claveNueva2Valida = false
        }
        if ( claveNueva1Valida && claveNueva2Valida && claveNueva1 === claveNueva2) {
            claveNuevaValida = true
        } else {
            claveNuevaValida = false
        }
    }

    const resRecuperarClave = async () => {
        modRecuperarClave = false
        pasoRecuperarClave = 1
    }

    const cambiarClave = async () => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'cambiar_clave_recuperar.php',{
                correo: correo,
                usuario_clave: claveNueva1
            })
            spinner = false
        } catch (e) {}
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

    // OTRAS FUNCIONES
    const contarAltas = (string) =>{
        let out = 0;
        let filtro = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out++
        return out
    }

    const contarBajas = (string) => {
        let out = 0;
        let filtro = 'abcdefghijklmnopqrstuvwxyz';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out++;
        return out
    }

    const contarNumeros = (string) =>{
        let out = 0;
        let filtro = '0123456789';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out++;
        return out
    }

    const contarEspeciales = (string) => {
        let out = 0;
        let filtro = '@!#$%&/()=';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out++
        return out
    }

    // DEBUG
    let debug = false
</script>

<main>

    <nav class="navbar navbar-expand-lg bg-amarillo-1">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <button class="btn {ubicacion == '#/' ? 'active' : ''}" on:click={()=>cambiar('/')}>Inicio</button>
                </li>
                <li class="nav-item">
                    <button class="btn {ubicacion == '#/Acerca' ? 'active' : ''}" on:click={()=>cambiar('/Acerca')}>Acerca de este software</button>
                </li>
            </ul>
            <form class="d-flex">
                <button class="btn entrar" on:click={entrar}>Entrar</button>
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
        <div class="d-grid gap-2">
            <button class="btn btn-warning" on:click={recuperarClave}>Recuperar contraseña</button>
        </div>
    </Modal>
    
    <Modal open={modRecuperarClave} onClosed={(data) => resRecuperarClave(data)}
        title="RECUPERAR CLAVE" 
        saveButton="Off" 
        closeButtonText="Cancelar"
        modalSize = "modal-lg">
        {#if pasoRecuperarClave == 1}
            <div class="input-group mb-3">
                <span class="input-group-text"><strong>Correo:</strong></span>
                <input type="text" class="form-control" bind:value={correo} on:input={validarCorreo}>
                <button class="btn btn-success" disabled={correoValido ? false : true} on:click={solicitarCodigo}>Solicitar código</button>
                <button class="btn btn-warning" disabled={correoValido ? false : true} on:click={()=>pasoRecuperarClave = 2}>Ya tengo un código</button>
            </div>
        {:else if pasoRecuperarClave == 2}
            <div class="input-group mb-3">
                <span class="input-group-text"><strong>Código obtenido:</strong></span>
                <input type="text" class="form-control" bind:value={codigo} on:input={validarCodigo}>
                <button class="btn btn-success" disabled={codigoValido ? false : true} on:click={verificarCodigo}>Validar código</button>
            </div>
        {:else if pasoRecuperarClave == 3}
            <div class="row">
                <div class="col-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><strong>Clave nueva:</strong></span>
                        {#if verClaveNueva1}
                             <input type="text" class="form-control" bind:value={claveNueva1} on:input={validarClaveNueva1}>
                        {:else}
                             <input type="password" class="form-control" bind:value={claveNueva1} on:input={validarClaveNueva1}>
                        {/if}
                        <button class="btn btn-outline-secondary" on:click={()=>verClaveNueva1 = !verClaveNueva1}><i class="bi {verClaveNueva1 ? 'bi-eye-fill' : 'bi-eye-slash-fill'} {verClaveNueva1 ? 'text-danger' : 'text-success'}"></i></button>
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><strong>Repetir clave:</strong></span>
                        {#if verClaveNueva2}
                            <input type="text" class="form-control" bind:value={claveNueva2} on:input={validarClaveNueva2}>
                        {:else}
                            <input type="password" class="form-control" bind:value={claveNueva2} on:input={validarClaveNueva2}>
                        {/if}
                        <button class="btn btn-outline-secondary" on:click={()=>verClaveNueva2 = !verClaveNueva2}><i class="bi {verClaveNueva2 ? 'bi-eye-fill' : 'bi-eye-slash-fill'} {verClaveNueva2 ? 'text-danger' : 'text-success'}"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col 12">
                    <div class="d-grid gap-2">
                        <button class="btn btn-rojo-5 cambiar-clave" disabled='{claveNuevaValida ? false : true}' on:click={cambiarClave}>Cambiar clave</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                MANUAL DE CONTRASEÑAS
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                                <div class="accordion-body">
                                    <p><strong>Requerimientos para generar una contraseña válida</strong></p>
                                    <ul>
                                        <li>1. Deben ser al menos 8 caracteres.</li>
                                        <li>2. Debe contener al menos una letra mayúscula.</li>
                                        <li>3. Debe contener al menos una letra minúscula.</li>
                                        <li>4. Debe contener al menos un número.</li>
                                        <li>5. Debe contener al menos un signo de los siguientes: !#$%&/()=</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {/if}
    </Modal>

    {#if debug}
        <div class="debug">
            <div class="input-group mb-1">
                <span class="input-group-text">correoValido</span><input type="text" class="form-control" bind:value={correoValido}>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text">codigoValido</span><input type="text" class="form-control" bind:value={codigoValido}>
            </div>
        </div>
    {/if}
</main>

<style>
    .accordion-button {
        background-color: var(--amarillo-5);
        color: white;
    }
    .accordion-body {
        background-color: var(--amarillo-1);
    }
    .cambiar-clave {
        margin-bottom: 10px;
    }
    .container-fluid {
        padding-left: 5px;
    }
    .nav-item {
        margin: 0 3px;
    }
    .nav-item .btn, .entrar {
        color: var(--azul-5);
        border: solid var(--azul-5) 1px;
    }
    .nav-item .btn:hover, .active, .entrar:hover {
        color: var(--amarillo-3) !important;
        background-color: var(--azul-5);
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