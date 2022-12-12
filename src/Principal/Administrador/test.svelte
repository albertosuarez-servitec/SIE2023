<script>
    // CON EXTRA
    import Spinner from "../../Componentes/Spinner.svelte";
    let spinner = false
    
    // SIN EXTRA
    import axios from "axios"
    import Lugar from "../../lugares"
    import Modal from "../../Componentes/Modal.svelte";
    import Swal from "sweetalert2"
    
    // RECORDSET INICIAL
    let tieneRegistros = false
    let rsRegistros = []
    const main = async () => {
        try {
            spinner = true
                const rs = await axios.post(Lugar.backend+'test.php',{
                    valor: "This is a test"
                })
            spinner = false
            if ( rs.data.tieneRegistros ) {
                tieneRegistros = true
                rsRegistros = Object.values(rs.data.rsRegistros)
            }

        } catch (e) {console.log(e)}
    }
    main()

    // FILTRADO
    let filtro = ''
    const filtrar = () => {
        
    }

    // PAGINADOR
    let maxRegsPP = 10
    let paginas = 0
    let paginaActual = 1
    let registroInicial = 1
    let registroFinal = 10
    const cambiarMaxRegsPP = () => {

    }
    const cambiarPagina = (pagina) => {
        paginaActual = pagina
        registroInicial = (paginaActual * maxRegsPP) - (maxRegsPP - 1)
        registroFinal = paginaActual * maxRegsPP
    }

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

    // EDITAR REGISTRO
    let id_test = 0
    let texto = ''
    
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

    const numeros = (string) => {
        let out = '';
        let filtro = '0123456789';
        for (let i=0; i<string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) 
            out += string.charAt(i);
        return parseInt(out)
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
                    <input type="text" class="form-control" placeholder="Escriba para filtrar" bind:value={filtro} on:input={filtrar}>
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

        <div class="input-group mb-3 barra-acciones">
            <button class="btn btn-success" type="button"><i class="bi bi-person-plus"></i> Agregar registro</button>
        </div>
        
        <div class="input-group mb-3 barra-navPaginas ">
            <div role="group">
                {#each Array(paginas) as _, i}
                    <button type="button" class="btn {paginaActual == i+1 ? 'btn-primary' : 'btn-light'}" on:click={()=>cambiarPagina(i+1)}>{i+1}</button>
                {/each}
            </div>
        </div>
        <div class="barra-usuarios">
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
                                        <div on:keydown={null} on:click={()=>editarRegistro(registro.id_test,registro.texto,registro.entero)}>
                                            <i class="bi bi-pencil-fill"></i>
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

    <Modal open={modEditarRegistro} onClosed={(data) => resEditarRegistro(data)}
        title="Editar registro:" 
        saveButtonText="Guardar registro" 
        closeButtonText="Cancelar">
        <div class="input-group mb-3">
            <span class="input-group-text">Texto:*</span>
            <input type="text" class="form-control" bind:value={texto} on:input={validarTexto}>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text">Entero:*</span>
            <input type="text" class="form-control" bind:value={entero} on:input={validarEntero}>
        </div>
    </Modal>

    <!-- DEBUG -->
    {#if debug}
        <div class="debug">
            <div class="input-group mb-3"><span class="input-group-text">debug</span><input type="text" class="form-control" bind:value={debug}></div>
        </div>
    {/if}

</main>

<style>
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