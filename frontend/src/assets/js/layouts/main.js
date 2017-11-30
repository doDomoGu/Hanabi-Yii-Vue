//import Sidebar from '@/views/layouts/sidebar'
//import { mapState,mapMutations} from 'vuex'

export default {
  name: 'app',
  data () {
    return {
      //transitionName: 'slide-left'
    }
  },
  // dynamically set transition based on route change
  watch: {
    /*'$route' (to, from) {
      const toDepth = to.path.split('/').length
      const fromDepth = from.path.split('/').length
      this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left'
    }*/
  },
  methods: {
    getTitle(){
      let title = this.$store.getters['common/title'];
      document.title = title;
      return title;
    },
    isTopPath(){
      return this.$route.path !=='/';
    },
    isLogin(){
      return this.$store.getters['auths/is_login']===true;
    }
  },

  //components: { 'sidebar': Sidebar },



  /*created(){

  },*/
}