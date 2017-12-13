import { MessageBox} from 'mint-ui';

// let colors = {
//   0: 'white',
//   1: 'blue',
//   2: 'yellow',
//   3: 'red',
//   4: 'green'
// };

export default {
  name: 'game',
  data () {
    return {
      'colors':['white','blue','yellow','red','green'],
      'numbers':[1,1,1,2,2,3,3,4,4,5]
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
          if(this.$store.getters['my_game/game_id'] === 0){
            clearInterval(this.intervalid1)

            MessageBox('提示', '游戏已结束').then(action => {
              if(action ==='confirm'){
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
      user.is_you = user.id === this.$store.getters['auth/user_id'];
      return user;
    },
    guest_user:function(){
      let user = this.$store.getters['my_room/guest_user'];
      user.cards = this.$store.getters['my_game/guest_user_hand_cards'];
      user.is_you = user.id === this.$store.getters['auth/user_id'];
      return user;
    },
    library_cards_num:function(){
      return this.$store.getters['my_game/library_cards_num'];
    },
    cue_num:function(){
      return this.$store.getters['my_game/cue_num'];
    },
    chance_num:function(){
      return this.$store.getters['my_game/chance_num'];
    },
    discard_cards_num:function(){
      return this.$store.getters['my_game/discard_cards_num'];
    },
    table_cards:function(){
      return this.$store.getters['my_game/table_cards'];
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