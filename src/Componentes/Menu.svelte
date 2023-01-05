<script>
    // @ts-nocheck
    // LIBRERIAS O COMPONENTES CON VARIABLE EXTRA
    import Spinner from "../Componentes/Spinner.svelte"
    let spinner = false

    // LIBRERIAS O COMPONENTES SIN VARIABLE EXTRA
    import { push } from 'svelte-spa-router'
    import Lugar from "../lugares"
    import Modal from "../Componentes/Modal.svelte"
    import Swal from "sweetalert2"
    import axios from "axios"

    // SESIÓN ACTIVA
    let usuarioActual_nombre = ''
    let usuarioActual_id = ''
    let enSesion = false
    let tiempoRestante = 0
    const sesion = async () => {
        if ( !(sessionStorage.getItem( 'token' ) === null )) {
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'revisar_sesion.php',{
                    token: sessionStorage.getItem( 'token' )
                })
                spinner = false
            
                if ( rs.data.en_sesion_previa ) {
                    usuarioActual_nombre = sessionStorage.getItem( 'nombreCompleto' )
                    usuarioActual_id = sessionStorage.getItem( 'id_usuario' )
                    enSesion = true
                    tiempoRestante = rs.data.tiempo_restante
                    temporizadorSesion(tiempoRestante)
                } else {
                    sessionStorage.clear()
                    push("/")
                }

            } catch (e) {}
        } else {
            sessionStorage.clear()
        }
    }
    sesion()

    // SESION
    let modSesion = false
    let tiempoSesion
    let cerrarSesionAuto

    const avisoSesion = () => {
        modSesion = true
        cerrarSesionAuto = setTimeout(cerrarSesion,60000)
    }

    const resModSesion = (data) => {
        if ( data == 'save' ) {
            extenderSesion()
        } else if ( data == 'cancel' || data == 'close' ) {
            cerrarSesion()
        }
    }

    const temporizadorSesion = (tiempo) => {
        modSesion = false
        tiempoSesion = setTimeout(avisoSesion, tiempo - 60000);
    }

    const extenderSesion = async () => {
        clearTimeout(cerrarSesionAuto)
        modSesion = false
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'extender_sesion.php',{
                id_usuario: sessionStorage.getItem('id_usuario')
            })
            spinner = false
        } catch (e) {}
        sesion()
    }

    // CAMBIO EN MENÚ
    let ubicacion = window.location.hash
    const cambiar = async (sitio) => {
        await push(sitio)
        ubicacion = window.location.hash
    }

    // ENTRAR
    let modEntrar = false
    let foto = ''
    let correo = 'alberto_suarez_v@encrym.edu.m'
    let clave = '3Ruckt00'
    let verClave = false
    const entrar = async () => {
        modEntrar = true
    }

    const resModEntrar = async (data) => {
        correo = correo.trim()
        modEntrar = false
        if ( data == 'save' && correoValido && claveValida ) {
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'login.php',{
                    correo: correo,
                    usuario_clave: clave
                })
                spinner = false
                if ( rs.data.correo_existente ) {
                    if ( rs.data.clave_correcta ) {
                        if ( rs.data.usuario_bloqueado ) {
                            Swal.fire({icon: 'error',title: 'Datos erroneos',text: 'Algo no coincide, por favor revisa de nuevo.'})
                        } else {
                            if ( rs.data.en_sesion_previa ) {
                                if ( rs.data.token == sessionStorage.getItem( 'token' ) ) {
                                    Swal.fire({icon: 'info',title: 'Ya registrado',text: 'Acceso concedido, la sesión no ha terminado.'})
                                } else {
                                    Swal.fire({icon: 'info',title: 'Sesión en uso',text: 'Alguien ya está utilizando el acceso.'})
                                    correo = ''
                                    clave = ''
                                    sessionStorage.clear()
                                }
                            } else {
                                sessionStorage.setItem( 'token',rs.data.token )
                                sessionStorage.setItem( 'nombreCompleto',rs.data.nombre_completo)
                                sessionStorage.setItem( 'id_usuario',rs.data.id_usuario)
                                sessionStorage.setItem( 'foto',rs.data.foto)
                                sesion()
                                Swal.fire({icon: 'success',title: 'Datos correctos',text: 'Acceso concedido, no cierre su navegador sin salir del sistema.'})
                                push( '/Tablero' )
                                ubicacion = '#/Tablero'
                                correo = ''
                                clave = ''
                            }
                        }
                    } else {
                        Swal.fire({icon: 'error',title: 'Datos erroneos',text: 'Algo no coincide, por favor revisa de nuevo.'})
                    }
                } else {
                    Swal.fire({icon: 'error',title: 'Datos erroneos',text: 'Algo no coincide, por favor revisa de nuevo.'})
                }
            } catch (e) {}
        } else {
            correo = ''
            correoValido = false
            clave = ''
            claveValida = false
        }
	}

    // RECUPERAR CLAVE
    let codigo = ''
    let codigoValido = false
    let modRecuperarClave = false
    let pasoRecuperarClave = 1
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

    let claveValida = false
    const validarClave = async () => {
        clave = clave.trim()
        if ( contarAltas(clave)>0 && contarBajas(clave)>0 && contarNumeros(clave)>0 && contarEspeciales(clave)>0 && clave.length>7 ) {
            claveValida =  true
        } else {
            claveValida = false
        }
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

    // CERRAR SESION
    const cerrarSesion = async () => {
        modSesion = false
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'cerrar_sesion.php',{
                id_usuario: sessionStorage.getItem( 'id_usuario' )
            })
            spinner = false
            sessionStorage.clear()
            usuarioActual_id = 0
            usuarioActual_nombre = ''
            enSesion = false
            correo = ''
            correoValido = false
            claveValida = false
            clave = ''
            push( '/' )
        } catch (e) {}
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

    const enterLogin = e => {
        if (e.charCode === 13) resModEntrar('save');
    };

    // DEBUG
    let debug = true

