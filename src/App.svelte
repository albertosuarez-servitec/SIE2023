<script>
    import Router from 'svelte-spa-router'
    // import routes from './routes'
    import Menu from './Componentes/Menu.svelte'

    import Swal from 'sweetalert2'

    Swal.fire (
        'The Internet?',
        'That thing is still around?',
        'question'
    )
</script>

<main>
    <Menu />
</main>

