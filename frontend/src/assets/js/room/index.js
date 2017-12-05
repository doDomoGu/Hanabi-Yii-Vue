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
      this.$store.dispatch('common/SetTitle','房间'+this.$store.getters['rooms/your_room_id']);
      this.$store.dispatch('rooms/GetRoomUser',this.$store.getters['rooms/your_room_id']);
    });
  },
  computed : {
    master_user:function(){
      return this.$store.getters['rooms/your_room_master_user'];
    },
    guest_user:function(){
      return this.$store.getters['rooms/your_room_guest_user'];
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
    }
  }
}