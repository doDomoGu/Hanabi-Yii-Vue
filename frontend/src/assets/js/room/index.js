import { MessageBox} from 'mint-ui';

export default {
  name: 'room',
  data () {
    return {
    }
  },
  mounted: function(){

  },
  created: function(){

    this.$store.dispatch('my_room/IsInRoom').then(()=>{

      this.$store.dispatch('common/SetTitle2','房间'+this.$store.getters['my_room/room_id']);
      this.getRoomInfo();

      this.intervalid1 = setInterval(()=>{
        this.getRoomInfo();
        this.$store.dispatch('my_game/IsInGame').then(()=>{
          if(this.$store.getters['my_game/is_playing']){
            this.$router.push('/game');
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
      let user = this.$store.getters['my_room/host_player'];
      user.is_you = user.id === this.$store.getters['auth/user_id'];
      return user;
    },
    guest_player:function(){
      let user = this.$store.getters['my_room/guest_player'];
      user.is_you = user.id === this.$store.getters['auth/user_id'];
      return user;
    }
  },
  methods: {
    exit(){
      MessageBox.confirm('确定要退出房间?').then(action => {
        if(action==='confirm'){
          this.$store.dispatch('my_room/Exit').then(()=>{
            this.$router.push('/');
          });
        }else{
          return false;
        }
      });
    },
    getRoomInfo(){
      this.$store.dispatch('my_room/GetRoomInfo');
    },
    doReady(){
      this.$store.dispatch('my_room/DoReady');
    },
    startGame(){
      this.$store.dispatch('my_game/Start').then((res)=>{
        if(res.success){
          this.$router.push('/game');
        }
      })
    }
  }
}