<template>
    <div class="hello">
        <span>首页</span>
        <router-link to="/admin/user">admin user</router-link>

        <!--<h2>Essential Links2</h2>
        <router-link to="/about">关于</router-link>-->
    </div>
</template>

<script>
export default {
    name: 'index',
    data () {
        return {
          msg: 'Welcome to Your Vue.js App'
        }
    },
    methods: {

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
