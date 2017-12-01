import { Toast } from 'mint-ui';


export default {
  name: 'index',
  data () {
    return {
      rooms: []
    }
  },
  mounted: function(){
    this.$store.dispatch('common/SetTitle','首页');

  },
  created: function(){
    if(this.isLogin()){
      this.getRoom();
    }
  },
  methods: {
    toLogin(){
      this.$router.push({path:'/login'});
    },
    /*toRegister(){
      Toast({
        message: '提示',
        position: 'bottom',
        duration: 500
      });
    },*/
    isLogin(){
      return this.$store.getters['auths/is_login'];
    },
    getRoom(){
      let that = this;
      this.$store.dispatch('rooms/LIST').then((res)=>{
        that.rooms = res.data;
      })
    }
  }
}