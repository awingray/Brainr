<template>
    <div>
        <navbar></navbar>
        <router-view></router-view>
    </div>
</template>

<script>
    import navbar from './NavBar'

    export default {
        components: {
            navbar
        }
    }
</script>
