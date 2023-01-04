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
    
    // PAGINADOR
    let maxRegsPP = 10
    let paginas = 0
    let paginaActual = 1
    let registroInicial = 1
    let registroFinal = 10
    let residuo = 0
    let pagAd = 0
    let rsPerfiles = []
    let rsModulos = []
    
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
    let tieneModulos = false
    const main = async () => {
        try {
            spinner = true
                const rs = await axios.post(Lugar.backend+'perfiles_usuario.php',{
                    filtro: filtro,
                    id_usuario: sessionStorage.getItem( 'id_usuario_p')
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
                if ( rs.data.tieneModulos ) {
                    tieneModulos = true
                    rsModulos = Object.values(rs.data.rsModulos)
                }
            }
        } catch (e) {}
    }
    main()

    // AGREGAR PERFIL
    let perfil_nombre = ''
    let perfil_descripcion = ''
    let modAgregarPerfil = false
    const agregarPerfil = () => {
        modAgregarPerfil = true
    }

    const resAgregarPerfil = async (data) => {
        modAgregarPerfil = false
        if (data == 'save'){
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'agregar_usuarios_perfil.php', {
                    fk_id_usuario: sessionStorage.getItem( 'id_usuario_p'),
                    fk_id_perfil: id_perfil,
                })
                spinner = false
                main()
            } catch (e) {}
        }
	}

    // QUITAR PERFIL
    let modQuitarPerfil = false
    let id_usuario = 0
    let id_perfil = 0
    const quitarPerfil = async (id_perfilT, perfil_nombreT) => {
        id_usuario = sessionStorage.getItem('id_usuario_p')
        id_perfil = id_perfilT
        perfil_nombre = perfil_nombreT
        modQuitarPerfil = true
    }

    const resQuitarPerfil = async (data) => {
        modQuitarPerfil = false
        console.log(data)
        if (data == 'save'){
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'quitar_perfil_usuario.php', {
                    id_usuario: id_usuario,
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

            <div class="accordion" id="acordeonPerfil">
                { #if tienePerfiles }
                    { #each rsPerfiles as perfil, i }
                        { #if perfil.numero >= registroInicial && perfil.numero <= registroFinal }
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{i+1}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{i+1}" >
                                        <div class="row" style="width: 100%;">
                                            <div class="col-4 col-md-4 col-lg-3">
                                                <strong>{ perfil.perfil_nombre }</strong>
                                            </div>
                                            <div class="col-8 col-md-8 col-lg-9">
                                                { perfil.perfil_descripcion }
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{i+1}" class="accordion-collapse collapse {i==0?'show':''}" aria-labelledby="heading{i+1}" data-bs-parent="#acordeonPerfil">
                                    <div class="accordion-body">
                                        <!-- svelte-ignore a11y-click-events-have-key-events -->
                                        <i class="bi bi-trash-fill text-danger pointer" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="right" 
                                            title="Quitar perfil" 
                                            style="font-size:large;"
                                            on:click={()=>quitarPerfil(perfil.id_perfil,perfil.perfil_nombre)}>
                                        </i>
                                        { #if tieneModulos }
                                            { #each rsModulos as modulo, i }
                                                {#if perfil.id_perfil == modulo.id_perfil}
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
                                                <!-- <img src="..." alt='...'> -->
                                                <div class="card-body">
                                                <h5 class="card-title"><strong>Faltan datos</strong></h5>
                                                <p class="card-text">No se encontraron módulos para este perfil en la base de datos.</p>
                                                </div>
                                            </div>
                                        { /if }
                                    </div>
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
            <button class="btn btn-primary" type="button">Regresar</button>
        </div>
    </div>

    <Modal open={modAgregarPerfil} onClosed={(data) => resAgregarPerfil(data)}
        title="Agregar registro:" 
        saveButtonText="Guardar registro"
        closeButtonText="Cancelar">

    </Modal>
    
    <Modal open={modQuitarPerfil} onClosed={(data) => resQuitarPerfil(data)}
        title="Quitar perfil: {perfil_nombre}" 
        saveButtonText="Quitar el perfil" 
        closeButtonText="Cancelar">
        <div>
            <p class="bg-danger text-light text-center" style="border-radius:15px;">Al quitar el perfil también se desasociarán los módulos de dicho perfil asignados al usuario.</p>
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