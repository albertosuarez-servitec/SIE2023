<script>
    // @ts-nocheck
    // LIBRERIAS O COMPONENTES CON VARIABLE EXTRA
    import Spinner from "../../Componentes/Spinner.svelte";
    let spinner = false

    // LIBRERIAS O COMPONENTES SIN VARIABLE EXTRA
    import { push, location } from 'svelte-spa-router'
    import Lugar from "../../lugares"
    import Modal from "../../Componentes/Modal.svelte";
    import Swal from "sweetalert2"
    import axios from "axios";

    let rsBanners = []
    let tieneBanners = false
    const banners = async () => {
        try {
            spinner = true
            const rs = await axios.post(Lugar.backend+'banners.php')
            spinner = false
            if ( rs.data.tieneRegistros ) {
                tieneBanners = true
                rsBanners = Object.values(rs.data.rsRegistros)
            }
        } catch (e) {}
    }
    banners()
</script>

<main>

    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            {#each rsBanners as banner, i}
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to={i} class="{i == 0 ? 'active' : ''}" aria-current="{i == 0 ? 'true' : ''}" aria-label="Slide {i+1}"></button>
            {/each}
        </div>
        <div class="carousel-inner">
            {#each rsBanners as banner, i}
                <div class="carousel-item {i == 0 ? 'active' : ''}">
                    <img src={Lugar.banners+banner.banner_imagen} class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>{banner.banner_titulo}</h5>
                        <p>{banner.banner_descripcion}</p>
                    </div>
                </div>
            {/each}

        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <i class="bi bi-arrow-left-square-fill"></i>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <i class="bi bi-arrow-right-square-fill"></i>
        </button>
    </div>

</main>

<style>
    .carousel-control-prev, .carousel-control-next {
        font-size: 2rem;
        color: var(--amarillo-3);
    }
</style>