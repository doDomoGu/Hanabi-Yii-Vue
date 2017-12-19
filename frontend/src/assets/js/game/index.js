import { MessageBox} from 'mint-ui';
//import { XDialog, XButton, Group, XSwitch, TransferDomDirective as TransferDom } from 'vux'
import  XDialog from 'vux/src/components/x-dialog'
export default {
  name: 'game',
  components: {
    XDialog,
  },
  data () {
    return {
      colors:['white','blue','yellow','red','green'],
      numbers:[1,1,1,2,2,3,3,4,4,5],
      cardOperationShow:false,
      cardOperationType:-1,
      cardSelectOrd:-1,
      cardSelectColor:-1,
      cardSelectNum:-1,

    }
  },
  mounted: function(){

  },
  created: function(){
    this.$store.dispatch('my_game/IsInGame').then(()=>{

      this.$store.dispatch(
        'common/SetTitle',
        this.$store.getters['common/title_suffix']+' - '+(this.$store.getters['my_game/is_playing']>0?'游戏中':'错误')
      );
      this.getRoomInfo();

      this.getGameInfo();

      this.intervalid1 = setInterval(()=>{
        this.getGameInfo();

        this.$store.dispatch('my_game/IsInGame').then(()=>{
          if(!this.$store.getters['my_game/is_playing']){
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
    host_player:function(){
      let player = this.$store.getters['my_room/host_player'];
      player.cards = this.$store.getters['my_game/host_hands'];
      player.is_you = player.id === this.$store.getters['auth/user_id'];
      return player;
    },
    guest_player:function(){
      let player = this.$store.getters['my_room/guest_player'];
      player.cards = this.$store.getters['my_game/guest_hands'];
      player.is_you = player.id === this.$store.getters['auth/user_id'];
      return player;
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
    },
    round_player_is_host:function(){
      return this.$store.getters['my_game/round_player_is_host'];
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
    },
    showCardOperation(cards,card,type){
      this.clearSelect();
      //cards所有手牌
      //card选中的手牌
      //type 0:自己的手牌 1:对手的手牌
      let index = cards.indexOf(card); //序号 从左至右 0-4

      if(type===0){
        this.cardSelectOrd = card.ord;
      }else if(type===1){
        this.cardSelectColor = card.color;
        this.cardSelectNum = card.num;
        this.cardSelectOrd = card.ord;
      }

      this.cardOperationType = type;
      this.cardOperationShow = true;
    },
    clearSelect(){
      this.cardSelectColor = -1;
      this.cardSelectNum = -1;
      this.cardSelectOrd = -1;
      this.cardOperationType = -1;
    },
    doDiscard(){
      this.$store.dispatch('my_game/DoDiscard',this.cardSelectOrd).then((res)=>{
        if(res.success){
          this.cardOperationShow = false;
        }else{
          alert(res.msg);
        }
      })
    },
    doPlay(){
      this.$store.dispatch('my_game/DoPlay',this.cardSelectOrd).then((res)=>{
        if(res.success){
          this.cardOperationShow = false;
        }else{
          alert(res.msg);
        }
      })
    },


  }
}