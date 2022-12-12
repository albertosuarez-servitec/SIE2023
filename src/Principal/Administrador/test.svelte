<script>
    // CON EXTRA
    import Spinner from "../../Componentes/Spinner.svelte";
    let spinner = false
    
    // SIN EXTRA
    import axios from "axios"
    import Lugar from "../../lugares"
    
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
                                    <th             ><i class="bi bi-pencil-fill"></i></th>
                                    <td             >{registro.texto}</td>
                                    <td             >{registro.entero}</td>
                                    <td             >{registro.buleano}</td>
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