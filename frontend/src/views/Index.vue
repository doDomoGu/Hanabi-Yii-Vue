<template>
    <div class="hello">
        <mt-button type="default" size="large" @click.native="toLogin">登录</mt-button>
        <mt-button type="default" size="large" @click.native="toRegister">注册</mt-button>



        <!--<router-link to="/ssr">admin user</router-link>-->

        <!--<h2>Essential Links2</h2>
        <router-link to="/about">关于</router-link>-->
    </div>
</template>

<script>
    import { Toast } from 'mint-ui';

    export default {
        name: 'index',
        data () {
            return {
                //msg: 'Welcome to Your Vue.js App'
            }
        },
        mounted: function(){
            this.$store.dispatch('common/SetTitle','首页');
        },
        methods: {
            toLogin(){
                Toast({
                    message: '提示',
                    position: 'bottom',
                    duration: 500
                });
            },
            toLogin(){
                Toast({
                  message: '提示',
                  position: 'bottom',
                  duration: 500
                });
            },
        }
    }
</script>

<style scoped>
    h1, h2 {
      font-weight: normal;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        display: inline-block;
        margin: 0 10px;
    }

    a {
        color: #42b983;
    }
</style>
