<script>
    // @ts-nocheck
    // LIBRERIAS O COMPONENTES CON VARIABLE EXTRA
    import Spinner from "../../Componentes/Spinner.svelte";
    let spinner = false
    
    // LIBRERIAS O COMPONENTES SIN VARIABLE EXTRA
    import axios from "axios"
    import Lugar from "../../lugares"
    import Modal from "../../Componentes/Modal.svelte";
    import Swal from "sweetalert2"
    import { push } from "svelte-spa-router";
    
    // PAGINADOR
    let maxRegsPP = 10
    let paginas = 0
    let paginaActual = 1
    let registroInicial = 1
    let registroFinal = 10
    let residuo = 0
    let pagAd = 0
    let rsRegistros = []
    let rsRoles = []
    
    const cambiarMaxRegsPP = () => {
        paginas = Math.floor( rsRegistros.length / maxRegsPP )
        residuo = ( rsRegistros.length % maxRegsPP )
        if ( residuo > 0 ) {
            pagAd = 1
        } else {
            pagAd = 0
        }
        paginas = paginas + pagAd
        paginaActual = 1
        registroInicial = 1
        registroFinal = maxRegsPP
    }

    const cambiarPagina = (pagina) => {
        paginaActual = pagina
        registroInicial = (paginaActual * maxRegsPP) - (maxRegsPP - 1)
        registroFinal = paginaActual * maxRegsPP
    }

    // RECORDSET INICIAL
    let filtro = ''
    let tieneRegistros = false
    let tieneRoles = false
    const main = async () => {
        try {
            spinner = true
                const rs = await axios.post(Lugar.backend+'control_usuarios.php',{
                    filtro: filtro
                })
            spinner = false
            if ( rs.data.tieneRegistros ) {
                tieneRegistros = true
                rsRegistros = Object.values(rs.data.rsRegistros)
                paginas = Math.floor( rsRegistros.length / maxRegsPP )
                residuo = ( rsRegistros.length % maxRegsPP )
                if ( residuo > 0 ) {
                    pagAd = 1
                } else {
                    pagAd = 0
                }
                paginas = paginas + pagAd
                paginaActual = 1
                registroInicial = 1
                registroFinal = maxRegsPP
            }
            if ( rs.data.tieneRoles ) {
                tieneRoles = true
                rsRoles = Object.values(rs.data.rsRoles)
            }
        } catch (e) {
            //console.log(e)
        }
    }
    main()

    // CAMBIAR CLAVE
    const cambiarCambiar_Clave = async (id_usuarioT, cambiar_claveT) => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'cambiar_cambiar_clave.php',{
                id_usuario: id_usuarioT,
                cambiar_clave: cambiar_claveT
            })
            spinner = false
        } catch (e) {}
    }

    // BLOQUEO
    const cambiarBloqueo = async (id_usuarioT, bloqueoT) => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'cambiar_bloqueo.php',{
                id_usuario: id_usuarioT,
                bloqueo: bloqueoT
            })
            spinner = false
        } catch (e) {}
    }

    // AGREGAR REGISTRO
    let id_usuario = 0
    let nombres = ''
    let primer_apellido = ''
    let segundo_apellido = ''
    let correo = ''
    let codigo = ''
    let modAgregarRegistro = false
    const agregarRegistro = () => {
        nombres = ''
        primer_apellido = ''
        segundo_apellido = ''
        correo = ''
        codigo = ''
        nombresValido = false
        primer_apellidoValido = false
        correoValido = false
        modAgregarRegistro = true
    }

    const resAgregarRegistro = async (data) => {
        nombres = nombres.trim()
        primer_apellido = primer_apellido.trim()
        correo = correo.trim()
        modAgregarRegistro = false
        if (data == 'save'){
            if ( nombresValido && primer_apellidoValido && correoValido ) {
                try {
                    spinner = true
                    const rs = await axios.post(Lugar.backend+'agregar_registro_usuarios.php', {
                        nombres: nombres,
                        primer_apellido: primer_apellido,
                        segundo_apellido: segundo_apellido,
                        correo: correo
                    })
                    spinner = false
                    main()
                } catch (e) {}
            } else {
                Swal.fire({icon: 'error',title: 'Faltan datos',text: 'El/Los Nombre(s) y el primer apellido deben ser v??lidos, as?? como el correo.'})
            }
        }
	}

    // EDITAR REGISTRO
    let modEditarRegistro = false
    const editarRegistro = (id_usuarioT,nombresT,primer_apellidoT,segundo_apellidoT,correoT,codigoT) => {
        id_usuario = id_usuarioT
        nombres = nombresT
        primer_apellido = primer_apellidoT
        segundo_apellido = segundo_apellidoT
        correo = correoT
        codigo = codigoT
        modEditarRegistro = true
    }

    let nombresValido = true
    const validarNombres = () => {
        if ( nombres.length > 2 ) {
            nombresValido = true
        } else {
            nombresValido = false
        }
    }

    let primer_apellidoValido = true
    const validarPrimer_Apellido = () => {
        if ( primer_apellido.length > 2 ) {
            primer_apellidoValido = true
        } else {
            primer_apellidoValido = false
        }
    }

    let correoValido = true
    const validarCorreo = () => {
        if ( correo.length > 2 ) {
            correoValido = true
        } else {
            correoValido = false
        }
    }

    let codigoValido = true
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

    let claveValida = false
    const validarClave = () => {
        if ( clave.length > 2 ) {
            claveValida = true
        } else {
            claveValida = false
        }
    }

    const resEditarRegistro = async (data) => {
        nombres = nombres.trim()
        primer_apellido = primer_apellido.trim()
        segundo_apellido = segundo_apellido.trim()
        correo = correo.trim()
        modEditarRegistro = false
        if (data == 'save'){
            if ( nombresValido && primer_apellidoValido && correoValido) {
                try {
                    spinner = true
                    const rs = await axios.post(Lugar.backend+'actualizar_usuarios.php', {
                        id_usuario: id_usuario,
                        nombres: nombres,
                        primer_apellido: primer_apellido,
                        segundo_apellido: segundo_apellido,
                        correo: correo,
                        codigo: codigo
                    })
                    spinner = false
                    main()
                } catch (e) {}
            } else {
                Swal.fire({icon: 'error',title: 'Faltan datos',text: 'El/Los Nombre(s), as?? como el primer apellido deben ser v??lidos al igual que el correo.'})
            }
        }
	}

    // CAMBIAR CLAVE
    let modCambiarClave = false
    let clave = ''
    let nombreCompleto = ''
    const cambiarClave = async (id_usuarioT, nombreCompletoT) => {
        id_usuario = id_usuarioT
        nombreCompleto = nombreCompletoT
        clave = ''
        claveValida = false
        modCambiarClave = true
    }

    const resCambiarClave = async (data) => {
        modCambiarClave = false
        if (data == 'save'){
            if ( claveValida ) {
                try {
                    spinner = true
                    const rs = await axios.post(Lugar.backend+'cambiar_clave_usuario.php',{
                        id_usuario: id_usuario,
                        usuario_clave: clave
                    })
                    spinner = false
                } catch (e) {}
                main()
            } else {
                Swal.fire({icon: 'error',title: 'Error en la clave',text: 'La clave que escribi?? no es v??lida.'})
            }
        }
	}

    // ELIMINAR USUARIO
    let modEliminarUsuario = false
    const eliminarUsuario = async (id_usuarioT, nombreCompletoT) => {
        id_usuario = id_usuarioT
        nombreCompleto = nombreCompletoT
        modEliminarUsuario = true
    }

    const resEliminarUsuario = async (data) => {
        modEliminarUsuario = false
        if (data == 'save'){
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'eliminar_registro_usuarios.php',{
                    id_usuario: id_usuario
                })
                spinner = false
            } catch (e) {}
            main()
        }
	}

    // ROL ADMINISTRATIVO
    let administrativo_activo = false
    let modRolAdministrativo = false
    const rolAdministrativo = async (id_usuarioT, nombreCompletoT, administrativo_activoT) => {
        id_usuario = id_usuarioT
        nombreCompleto = nombreCompletoT
        administrativo_activo = administrativo_activoT
        modRolAdministrativo = true
    }

    const resRolAdministrativo = async (data) => {
        modRolAdministrativo = false
        if (data == 'save'){
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'actualizar_rol_administrativo.php',{
                    id_usuario: id_usuario,
                    administrativo_activo: administrativo_activo,
                })
                spinner = false
            } catch (e) {}
            main()
        }
	}
    // ROL DOCENTE
    let docente_activo = false
    let modRolDocente = false
    const rolDocente = async (id_usuarioT, nombreCompletoT, docente_activoT) => {
        id_usuario = id_usuarioT
        nombreCompleto = nombreCompletoT
        docente_activo = docente_activoT
        modRolDocente = true
    }

    const resRolDocente = async (data) => {
        modRolDocente = false
        if (data == 'save'){
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'actualizar_rol_docente.php',{
                    id_usuario: id_usuario,
                    docente_activo: docente_activo,
                })
                spinner = false
            } catch (e) {}
            main()
        }
	}
    // ROL ESTUDIANTE
    let estudiante_activo = false
    let modRolEstudiante = false
    const rolEstudiante = async (id_usuarioT, nombreCompletoT, estudiante_activoT) => {
        id_usuario = id_usuarioT
        nombreCompleto = nombreCompletoT
        estudiante_activo = estudiante_activoT
        modRolEstudiante = true
    }

    const resRolEstudiante = async (data) => {
        modRolEstudiante = false
        if (data == 'save'){
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'actualizar_rol_estudiante.php',{
                    id_usuario: id_usuario,
                    estudiante_activo: estudiante_activo,
                })
                spinner = false
            } catch (e) {}
            main()
        }
	}

    // PERFILES DEL USUARIO
    const perfilesUsuario = async (id_usuarioT, nombreCompletoT) => {
        sessionStorage.setItem( 'id_usuario_t', id_usuarioT )
        sessionStorage.setItem( 'nombreCompleto_t', nombreCompletoT)
        push( '/PerfilesUsuario' )
    }



    // FUNCIONES GENERALES
    const limpiarFiltro = () => {
        filtro = ''
        main()
    }

    const zeroFill = (num,largo) => {
        let zero = '0'
        num = num.toString()
        let numl = num.length
        return zero.repeat(largo-numl)+num
    }

    const numerosSinCero = (string) => {
        let out = '';
        let filtro = '123456789';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out += string.charAt(i);
        return out
    }

    const autogenerar = (cadena, largo) => {
        let auto = ''
        while (auto.length < largo) {
            let char = Math.floor(Math.random() * cadena.length + 1)
            auto += cadena.charAt(char)
        }
        return auto
    }

    // LOGEO
    const logeo = (unixTime) => {
        if ( unixTime == 0 ) {
            return 'Nunca'
        } else {
            let date = new Date(unixTime)
            return zeroFill(date.getDate(),2)+'/'+zeroFill(date.getMonth()+1,2)+'/'+date.getFullYear()+' '+zeroFill(date.getHours(),2)+':'+zeroFill(date.getMinutes(),2)
        }
    }

    // DEBUG
    let debug = false

