<template>
    <div id="app">
        <sidebar></sidebar>
        <div id="main">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import Sidebar from './sidebar'
    //import { mapState,mapMutations} from 'vuex'
    export default {
        name: 'app',
        components: { 'sidebar': Sidebar }
    }
</script>

<style>
    body {
        margin:0;
    }

    html, body, #app {
        height:100%;
    }

    #main {
        margin-left:240px;
        padding:24px;
    }

    /*#app {
      font-family: 'Avenir', Helvetica, Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-align: center;
      color: #2c3e50;
      margin-top: 60px;
    }*/
</style>
