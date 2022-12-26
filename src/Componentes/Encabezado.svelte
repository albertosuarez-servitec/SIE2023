<script>

</script>

<main class="container-fluid">
    <div class="logos">
        <div class="logos-left">
            <img class="logo-img" src="/encrym_logo_blanco.png" alt="">
        </div>
        <div class="logos-center">
            <h1 class="text-light">SIENCRyM</h1>
        </div>
        <div class="logos-right">
            <img class="logo-img" src="/inah_logo_blanco.png" alt="">
        </div>
    </div>
</main>

<style>
    .logos {
        display: grid;
        grid-template-columns: 1fr, 1fr, 1fr;
    }
    .logos-left {
        grid-row: 1 / 1;
        grid-column: 1 / 1;
        text-align: left;
    }
    .logos-center {
        grid-row: 1 / 1;
        grid-column: 2 / 2;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .logos-right {
        grid-row: 1 / 1;
        grid-column: 3 / 3;
        text-align: right;
    }
    .container-fluid {
        padding-left: 5px;
    }
    .logo-img {
        height: 60px !important;
        margin-top: 5px;
        margin-bottom: 5px;
    }
    main {
        height: 70px;
        background-color: var(--rojo-5);
    }
</style>