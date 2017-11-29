<template>
    <div id="app">
        <mt-header fixed :title="getTitle()" id="header">
            <router-link to="/" slot="left">
                <mt-button icon="back">返回</mt-button>
            </router-link>

            <!--<mt-button slot="left" @click="handleClose">关闭</mt-button>

            <mt-button icon="more" slot="right"></mt-button>-->
        </mt-header>
        <!--<sidebar></sidebar>-->
        <div id="main">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import Sidebar from './sidebar'
    //import { mapState,mapMutations} from 'vuex'

    export default {
          data () {
            return {
              title: this.$store.getters['common/title']
            }
          },
        name: 'app',
        components: { 'sidebar': Sidebar },
      methods: {
            getTitle:function(){
                return this.$store.getters['common/title'];
            }

      },
      created(){

      },
    }
</script>

<style>
    /*body {
        margin:0;
    }*/

    /*html, body, #app {
        height:100%;
    }*/

</style>
