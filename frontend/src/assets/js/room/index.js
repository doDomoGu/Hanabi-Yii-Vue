import { MessageBox} from 'mint-ui';


export default {
  name: 'room',
  data () {
    return {
      //rooms: this.$store.getters['rooms/list']
    }
  },
  mounted: function(){

  },
  created: function(){

    this.$store.dispatch('rooms/IsInRoom').then(()=>{

      this.$store.dispatch(
        'common/SetTitle',
        this.$store.getters['common/title_suffix']+' - '+'房间'+this.$store.getters['rooms/your_room_id']
      );
      this.getUser();

      this.intervalid1 = setInterval(()=>{
        this.getUser();
      },500);

    });
  },
  beforeDestroy () {
    clearInterval(this.intervalid1)
  },
  computed : {
    master_user:function(){
      let user = this.$store.getters['rooms/your_room_master_user'];
      user.is_you = false;
      if(user.id == this.$store.getters['auths/user_id']){
        user.is_you = true;
      }
      return user;
    },
    guest_user:function(){
      let user = this.$store.getters['rooms/your_room_guest_user'];
      user.is_you = false;
      if(user.id == this.$store.getters['auths/user_id']){
        user.is_you = true;
      }
      return user;
    }
  },
  methods: {
    exit(){
      MessageBox.confirm('确定要退出房间?').then(action => {
        if(action=='confirm'){
          this.$store.dispatch('rooms/Exit').then(()=>{
            this.$router.push('/');
          });
        }else{
          return false;
        }
      });
    },
    getUser(){
      this.$store.dispatch('rooms/GetRoomUser',this.$store.getters['rooms/your_room_id']);
    },
    doReady(){
      this.$store.dispatch('rooms/DoReady',this.$store.getters['rooms/your_room_id']);
    }
  }
}