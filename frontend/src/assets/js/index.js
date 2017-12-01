import { Toast } from 'mint-ui';


export default {
  name: 'index',
  data () {
    return {
      //msg: 'Welcome to Your
      // Vue.js App'
    }
  },
  mounted: function(){
    this.$store.dispatch('common/SetTitle','首页');
  },
  methods: {
    toLogin(){
      this.$router.push({path:'/login'});

    },
    toRegister(){
      Toast({
        message: '提示',
        position: 'bottom',
        duration: 500
      });
    },
    isNotAuth(){
      return this.$store.getters['auths/is_login']!==true;
    }
  }
}