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
    this.$store.dispatch('my_game/IsInGame').then(()=>{

      this.$store.dispatch(
        'common/SetTitle',
        this.$store.getters['common/title_suffix']+' - '+(this.$store.getters['my_game/game_id']>0?'游戏中':'错误')
      );
      this.getRoomInfo();

      this.getGameInfo();
      this.intervalid1 = setInterval(()=>{
        this.getGameInfo();

        this.$store.dispatch('my_game/IsInGame').then(()=>{
          if(this.$store.getters['my_game/game_id']==0){
            clearInterval(this.intervalid1)

            MessageBox('提示', '游戏已结束').then(action => {
              if(action=='confirm'){
                this.$router.push('/room');
              }
            });
          }
        });

      },500);

    });
  },
  beforeDestroy () {
    clearInterval(this.intervalid1)
  },
  computed : {
    master_user:function(){
      let user = this.$store.getters['my_room/master_user'];
      user.cards = this.$store.getters['my_game/master_user_hand_cards'];
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
    getGameInfo(){
      this.$store.dispatch('my_game/GetGameInfo');
    },
    getRoomInfo(){
      this.$store.dispatch('my_room/GetRoomInfo');
    },
    endGame(){

      this.$store.dispatch('my_game/End');
    }
  }
}