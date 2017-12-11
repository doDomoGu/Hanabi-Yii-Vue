import { MessageBox} from 'mint-ui';


export default {
  name: 'game',
  data () {
    return {
    }
  },
  mounted: function(){

  },
  created: function(){
    this.$store.dispatch('my_room/IsInRoom').then(()=>{

      this.$store.dispatch(
        'common/SetTitle',
        this.$store.getters['common/title_suffix']+' - '+(this.$store.getters['my_game/game_id']>0?'游戏中':'错误')
      );
      this.getGameInfo();
      this.intervalid1 = setInterval(()=>{
        this.getGameInfo();
        /*if(this.$store.getters['my_room/is_playing']){
          this.$router.push('/game');
        }*/
      },500);

    });
  },
  beforeDestroy () {
    clearInterval(this.intervalid1)
  },
  computed : {
    master_user:function(){
      let user = this.$store.getters['my_room/master_user'];
      let game = this.$store.getters['games']
      user.is_you = false;
      if(user.id == this.$store.getters['auth/user_id']){
        user.is_you = true;
      }
      return user;
    },
    guest_user:function(){
      let user = this.$store.getters['my_room/guest_user'];
      user.is_you = false;
      if(user.id == this.$store.getters['auth/user_id']){
        user.is_you = true;
      }
      return user;
    }
  },
  methods: {
    geGameInfo(){
      this.$store.dispatch('game/GetGameInfo');
    },
  }
}