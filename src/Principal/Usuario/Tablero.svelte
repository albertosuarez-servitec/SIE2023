<script>
    // LIBRERIAS O COMPONENTES CON VARIABLE EXTRA
    import Spinner from "../../Componentes/Spinner.svelte";
    let spinner = false
    
    // LIBRERIAS O COMPONENTES SIN VARIABLE EXTRA
    import axios from "axios"
    import Lugar from "../../lugares"
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
    let rsModulos = []

    // SESIÓN ACTIVA
    let usuarioActual_nombre = ''
    let usuarioActual_id = ''
    let enSesion = false
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
                } else {
                    sessionStorage.clear()
                }
            } catch (e) {}
        } 
    }
    sesion()

    // RECORDSET INICIAL
    let tienePerfiles = false
    let tieneModulos = false
    const main = async () => {
        try {
            spinner = true
                const rs = await axios.post(Lugar.backend+'tableros.php')
            spinner = false
            if ( rs.data.tienePerfiles ) {
                tienePerfiles = true
                rsPerfiles = Object.values( rs.data.rsPerfiles )
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
                    rsModulos = Object.values( rs.data.rsModulos )
                }
            }
        } catch (e) {}
    }
    main()

</script>

<main>
    { #each rsPerfiles as perfil, i }
        <div class="card">
            <div class="card-header shadow"><strong>PERFIL:</strong> { perfil.perfil_nombre }</div>
            <div class="card-body perfiles-body">
                <h5 class="card-title modulos-disponibles shadow">MÓDULOS DISPONIBLES</h5>
                    <div class="row">
                        {#each rsModulos as modulo, i}
                            {#if modulo.id_perfil == perfil.id_perfil }
                                 <div class="col-3">
                                     <div class="card card-modulo shadow">
                                         <div class="card-body body-modulo">
                                             <h5 class="card-title"><strong>{ modulo.modulo_nombre }</strong></h5>
                                             <p class="card-text text-modulo">{ modulo.modulo_descripcion }</p>
                                             <button class="btn btn-primary abrir-modulo" on:click={ ()=> push( "/"+modulo.modulo_pagina ) }>Abrir módulo</button>
                                         </div>
                                     </div>
                                 </div>
                            {/if}
                        {/each}
                    </div>
                <h5 class="modulos shadow"><span></span></h5>
            </div>
        </div>
    { /each }
</main>

<style>
    .perfiles-body {
        padding-bottom: 0px;
    }
    .abrir-modulo {
        border-radius: 15px;
    }
    .text-modulo {
        height: 50px;
    }
    .card-modulo {
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .modulos {
        background-color: var(--blue-100);
        border-radius: 0 0 15px 15px;
        height: 1rem;
        margin-bottom: 1rem;
    }
    .modulos-disponibles {
        background-color: var(--blue-100);
        border-radius: 15px 15px 0 0;
        padding-left: 10px;
        margin-bottom: 0px;
        text-align: center;
        font-weight: bolder;
    }
    .card-header {
        background-color: var(--azul-5);
        color: var(--light);
    }
</style>