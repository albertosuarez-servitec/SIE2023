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
    const cambiarMaxRegsPP = () => {
        paginas = ( rsRegistros.length / maxRegsPP )
        residuo = ( rsRegistros.length % maxRegsPP )
        if ( residuo > 0 ) {
            pagAd = 1
        } else {
            pagAd = 0
        }
        paginas = parseInt(paginas) + pagAd
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
    let rsRegistros = []
    const main = async () => {
        try {
            spinner = true
                const rs = await axios.post(Lugar.backend+'test.php',{
                    filtro: filtro
                })
            spinner = false
            if ( rs.data.tieneRegistros ) {
                tieneRegistros = true
                rsRegistros = Object.values(rs.data.rsRegistros)
                paginas = ( rsRegistros.length / maxRegsPP )
                residuo = ( rsRegistros.length % maxRegsPP )
                if ( residuo > 0 ) {
                    pagAd = 1
                } else {
                    pagAd = 0
                }
                paginas = parseInt(paginas) + pagAd
                paginaActual = 1
                registroInicial = 1
                registroFinal = maxRegsPP
            }
        } catch (e) {console.log(e)}
    }
    main()

    // BULEANO
    const cambiarBuleano = async (id_testT,buleanoT) => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'cambiar_buleano.php',{
                id_test:id_testT,
                buleano:buleanoT
            })
            spinner = false
        } catch (e) {}
    }

    // AGREGAR REGISTRO
    let id_test = 0
    let texto = ''
    let modAgregarRegistro = false
    const agregarRegistro = () => {
        texto = ''
        entero = ''
        textoValido = false
        enteroValido = false
        modAgregarRegistro = true
    }

    const resAgregarRegistro = async (data) => {
        texto = texto.trim()
        modAgregarRegistro = false
        if (data == 'save'){
            if ( textoValido && enteroValido ) {
                try {
                    spinner = true
                    const rs = await axios.post(Lugar.backend+'agregar_registro_tests.php', {
                        id_test: id_test,
                        texto: texto,
                        entero: entero
                    })
                    spinner = false
                    main()
                } catch (e) {}
            } else {
                Swal.fire({icon: 'error',title: 'Faltan datos',text: 'El largo del texto debe tener al menos 3 caracteres y el entero debe ser mayor a 0.'})
            }
        }
	}

    // EDITAR REGISTRO
    let entero = 0
    let modEditarRegistro = false
    const editarRegistro = (id_testT,textoT,enteroT) => {
        id_test = id_testT
        texto = textoT
        entero = enteroT
        modEditarRegistro = true
    }

    let textoValido = true
    const validarTexto = () => {
        if ( texto.length > 2 ) {
            textoValido = true
        } else {
            textoValido = false
        }
    }

    let enteroValido = true
    const validarEntero = () => {
        entero = numeros(entero)
        if ( texto.length > 2 ) {
            enteroValido = true
        } else {
            enteroValido = false
        }
    }

    const resEditarRegistro = async (data) => {
        texto = texto.trim()
        modEditarRegistro = false
        if (data == 'save'){
            if ( textoValido && enteroValido ) {
                try {
                    spinner = true
                    const rs = await axios.post(Lugar.backend+'actualizar_tests.php', {
                        id_test: id_test,
                        texto: texto,
                        entero: entero
                    })
                    spinner = false
                    main()
                } catch (e) {}
            } else {
                Swal.fire({icon: 'error',title: 'Faltan datos',text: 'El largo del texto debe tener al menos 3 caracteres y el entero debe ser mayor a 0.'})
            }
        }
	}

    // ELIMINAR REGISTRO
    let modEliminarRegistro = false
    const eliminarRegistro = async (id_testT,textoT) => {
        id_test = id_testT
        texto = textoT
        modEliminarRegistro = true
    }

    const resEliminarRegistro = async (data) => {
        modEliminarRegistro = false
        if (data == 'save'){
            try {
                spinner = true
                const rs = await axios.post(Lugar.backend+'eliminar_registro_test.php',{
                    id_test: id_test
                })
                spinner = false
            } catch (e) {}
            main()
        }
	}

    // FUNCIONES GENERALES
    const numeros = (string) => {
        let out = '';
        let filtro = '0123456789';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out += string.charAt(i);
        return parseInt(out)
    }

    const limpiarFiltro = () => {
        filtro = ''
        main()
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
            </div>
        </div>
        
        <div class="barra-registros">
            <table class="table table-hover bg-light table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Acciones</th>
                        <th scope="col">Texto</th>
                        <th scope="col">Entero</th>
                        <th scope="col">Buleano</th>
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
                                            <i class="bi bi-pencil-fill text-warning" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="right" 
                                                title="Editar registro" 
                                                style="font-size:large;"
                                                on:keydown={null} on:click={()=>editarRegistro(registro.id_test,registro.texto,registro.entero)}>
                                            </i>
                                            <i class="bi bi-trash-fill text-danger" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="right" 
                                                title="Editar registro" 
                                                style="font-size:large;"
                                                on:keydown={null} on:click={()=>eliminarRegistro(registro.id_test,registro.texto)}>
                                            </i>
                                        </div>
                                    </th>
                                    <td             >{registro.texto}</td>
                                    <td             >{registro.entero}</td>
                                    <td             >
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" bind:checked={registro.buleano}
                                                on:change={()=>cambiarBuleano(registro.id_test,registro.buleano)}>
                                        </div>
                                    </td>
                                </tr>   
                            {/if}
                        {/each}
                    {:else}
                        <td colspan="5">
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
            <span class="input-group-text"><strong>Texto:*</strong></span>
            <input type="text" class="form-control" bind:value={texto} on:input={validarTexto}>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text"><strong>Entero:*</strong></span>
            <input type="text" class="form-control" bind:value={entero} on:input={validarEntero}>
        </div>
    </Modal>

    <Modal open={modEditarRegistro} onClosed={(data) => resEditarRegistro(data)}
        title="Editar registro:" 
        saveButtonText="Guardar registro" 
        closeButtonText="Cancelar">
        <div class="input-group mb-3">
            <span class="input-group-text"><strong>Texto:*</strong></span>
            <input type="text" class="form-control" bind:value={texto} on:input={validarTexto}>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text"><strong>Entero:*</strong></span>
            <input type="text" class="form-control" bind:value={entero} on:input={validarEntero}>
        </div>
    </Modal>

    <Modal open={modEliminarRegistro} onClosed={(data) => resEliminarRegistro(data)}
        title="Eliminar registro:" 
        saveButtonText="Eliminar registro" 
        closeButtonText="Cancelar">
        <h3 class="text-center">¿Eliminar el siguiente registro?</h3>
        <p class="text-center text-primary"><strong>{texto}</strong></p>
        <p class="text-center">Esto no se podrá revertir</p>
    </Modal>

    <!-- DEBUG -->
    {#if debug}
        <div class="debug">
            <div class="input-group mb-3">
                <span class="input-group-text">maxRegsPP</span>
                <input type="text" class="form-control" bind:value={maxRegsPP}>
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