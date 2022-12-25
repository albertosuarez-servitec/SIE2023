<script>
    import Router from 'svelte-spa-router'
    import routes from './routes'
    import Encabezado from './Componentes/Encabezado.svelte';
    import Menu from './Componentes/Menu.svelte'
</script>

<main>
    <Encabezado />
    <Menu />
    <Router {routes}/>
</main>

