<script>
    // LIBRERIAS O COMPONENTES CON VARIABLE EXTRA
    import Spinner from "../../Componentes/Spinner.svelte"
    let spinner = false
    
    // LIBRERIAS O COMPONENTES SIN VARIABLE EXTRA
    import axios from "axios"
    import Lugar from "../../lugares"
    import Modal from "../../Componentes/Modal.svelte"
    import Swal from "sweetalert2"
    import { push } from "svelte-spa-router"
    
    // PAGINADOR
    let maxRegsPP = 10
    let paginas = 0
    let paginaActual = 1
    let registroInicial = 1
    let registroFinal = 10
    let residuo = 0
    let pagAd = 0
    let rsPerfiles = []
    
    const cambiarMaxRegsPP = () => {
        paginas = Math.floor( rsPerfiles.length / maxRegsPP )
        residuo = ( rsPerfiles.length % maxRegsPP )
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
    let tienePerfiles = false
    let minimo_orden = 0
    let maximo_orden = 0
    const main = async () => {
        try {
            spinner = true
                const rs = await axios.post(Lugar.backend+'control_perfiles.php',{
                    filtro: filtro
                })
            spinner = false
            if ( rs.data.tienePerfiles ) {
                tienePerfiles = true
                rsPerfiles = Object.values(rs.data.rsPerfiles)
                paginas = Math.floor( rsPerfiles.length / maxRegsPP )
                residuo = ( rsPerfiles.length % maxRegsPP )
                if ( residuo > 0 ) {
                    pagAd = 1
                } else {
                    pagAd = 0
                }
                paginas = paginas + pagAd
                paginaActual = 1
                registroInicial = 1
                registroFinal = maxRegsPP
                minimo_orden = rs.data.minimo_orden
                maximo_orden = rs.data.maximo_orden
            }
        } catch (e) {
            //console.log(e)
        }
    }
    main()

    // ACTIVO
    const cambiarPerfil_Activo = async (id_perfilT,perfil_activoT) => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'cambiar_perfil_activo.php',{
                id_perfil:id_perfilT,
                perfil_activo:perfil_activoT
            })
            spinner = false
        } catch (e) {}
    }

    // AGREGAR PERFIL
    let id_perfil = 0
    let perfil_nombre = ''
    let perfil_descripcion = ''
    let modAgregarPerfil = false
    const agregarPerfil = () => {
        perfil_nombre = ''
        perfil_nombreValido = false
        modAgregarPerfil = true
    }

    const resAgregarPerfil = async (data) => {
        perfil_nombre = perfil_nombre.trim()
        modAgregarPerfil = false
        if (data == 'save'){
            if ( perfil_nombreValido ) {
                try {
                    spinner = true
                    const rs = await axios.post(Lugar.backend+'agregar_registro_perfiles.php', {
                        perfil_nombre: perfil_nombre
                    })
                    spinner = false
                    main()
                } catch (e) {}
            } else {
                Swal.fire({icon: 'error',title: 'Faltan datos',text: 'El largo del nombre del perfil debe tener al menos 3 caracteres.'})
            }
        }
	}

    // EDITAR PERFIL
    let modEditarPerfil = false
    const editarPerfil = (id_perfilT,perfil_nombreT,perfil_descripcionT) => {
        id_perfil = id_perfilT
        perfil_nombre = perfil_nombreT
        perfil_descripcion = perfil_descripcionT
        modEditarPerfil = true
    }

    let perfil_nombreValido = true
    const validarperfil_Nombre = () => {
        if ( perfil_nombre.length > 2 ) {
            perfil_nombreValido = true
        } else {
            perfil_nombreValido = false
        }
    }

    const resEditarRegistro = async (data) => {
        perfil_nombre = perfil_nombre.trim()
        modEditarPerfil = false
        if (data == 'save'){
            if ( perfil_nombreValido ) {
                try {
                    spinner = true
                    const rs = await axios.post(Lugar.backend+'actualizar_perfil.php', {
                        id_perfil: id_perfil,
                        perfil_nombre: perfil_nombre,
                        perfil_descripcion: perfil_descripcion
                    })
                    spinner = false
                    main()
                } catch (e) {}
            } else {
                Swal.fire({icon: 'error',title: 'Faltan datos',text: 'El nombre del perfil debe tener al menos 3 caracteres.'})
            }
        }
	}

    // ELIMINAR PERFIL
    let modEliminarPerfil = false
    const eliminarPerfil = async (id_perfilT,perfil_nombreT) => {
        id_perfil = id_perfilT
        perfil_nombre = perfil_nombreT
        modEliminarPerfil = true
    }

    const resEliminarPerfil = async (data) => {
        modEliminarPerfil = false
        if (data == 'save'){
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'eliminar_registro_perfil.php',{
                    id_perfil: id_perfil
                })
                spinner = false
            } catch (e) {}
            main()
        }
	}

    // FUNCIONES GENERALES
    const limpiarFiltro = () => {
        filtro = ''
        main()
    }

    // ORDEN
    const bajar = async (id_perfilT,perfil_ordenT) => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'cambiar_orden_perfiles.php',{
                id_perfil: id_perfilT,
                perfil_orden: perfil_ordenT,
                direccion: 'b'
            })
            spinner = false
            main()
        } catch (e) {}
    }

    const subir = async (id_perfilT,perfil_ordenT) => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'cambiar_orden_perfiles.php',{
                id_perfil: id_perfilT,
                perfil_orden: perfil_ordenT,
                direccion: 's'
            })
            spinner = false
            main()
        } catch (e) {}
    }

    // MODULOS DEL PERFIL
    const modulosPerfil = async (id_perfilT, perfil_nombreT) => {
        sessionStorage.setItem( 'id_perfil_t', id_perfilT )
        sessionStorage.setItem( 'perfil_nombre_t', perfil_nombreT)
        push( '/ModulosPerfil' )
    }

    // DEBUG
    let debug = true

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
                    <span class="input-group-text"><strong>Registros por página:</strong></span>
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
                    <button class="btn btn-success" type="button" on:click={agregarPerfil}><i class="bi bi-plus-circle"></i> Agregar perfil</button>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-xxl-3 navs">
                {#if tienePerfiles }
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
                        <th scope="col">Perfil</th>
                        <th scope="col">Descripción</th>
                        <th class="text-center" scope="col">Orden</th>
                        <th class="text-center" scope="col">Activo</th>
                    </tr>
                </thead>
                <tbody >
                    {#if tienePerfiles}
                        {#each rsPerfiles as perfil}
                            {#if perfil.numero >= registroInicial && perfil.numero <= registroFinal}
                                <tr>
                                    <th scope="row" >{perfil.numero}</th>
                                    <th>
                                        <div>
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <i class="bi bi-pencil-fill text-warning" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="right" 
                                                title="Editar perfil" 
                                                style="font-size:large;"
                                                on:click={()=>editarPerfil(perfil.id_perfil,perfil.perfil_nombre,perfil.perfil_descripcion)}>
                                            </i>
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <i class="bi bi-trash-fill text-danger" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="right" 
                                                title="Eliminar perfil" 
                                                style="font-size:large;"
                                                on:click={()=>eliminarPerfil(perfil.id_perfil,perfil.perfil_nombre)}>
                                            </i>
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <i class="bi bi-card-checklist text-success pointer" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="right" 
                                                title="Módulos del perfil" 
                                                style="font-size:large;"
                                                on:click={()=>modulosPerfil(perfil.id_perfil, perfil.perfil_nombre)}>
                                            </i>
                                        </div>
                                    </th>
                                    <td>{perfil.perfil_nombre}</td>
                                    <td>{perfil.perfil_descripcion}</td>
                                    <td class="orden">
                                        <div class="row">
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <div class="col-6" on:click={()=>bajar(perfil.id_perfil,perfil.perfil_orden)}>
                                                {#if perfil.perfil_orden != maximo_orden}
                                                     <i style="font-size:1.5rem;" class="text-primary bi bi-caret-down-fill pointer"></i>
                                                {/if}
                                            </div>
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <div class="col-6" on:click={()=>subir(perfil.id_perfil,perfil.perfil_orden)}>
                                                {#if perfil.perfil_orden != minimo_orden}
                                                     <i style="font-size:1.5rem;" class="text-primary bi bi-caret-up-fill pointer"></i>
                                                {/if}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="activo">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" role="switch" bind:checked={perfil.perfil_activo}
                                            on:change={()=>cambiarPerfil_Activo(perfil.id_perfil,perfil.perfil_activo)}>
                                        </div>
                                    </td>
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
                                    <p class="card-text">No se encontró información en la base de datos.</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    {/if}
                </tbody>
            </table>
        </div>

        <div class="input-group mb-3 bg-light barra-regresar">
            <button class="btn btn-primary" type="button" on:click={ ()=> push( "/Tablero" ) }>Regresar</button>
        </div>
    </div>

    <Modal open={modAgregarPerfil} onClosed={(data) => resAgregarPerfil(data)}
        title="Agregar perfil:" 
        saveButtonText="Guardar perfil" 
        closeButtonText="Cancelar">
        <div class="input-group mb-3">
            <span class="input-group-text"><strong>Nombre del perfil:*</strong></span>
            <input type="text" class="form-control" bind:value={perfil_nombre} on:input={validarperfil_Nombre}>
        </div>
    </Modal>

    <Modal open={modEditarPerfil} onClosed={(data) => resEditarRegistro(data)}
        title="Editar perfil:" 
        saveButtonText="Guardar perfil" 
        closeButtonText="Cancelar">
        <div class="input-group mb-3">
            <span class="input-group-text"><strong>Perfil:*</strong></span>
            <input type="text" class="form-control" bind:value={perfil_nombre} on:input={validarperfil_Nombre}>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text"><strong>Descripción:*</strong></span>
            <textarea class="form-control" bind:value={perfil_descripcion}></textarea>
        </div>
    </Modal>

    <Modal open={modEliminarPerfil} onClosed={(data) => resEliminarPerfil(data)}
        title="Eliminar perfil:" 
        saveButtonText="Eliminar perfil" 
        closeButtonText="Cancelar">
        <h3 class="text-center">¿Eliminar el siguiente perfil?</h3>
        <p class="text-center text-primary"><strong>{perfil_nombre}</strong></p>
        <p class="text-center">Esto no se podrá revertir</p>
    </Modal>

    <!-- DEBUG -->
    {#if debug}
        <div class="debug">
            <div class="input-group mb-3">
                <span class="input-group-text">tienePerfiles</span>
                <input type="text" class="form-control" bind:value={tienePerfiles}>
            </div>
        </div>
    {/if}

</main>

<style>
    .activo {
        width: 100px;
    }
    .orden {
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