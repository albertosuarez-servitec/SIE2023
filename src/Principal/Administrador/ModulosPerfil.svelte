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
    import { push } from 'svelte-spa-router'
    
    // PAGINADOR
    let maxRegsPP = 10
    let paginas = 0
    let paginaActual = 1
    let registroInicial = 1
    let registroFinal = 10
    let residuo = 0
    let pagAd = 0
    let rsModulos = []
    let rsModulosDisponibles = []
    
    const cambiarMaxRegsPP = () => {
        paginas = Math.floor( rsModulos.length / maxRegsPP )
        residuo = ( rsModulos.length % maxRegsPP )
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
    let tieneModulos = false
    let tieneModulosDisponibles = false
    const main = async () => {
        try {
            spinner = true
                const rs = await axios.post(Lugar.backend+'modulos_perfil.php',{
                    id_perfil: sessionStorage.getItem( 'id_perfil_t')
                })
            spinner = false
            if ( rs.data.tieneModulos ) {
                tieneModulos = true
                rsModulos = Object.values(rs.data.rsModulos)
                paginas = Math.floor( rsModulos.length / maxRegsPP )
                residuo = ( rsModulos.length % maxRegsPP )
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
            if ( rs.data.tieneModulosDisponibles ) {
                tieneModulosDisponibles = true
                rsModulosDisponibles = Object.values(rs.data.rsModulosDisponibles)
                console.log(rsModulosDisponibles)
            }
        } catch (e) {}
    }
    main()

    // AGREGAR MÓDULO
    let modulo_nombre = ''
    let modAgregarModulo = false
    let disponibleSeleccionado = 0
    const agregarModulo = () => {
        modAgregarModulo = true
    }

    const resAgregarModulo = async (data) => {
        modAgregarModulo = false
        if (data == 'save'){
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'agregar_perfiles_modulos.php', {
                    fk_id_perfil: sessionStorage.getItem( 'id_perfil_t'),
                    fk_id_modulo: disponibleSeleccionado
                })
                spinner = false
                main()
            } catch (e) {}
        }
	}

    // QUITAR PERFIL
    let modQuitarModulo = false
    let id_perfil = 0
    let id_modulo = 0
    const quitarModulo = async (id_moduloT, modulo_nombreT) => {
        id_perfil = parseInt(sessionStorage.getItem('id_perfil_t'))
        id_modulo = id_moduloT
        modulo_nombre = modulo_nombreT
        modQuitarModulo = true
    }

    const resQuitarPerfil = async (data) => {
        modQuitarModulo = false
        console.log(data)
        if (data == 'save'){
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'quitar_perfiles_modulos.php', {
                    fk_id_perfil: id_perfil,
                    fk_id_modulo: id_modulo
                })
                spinner = false
            } catch (e) {}
        }
        main()
	}

    // FUNCIONES GENERALES
    const blackhole = () => {}

    const regresar = () => {
        push('/ControlPerfiles')
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
                <!-- <div class="input-group mb-3 barra-filtrar ">
                    <span class="input-group-text"><strong>Filtrar:</strong></span>
                    <input type="text" class="form-control" placeholder="Escriba para filtrar" bind:value={filtro}>
                    <button class="btn btn-outline-success" on:click={limpiarFiltro}>Limpiar</button>
                    <button class="btn btn-outline-success" on:click={main}>Buscar</button>
                </div> -->
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
                    <button class="btn btn-success" type="button" on:click={agregarModulo}><i class="bi bi-plus-circle"></i> Agregar módulo</button>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-xxl-3 navs">
                {#if tieneModulos }
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

            <div class="accordion" id="acordeonPerfil">
                { #if tieneModulos }
                    { #each rsModulos as modulo, i }
                        { #if modulo.numero >= registroInicial && modulo.numero <= registroFinal }
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{i+1}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{i+1}" >
                                        <div class="row" style="width: 100%;">
                                            <div class="col-4 col-md-4 col-lg-3">
                                                <strong>{ modulo.modulo_nombre }</strong>
                                            </div>
                                            <div class="col-8 col-md-8 col-lg-9">
                                                { modulo.modulo_descripcion }
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{i+1}" class="accordion-collapse collapse {i==0?'show':''}" aria-labelledby="heading{i+1}" data-bs-parent="#acordeonPerfil">
                                    <div class="quitar pointer shadow" on:keydown={blackhole} on:click={()=>quitarModulo(modulo.id_modulo,modulo.modulo_nombre)}>Quitar modulo</div>
                                    <!-- <div>
                                        <h5 class="asociados"><strong>Módulos asociados:</strong></h5>
                                        <hr style="margin:0px;">
                                    </div> -->
                                    <div class="accordion-body">
                                        <!-- { #if tieneModulos }
                                            { #each rsModulos as modulo, i }
                                                {#if modulo.id_perfil == modulo.id_perfil}
                                                    <div class="row">
                                                    <div class="col-4 col-md-4 col-lg-3">
                                                        <strong>{ modulo.modulo_nombre }</strong>
                                                    </div>
                                                    <div class="col-8 col-md-8 col-lg-9">
                                                        <strong>{ modulo.modulo_descripcion }</strong>
                                                    </div>
                                                    </div>
                                                {/if}
                                            { /each }
                                        { :else }
                                            <div class="card">
                                                <img src="..." alt='...'>
                                                <div class="card-body">
                                                <h5 class="card-title"><strong>Faltan datos</strong></h5>
                                                <p class="card-text">No se encontraron módulos para este modulo en la base de datos.</p>
                                                </div>
                                            </div>
                                        { /if } -->
                                    </div>
                                    <!-- <div>
                                        <h5 class="asociados2 shadow"><hr></h5>
                                    </div> -->
                                </div>
                            </div>
                        { /if }
                    { /each }
                { :else }

                    <div class="card">
                        <!-- <img src="..." alt='...'> -->
                        <div class="card-body">
                        <h5 class="card-title"><strong>Faltan datos</strong></h5>
                        <p class="card-text">No se encontraron perfiles para este usuario en la base de datos.</p>
                        </div>
                    </div>

                {/if}
            </div>
            
        </div>

        <div class="input-group mb-3 bg-light barra-regresar">
            <button class="btn btn-primary" type="button" on:click={ regresar }>Regresar</button>
        </div>
    </div>

    <Modal open={modAgregarModulo} onClosed={(data) => resAgregarModulo(data)}
        title="Agregar módulo:" 
        saveButtonText="Agregar módulo"
        closeButtonText="Cancelar">
        <table class="table">
            <thead>
                <tr>
                    <td><strong>MÓDULOS DISPONIBLES</strong></td>
                </tr>
            </thead>
            <tbody>
                {#each rsModulosDisponibles as disponible, i}
                    <tr class="pointer { disponibleSeleccionado == disponible.id_modulo ? 'seleccionado' : ''} sobre" 
                    on:click={ ()=> disponibleSeleccionado = disponible.id_modulo }>
                        <td>{ disponible.modulo_nombre }</td>
                    </tr>
                {/each}
            </tbody>
        </table>
    </Modal>

    <Modal open={modQuitarModulo} onClosed={(data) => resQuitarPerfil(data)}
        title="Quitar módulo: {modulo_nombre}" 
        saveButtonText="Quitar el módulo" 
        closeButtonText="Cancelar">
        <div>
            <p class="bg-danger text-light text-center" style="border-radius:15px;">Los cambios al perfil se reflejarán en todos los usuarios.</p>
        </div>
    </Modal>

    <!-- DEBUG -->
    {#if debug}
        <div class="debug">
            <div class="input-group mb-3">
                <span class="input-group-text">debug</span>
                <input type="text" class="form-control" bind:value={debug}>
            </div>
        </div>
    {/if}

</main>

<style>
    .accordion-body {
        padding-bottom: 0px;
    }
    .quitar {
        text-align: center;
        background-color: var(--rojo-3);
        margin-left: 40%;
        margin-right: 40%;
        border-radius: 0 0 15px 15px;
        color: var(--light);
        text-transform: uppercase;
    }
    .seleccionado, .sobre:hover {
        background-color: var(--amarillo-anaranjado-1) !important;
        color: var(--dark) !important;
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