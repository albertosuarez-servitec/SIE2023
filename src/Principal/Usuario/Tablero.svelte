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
    let rsPerfiles = []

    // RECORDSET INICIAL
    let tienePerfiles = false
    const main = async () => {
        try {
            spinner = true
                const rs = await axios.post(Lugar.backend+'perfiles_permisos.php')
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
            }
        } catch (e) {}
    }
    main()

</script>

<main>
    { #each rsPerfiles as perfil, i }
        <div class="card">
            <div class="card-header">{ perfil.perfil_nombre }</div>
            <div class="card-body">
            <h5 class="card-title">Special title treatment</h5>
            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
            <a href="!#" class="btn btn-primary">Go somewhere</a>
            </div>
        </div>
    { /each }
</main>

<style>
    .card-header {
        background-color: var(--azul-5);
        color: var(--light);
    }
</style>