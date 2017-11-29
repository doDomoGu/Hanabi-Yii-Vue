<template>
    <div class="not-found-404">
        404
    </div>
</template>

<script>
export default {
    name: 'notfound',
    /*data () {
        return {
          msg: 'Welcome to Your Vue.js App'
        },

    }*/
  mounted: function(){
    this.$store.dispatch('common/SetTitle','404');
  },
}
</script>

<!--<style scoped>
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
</style>-->
