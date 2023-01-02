<script>
    // LIBRERIAS O COMPONENTES CON VARIABLE EXTRA
    import Spinner from "../../Componentes/Spinner.svelte";
    let spinner = false
    
    // LIBRERIAS O COMPONENTES SIN VARIABLE EXTRA
    import axios from "axios"
    import Lugar from "../../lugares"
    import Modal from "../../Componentes/Modal.svelte";
    import Swal from "sweetalert2"
    
    // PAGINADOR
    let maxRegsPP = 10
    let paginas = 0
    let paginaActual = 1
    let registroInicial = 1
    let registroFinal = 10
    let residuo = 0
    let pagAd = 0
    let rsRegistros = []
    
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
    let minimo_orden = 0
    let maximo_orden = 0
    const main = async () => {
        try {
            spinner = true
                const rs = await axios.post(Lugar.backend+'control_menu.php',{
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
                minimo_orden = rs.data.minimo_orden
                maximo_orden = rs.data.maximo_orden
            }
        } catch (e) {
            //console.log(e)
        }
    }
    main()

    // ACTIVO
    const cambiarMenu_Activo = async (id_menuT,menu_activoT) => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'cambiar_menu_activo.php',{
                id_menu:id_menuT,
                menu_activo:menu_activoT
            })
            spinner = false
        } catch (e) {}
    }

    // MOSTRAR
    const cambiarMenu_Mostrar = async (id_menuT,menu_mostrarT) => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'cambiar_menu_mostrar.php',{
                id_menu:id_menuT,
                menu_mostrar:menu_mostrarT
            })
            spinner = false
        } catch (e) {}
    }

    // AGREGAR REGISTRO
    let id_menu = 0
    let menu_nombre = ''
    let modAgregarRegistro = false
    const agregarRegistro = () => {
        menu_nombre = ''
        menu_nombreValido = false
        modAgregarRegistro = true
    }

    const resAgregarRegistro = async (data) => {
        menu_nombre = menu_nombre.trim()
        modAgregarRegistro = false
        if (data == 'save'){
            if ( menu_nombreValido ) {
                try {
                    spinner = true
                    const rs = await axios.post(Lugar.backend+'agregar_registro_menu.php', {
                        menu_nombre: menu_nombre
                    })
                    spinner = false
                    main()
                } catch (e) {}
            } else {
                Swal.fire({icon: 'error',title: 'Faltan datos',text: 'El largo del nombre del menú debe tener al menos 3 caracteres.'})
            }
        }
	}

    // EDITAR REGISTRO
    let modEditarRegistro = false
    const editarRegistro = (id_menuT,menu_nombreT) => {
        id_menu = id_menuT
        menu_nombre = menu_nombreT
        modEditarRegistro = true
    }

    let menu_nombreValido = true
    const validarMenu_Nombre = () => {
        if ( menu_nombre.length > 2 ) {
            menu_nombreValido = true
        } else {
            menu_nombreValido = false
        }
    }

    const resEditarRegistro = async (data) => {
        menu_nombre = menu_nombre.trim()
        modEditarRegistro = false
        if (data == 'save'){
            if ( menu_nombreValido ) {
                try {
                    spinner = true
                    const rs = await axios.post(Lugar.backend+'actualizar_menu.php', {
                        id_menu: id_menu,
                        menu_nombre: menu_nombre
                    })
                    spinner = false
                    main()
                } catch (e) {}
            } else {
                Swal.fire({icon: 'error',title: 'Faltan datos',text: 'El largo del nombre del menú debe tener al menos 3 caracteres.'})
            }
        }
	}

    // ELIMINAR REGISTRO
    let modEliminarRegistro = false
    const eliminarRegistro = async (id_menuT,menu_nombreT) => {
        id_menu = id_menuT
        menu_nombre = menu_nombreT
        modEliminarRegistro = true
    }

    const resEliminarRegistro = async (data) => {
        modEliminarRegistro = false
        if (data == 'save'){
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'eliminar_registro_menu.php',{
                    id_menu: id_menu
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
    const bajar = async (id_menuT,menu_ordenT) => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'cambiar_orden.php',{
                id_menu: id_menuT,
                menu_orden: menu_ordenT,
                direccion: 'b'
            })
            spinner = false
            main()
        } catch (e) {}
    }

    const subir = async (id_menuT,menu_ordenT) => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'cambiar_orden.php',{
                id_menu: id_menuT,
                menu_orden: menu_ordenT,
                direccion: 's'
            })
            spinner = false
            main()
        } catch (e) {}
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
                    <button class="btn btn-success" type="button" on:click={agregarRegistro}><i class="bi bi-plus-circle"></i> Agregar registro</button>
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
                        <th scope="col">Menú</th>
                        <th class="text-center" scope="col">Orden</th>
                        <th class="text-center" scope="col">Activo</th>
                        <th class="text-center" scope="col">Mostrar</th>
                        <th scope="col">Destino</th>
                        <th scope="col">Ruta</th>
                    </tr>
                </thead>
                <tbody >
                    {#if tieneRegistros}
                        {#each rsRegistros as registro}
                            {#if registro.numero >= registroInicial && registro.numero <= registroFinal}
                                <tr>
                                    <th scope="row" >{registro.numero}</th>
                                    <th             >
                                        <div >
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <i class="bi bi-pencil-fill text-warning" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="right" 
                                                title="Editar registro" 
                                                style="font-size:large;"
                                                on:click={()=>editarRegistro(registro.id_menu,registro.menu_nombre)}>
                                            </i>
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <i class="bi bi-trash-fill text-danger" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="right" 
                                                title="Eliminar registro" 
                                                style="font-size:large;"
                                                on:click={()=>eliminarRegistro(registro.id_menu,registro.menu_nombre)}>
                                            </i>
                                            <i class="bi bi-subtract text-secondary" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="right" 
                                                title="Sin submenú" 
                                                style="font-size:large;">
                                            </i>
                                        </div>
                                    </th>
                                    <td             >{registro.menu_nombre}</td>
                                    <td class="orden">
                                        <div class="row">
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <div class="col-6" on:click={()=>bajar(registro.id_menu,registro.menu_orden)}>
                                                {#if registro.menu_orden != maximo_orden}
                                                     <i style="font-size:1.5rem;" class="text-primary bi bi-caret-down-fill pointer"></i>
                                                {/if}
                                            </div>
                                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                                            <div class="col-6" on:click={()=>subir(registro.id_menu,registro.menu_orden)}>
                                                {#if registro.menu_orden != minimo_orden}
                                                     <i style="font-size:1.5rem;" class="text-primary bi bi-caret-up-fill pointer"></i>
                                                {/if}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="activo">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" role="switch" bind:checked={registro.menu_activo}
                                            on:change={()=>cambiarMenu_Activo(registro.id_menu,registro.menu_activo)}>
                                        </div>
                                    </td>
                                    <td class="mostrar">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" role="switch" bind:checked={registro.menu_mostrar}
                                            on:change={()=>cambiarMenu_Mostrar(registro.id_menu,registro.menu_mostrar)}>
                                        </div>
                                    </td>
                                    <td             >{registro.menu_destino}</td>
                                    <td             >{registro.menu_ruta}</td>
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
            <button class="btn btn-primary" type="button">Regresar</button>
        </div>
    </div>

    <Modal open={modAgregarRegistro} onClosed={(data) => resAgregarRegistro(data)}
        title="Agregar registro:" 
        saveButtonText="Guardar registro" 
        closeButtonText="Cancelar">
        <div class="input-group mb-3">
            <span class="input-group-text"><strong>Nombre del menú:*</strong></span>
            <input type="text" class="form-control" bind:value={menu_nombre} on:input={validarMenu_Nombre}>
        </div>
    </Modal>

    <Modal open={modEditarRegistro} onClosed={(data) => resEditarRegistro(data)}
        title="Editar registro:" 
        saveButtonText="Guardar registro" 
        closeButtonText="Cancelar">
        <div class="input-group mb-3">
            <span class="input-group-text"><strong>Menú:*</strong></span>
            <input type="text" class="form-control" bind:value={menu_nombre} on:input={validarMenu_Nombre}>
        </div>
    </Modal>

    <Modal open={modEliminarRegistro} onClosed={(data) => resEliminarRegistro(data)}
        title="Eliminar registro:" 
        saveButtonText="Eliminar registro" 
        closeButtonText="Cancelar">
        <h3 class="text-center">¿Eliminar el siguiente menú?</h3>
        <p class="text-center text-primary"><strong>{menu_nombre}</strong></p>
        <p class="text-center">Esto no se podrá revertir</p>
    </Modal>

    <!-- DEBUG -->
    {#if debug}
        <div class="debug">
            <div class="input-group mb-3">
                <span class="input-group-text">tieneRegistros</span>
                <input type="text" class="form-control" bind:value={tieneRegistros}>
            </div>
        </div>
    {/if}

</main>

<style>
    .mostrar {
        width: 100px;
    }
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