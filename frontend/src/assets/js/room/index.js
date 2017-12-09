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

    this.$store.dispatch('room/IsInRoom').then(()=>{

      this.$store.dispatch(
        'common/SetTitle',
        this.$store.getters['common/title_suffix']+' - '+'房间'+this.$store.getters['room/your_room_id']
      );
      this.getRoomInfo();

      this.intervalid1 = setInterval(()=>{
        this.getRoomInfo();
        if(this.$store.getters['room/your_room_is_playing']){
          this.$router.push('/game');
        }
      },500);

    });
  },
  beforeDestroy () {
    clearInterval(this.intervalid1)
  },
  computed : {
    master_user:function(){
      let user = this.$store.getters['room/your_room_master_user'];
      user.is_you = false;
      if(user.id == this.$store.getters['auth/user_id']){
        user.is_you = true;
      }
      return user;
    },
    guest_user:function(){
      let user = this.$store.getters['room/your_room_guest_user'];
      user.is_you = false;
      if(user.id == this.$store.getters['auth/user_id']){
        user.is_you = true;
      }
      return user;
    }
  },
  methods: {
    exit(){
      MessageBox.confirm('确定要退出房间?').then(action => {
        if(action=='confirm'){
          this.$store.dispatch('room/Exit').then(()=>{
            this.$router.push('/');
          });
        }else{
          return false;
        }
      });
    },
    getRoomInfo(){
      this.$store.dispatch('room/GetRoomInfo');
    },
    doReady(){
      this.$store.dispatch('room/DoReady');
    },
    startGame(){
      this.$store.dispatch('room/StartGame')/*.then((res)=>{
        if(res.success){
          this.$router.push('/game');
        }else{
          alert('开始失败');
        }
      });*/
    }
  }
}