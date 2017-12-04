import { MessageBox} from 'mint-ui';


export default {
  name: 'room',
  data () {
    return {
      //rooms: this.$store.getters['rooms/list']
    }
  },
  mounted: function(){
    this.$store.dispatch('rooms/IsInRoom').then(()=>{
      this.$store.dispatch('common/SetTitle','房间'+this.$store.getters['rooms/your_room_id']);
    });
  },
  created: function(){
    //this.getRoom();
    /*    if(this.isLogin()){
          this.getRoom();
        }*/
    //this.rooms = this.getRoom();
  },
  computed : {

  },
  methods: {
    exit(){
      MessageBox.confirm('确定要退出房间?').then(action => {
        if(action=='confirm'){
          this.$store.dispatch('rooms/Exit');
          this.$router.push('/');
        }else{
          return false;
        }
      });
    }
    /*getUser(){
      this.$store.dispatch('rooms/GetRoomUser');
    }*/
  }
}