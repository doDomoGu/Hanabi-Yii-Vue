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
  created(){
    /*this.intervalid1 = setInterval(()=>{

      if(this.$store.getters['auth/token']!=''){
        let token = this.$store.getters['auth/token'];
        this.$store.dispatch('auth/CheckToken', [this.$store.getters['auth/token'],true]).then(()=>{
          console.log(this.$store.getters['auth/is_login']);
          if(this.$store.getters['auth/is_login']===false){
            this.$store.dispatch('auth/Logout',token).then(() => {
              clearInterval(this.intervalid1);
              console.log('clear');
              this.$router.push({path: '/login'});
            })
          }
        })
      }
    },500);*/
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
      return this.$store.getters['auth/is_login']===true;
    }
  },

  //components: { 'sidebar': Sidebar },



  /*created(){

  },*/
}