</script>

<main>
    {#if debug}
        <div class="debug">
            <div class="input-group mb-1">
                <span class="input-group-text">tiempoRestante</span><input type="text" class="form-control" bind:value={tiempoRestante}>
            </div>
        </div>
    {/if}
    <nav class="navbar navbar-expand-lg bg-amarillo-1">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <button class="btn {ubicacion == '#/' || ubicacion == '' ? 'active' : ''}" on:click={()=>cambiar('/')}>Inicio</button>
                </li>
                <li class="nav-item">
                    <button class="btn {ubicacion == '#/Acerca' ? 'active' : ''}" on:click={()=>cambiar('/Acerca')}>Acerca de este software</button>
                </li>
                {#if enSesion }
                    <li class="nav-item">
                        <button class="btn {ubicacion == '#/Tablero' ? 'active' : ''}" on:click={()=>cambiar('/Tablero')}>Tablero</button>
                    </li>
                {/if}
            </ul>
            {#if usuarioActual_nombre != ''}
                 <div class="btn-group">
                    <button type="button" class="btn btn-azul-5"><strong>{usuarioActual_nombre}</strong></button>
                    <button type="button" class="btn btn-azul-3 dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                      <span ><img class="foto" src={Lugar.fotos+sessionStorage.getItem( 'foto' )} alt="" style="border-radius:50%;"></span>
                    </button>
                    <ul class="dropdown-menu bg-azul-3">
                      <li><button class="dropdown-item btn-azul-3" on:click={cerrarSesion}>Cerrar sesión { tiempoRestante }</button></li>
                    </ul>
                  </div>
            {:else}
                 <button class="btn entrar" on:click={entrar}>Entrar</button>
            {/if}
            </div>
        </div>
    </nav>

    <!-- MODALES -->
    <Modal open={modEntrar} onClosed={(data) => resModEntrar(data)}
        title="Entrar al SIENCRyM:" 
        saveButtonText="Acceder al sistema" 
        closeButtonText="Cancelar">
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Correo:*</strong></span>
            <input type="text" class="form-control {correoValido ? 'is-valid' : 'is-invalid'}" bind:value={correo} on:input={validarCorreo} on:keypress={enterLogin}>
        </div>
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Clave:*</strong></span>
            {#if verClave}
                <input type="text" class="form-control {claveValida ? 'is-valid' : 'is-invalid'}" bind:value={clave} on:input={()=>validarClave(clave)} on:keypress={enterLogin}>
            {:else}
                <input type="password" class="form-control {claveValida ? 'is-valid' : 'is-invalid'}" bind:value={clave} on:input={()=>validarClave(clave)} on:keypress={enterLogin}>
            {/if}
            <button class="btn btn-outline-secondary" on:click={()=>verClave = !verClave}><i class="bi {verClave ? 'bi-eye-fill' : 'bi-eye-fill'} {verClave ? 'text-danger' : 'text-success'}"></i></button>
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
                        <button class="btn btn-outline-secondary" on:click={()=>verClaveNueva1 = !verClaveNueva1}><i class="bi {verClaveNueva1 ? 'bi-eye-fill' : 'bi-eye-fill'} {verClaveNueva1 ? 'text-danger' : 'text-success'}"></i></button>
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
                        <button class="btn btn-outline-secondary" on:click={()=>verClaveNueva2 = !verClaveNueva2}><i class="bi {verClaveNueva2 ? 'bi-eye-fill' : 'bi-eye-fill'} {verClaveNueva2 ? 'text-danger' : 'text-success'}"></i></button>
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

    <Modal open={modSesion} onClosed={(data) => resModSesion(data)}
        title="Cerrar sesión:" 
        saveButtonText="Extender sesión" 
        closeButtonText="Cerrar sesión">
        <h5 class="text-center">La sesión se cerrará en un minuto por falta de actividad, ¿desea extenderla?</h5>
        <p>{tiempoRestante}</p>
    </Modal>
</main>

<style>
    .foto {
        width: 30px;
    }
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