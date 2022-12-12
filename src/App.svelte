<script>
    import Router from 'svelte-spa-router'
    import routes from './routes'
    import Menu from './Componentes/Menu.svelte'
</script>

<main>
    <Menu />
    <Router {routes}/>
</main>