</script>

<main>

    {#if spinner}
        <Spinner />
    {/if}

    <div class="barra bg-light">
        <div class="row">
            <div class="col-6 col-lg-8 col-xxl-9">
                <div class="input-group mb-3 barra-filtrar ">
                    <span class="input-group-text"><strong>Filtrar:</strong></span>
                    <input type="text" class="form-control" placeholder="Escriba para filtrar" bind:value={filtro}>
                    <button class="btn btn-outline-success" on:click={limpiarFiltro}>Limpiar</button>
                    <button class="btn btn-outline-success" on:click={main}>Buscar</button>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-xxl-3">
                <div class="input-group mb-3 barra-paginador bg-light">
                    <span class="input-group-text"><strong>Registros por p??gina:</strong></span>
                    <select class="form-select" bind:value={maxRegsPP} on:change="{cambiarMaxRegsPP}">
                        <option value={10}>10</option>
                        <option value={20}>20</option>
                        <option value={50}>50</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 col-lg-8 col-xxl-9">
                <div class="input-group mb-3 barra-acciones">
                    <button class="btn btn-success" type="button" on:click={agregarRegistro}><i class="bi bi-plus-circle"></i> Agregar usuario</button>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-xxl-3 navs">
                {#if tieneRegistros }
                    <div class="input-group mb-3 barra-navPaginas">
                        <div role="group">
                            {#if paginaActual != 1}
                                <button type="button" class="btn btn-primary" on:click={()=>cambiarPagina(1)}>{'<<'}</button>
                            {:else}
                                <button type="button" class="btn btn-primary" disabled>{'<<'}</button>
                            {/if}
                            {#if paginaActual != 1}
                                <button type="button" class="btn btn-primary" on:click={()=>cambiarPagina(paginaActual-1)}>{'<'}</button>
                            {:else}
                                <button type="button" class="btn btn-primary" disabled>{'<'}</button>
                            {/if}
                            {#each Array(paginas) as _, i}
                                {#if i+1 == paginaActual - 1 || i+1 == paginaActual || i+1 == paginaActual + 1}
                                    <button type="button" class="btn {paginaActual == i+1 ? 'btn-primary' : 'btn-light'}" on:click={()=>cambiarPagina(i+1)}>{i+1}</button>
                                {/if}
                            {/each}
                            {#if paginaActual < paginas}
                                <button type="button" class="btn btn-primary" on:click={()=>cambiarPagina(paginaActual+1)}>{'>'}</button>
                            {:else}
                                <button type="button" class="btn btn-primary" disabled>{'>'}</button>
                            {/if}
                            {#if paginaActual < paginas}
                                <button type="button" class="btn btn-primary" on:click={()=>cambiarPagina(paginas)}>{'>>'}</button>
                            {:else}
                                <button type="button" class="btn btn-primary" disabled>{'>>'}</button>
                            {/if}
                        </div>
                    </div>
                {/if}
            </div>
        </div>
        
        <div class="barra-registros">
            <table class="table table-hover bg-light table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Acciones</th>
                        <th scope="col">Nombre completo</th>
                        <th class="text-center" scope="col">Roles</th>
                        <th scope="col">Correo electr??nico</th>
                        <th class="text-center" scope="col">Debe cambiar clave</th>
                        <th class="text-center" scope="col">Bloqueado</th>
                        <th class="text-center" scope="col">C??digo temporal</th>
                        <th class="text-center" scope="col">Logeo</th>
                    </tr>
                </thead>
                <tbody >
                    {#if tieneRegistros}
                        {#each rsRegistros as registro}
                            {#if registro.numero >= registroInicial && registro.numero <= registroFinal}
                                <tr>
                                    <th scope="row">{registro.numero}</th>
                                    <th>
                                        <div>
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <i class="bi bi-pencil-fill text-warning pointer" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="right" 
                                                title="Editar registro" 
                                                style="font-size:large;"
                                                on:click={()=>editarRegistro(registro.id_usuario,registro.nombres,registro.primer_apellido,registro.segundo_apellido,registro.correo,registro.codigo)}>
                                            </i>
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <i class="bi bi-key text-primary pointer" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="right" 
                                                title="Cambiar clave de acceso" 
                                                style="font-size:large;"
                                                on:click={()=>cambiarClave(registro.id_usuario, registro.nombres+' '+registro.primer_apellido+' '+registro.segundo_apellido)}>
                                            </i>
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <i class="bi bi-trash text-danger pointer" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="right" 
                                                title="Eliminar usuario definitivamente" 
                                                style="font-size:large;"
                                                on:click={()=>eliminarUsuario(registro.id_usuario, registro.nombres+' '+registro.primer_apellido+' '+registro.segundo_apellido)}>
                                            </i>
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <i class="bi bi-person-lines-fill text-success pointer" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="right" 
                                                title="Perfiles de usuario" 
                                                style="font-size:large;"
                                                on:click={()=>perfilesUsuario(registro.id_usuario, registro.nombres+' '+registro.primer_apellido+' '+registro.segundo_apellido)}>
                                            </i>
                                        </div>
                                    </th>
                                    <td >{registro.nombres} {registro.primer_apellido} {registro.segundo_apellido}</td>
                                    <td class="text-center pointer">
                                        {#each rsRoles as rol, i}
                                            {#if registro.id_usuario == rol.id_usuario}
                                                <img src="/public/{rol.administrativo}" alt="" style="height:.9rem;" 
                                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Administrativo" 
                                                    on:keydown={null} on:click={()=>
                                                    rolAdministrativo(
                                                        registro.id_usuario, 
                                                        registro.nombres+' '+registro.primer_apellido+' '+registro.segundo_apellido,
                                                        rol.administrativo_activo
                                                    )}
                                                >
                                                <img src="/public/{rol.docente}" alt="" style="height:.9rem;" 
                                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Docente"
                                                    on:keydown={null} on:click={()=>
                                                    rolDocente(
                                                        registro.id_usuario, 
                                                        registro.nombres+' '+registro.primer_apellido+' '+registro.segundo_apellido,
                                                        rol.docente_activo
                                                    )}
                                                >
                                                <img src="/public/{rol.estudiante}" alt="" style="height:.9rem;" 
                                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Estudiante"
                                                    on:keydown={null} on:click={()=>
                                                    rolEstudiante(
                                                        registro.id_usuario, 
                                                        registro.nombres+' '+registro.primer_apellido+' '+registro.segundo_apellido,
                                                        rol.estudiante_activo
                                                    )}
                                                >
                                            {/if}
                                        {/each}
                                    </td>
                                    <td >{registro.correo}</td>
                                    <td class="debeCambiarClave">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" role="switch" bind:checked={registro.cambiar_clave}
                                            on:change={()=>cambiarCambiar_Clave(registro.id_usuario,registro.cambiar_clave)}>
                                        </div>
                                    </td>
                                    <td class="bloqueado">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" role="switch" bind:checked={registro.bloqueo}
                                            on:change={()=>cambiarBloqueo(registro.id_usuario,registro.bloqueo)}>
                                        </div>
                                    </td>
                                    <td class="codigoTemporal text-center">{registro.codigo}</td>
                                    <td class="text-center">{logeo(registro.hora_unix)}</td>
                                </tr>   
                            {/if}
                        {/each}
                    {:else}
                        <td colspan="8">
                            <div class="sin-datos text-center">
                                <div class="card">
                                    <!-- <img src="..." alt='...'> -->
                                    <div class="card-body">
                                    <h5 class="card-title"><strong>Faltan datos</strong></h5>
                                    <p class="card-text">No se encontr?? informaci??n en la base de datos.</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    {/if}
                </tbody>
            </table>
        </div>

        <div class="input-group mb-3 bg-light barra-regresar">
            <button class="btn btn-primary" type="button" on:click={ ()=> push( "/Tablero") }>Regresar</button>
        </div>
    </div>

    <Modal open={modAgregarRegistro} onClosed={(data) => resAgregarRegistro(data)}
        title="Agregar registro:" 
        saveButtonText="Guardar registro" 
        closeButtonText="Cancelar">
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Nombre(s):*</strong></span>
            <input type="text" class="form-control" bind:value={nombres} on:input={validarNombres}>
        </div>
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Primer apellido:*</strong></span>
            <input type="text" class="form-control" bind:value={primer_apellido} on:input={validarPrimer_Apellido}>
        </div>
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Segundo apellido:*</strong></span>
            <input type="text" class="form-control" bind:value={segundo_apellido}>
        </div>
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Correo electr??nico:*</strong></span>
            <input type="text" class="form-control" bind:value={correo} on:input={validarCorreo}>
        </div>
    </Modal>

    <Modal open={modEditarRegistro} onClosed={(data) => resEditarRegistro(data)}
        title="Editar registro:" 
        saveButtonText="Guardar registro" 
        closeButtonText="Cancelar">
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Nombre(s):*</strong></span>
            <input type="text" class="form-control {nombresValido ? 'is-valid' : 'is-invalid'}" bind:value={nombres} on:input={validarNombres}>
        </div>
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Primer apellido:*</strong></span>
            <input type="text" class="form-control {primer_apellidoValido ? 'is-valid' : 'is-invalid'}" bind:value={primer_apellido} on:input={validarPrimer_Apellido}>
        </div>
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Segundo apellido:*</strong></span>
            <input type="text" class="form-control" bind:value={segundo_apellido}>
        </div>
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Correo electr??nico:*</strong></span>
            <input type="text" class="form-control {correoValido ? 'is-valid' : 'is-invalid'}" bind:value={correo} on:input={validarCorreo}>
        </div>
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>C??digo temporal:</strong></span>
            <input type="text" class="form-control {codigoValido ? 'is-valid' : 'is-invalid'}" bind:value={codigo} on:input={validarCodigo}>
            <button class="btn btn-outline-secondary" on:click={()=>codigo = autogenerar('123456789',6)}>Autogenerar</button>
        </div>
    </Modal>

    <Modal open={modCambiarClave} onClosed={(data) => resCambiarClave(data)}
        title="Cambiar clave: ({nombreCompleto})" 
        saveButtonText="Guardar" 
        closeButtonText="Cancelar">
        <div class="input-group mb-1">
            <span class="input-group-text"><strong>Nueva clave:*</strong></span>
            <input type="text" class="form-control" bind:value={clave} on:input={validarClave}>
        </div>
    </Modal>
    
    <Modal open={modEliminarUsuario} onClosed={(data) => resEliminarUsuario(data)}
        title="Eliminar usuario: ({nombreCompleto})" 
        saveButtonText="Eliminar definitivamente" 
        closeButtonText="Cancelar">
        <div class="d-flex justify-content-center">
            <h1 class="input-group-text"><strong>ESTO NO SE PUEDE DESHACER</strong></h1>
        </div>
    </Modal>

    <Modal open={modRolAdministrativo} onClosed={(data) => resRolAdministrativo(data)}
        title="Roles de usuario: ({nombreCompleto})" 
        saveButtonText="Cambiar Rol" 
        closeButtonText="Cancelar">
        {#if administrativo_activo }
             <h5 class="text-center">??Quieres que {nombreCompleto} deje de ser Administrativo?</h5>
        {:else}
             <h5 class="text-center">??Quieres que {nombreCompleto} sea Administrativo?</h5>
        {/if}
    </Modal>

    <Modal open={modRolDocente} onClosed={(data) => resRolDocente(data)}
        title="Roles de usuario: ({nombreCompleto})" 
        saveButtonText="Cambiar Rol" 
        closeButtonText="Cancelar">
        {#if docente_activo }
             <h5 class="text-center">??Quieres que {nombreCompleto} deje de ser Docente?</h5>
        {:else}
             <h5 class="text-center">??Quieres que {nombreCompleto} sea Docente?</h5>
        {/if}
    </Modal>

    <Modal open={modRolEstudiante} onClosed={(data) => resRolEstudiante(data)}
        title="Roles de usuario: ({nombreCompleto})" 
        saveButtonText="Cambiar Rol" 
        closeButtonText="Cancelar">
        {#if estudiante_activo }
             <h5 class="text-center">??Quieres que {nombreCompleto} deje de ser Estudiante?</h5>
        {:else}
             <h5 class="text-center">??Quieres que {nombreCompleto} sea Estudiante?</h5>
        {/if}
    </Modal>

    <!-- DEBUG -->
    {#if debug}
        <div class="debug">
            <div class="input-group mb-3">
                <span class="input-group-text">codigoValido</span>
                <input type="text" class="form-control" bind:value={codigoValido}>
            </div>
        </div>
    {/if}

</main>

<style>
    .bloqueado {
        width: 100px;
    }
    .debeCambiarClave {
        width: 100px;
    }
    .codigoTemporal {
        width: 100px;
    }
    .navs {
        display: grid;
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