import { MessageBox } from 'mint-ui';


export default {
  name: 'index',
  data () {
    return {
    }
  },
  mounted: function(){


  },
  created: function(){
    this.$store.dispatch('common/SetTitle2','Hanabi');
    if(this.isLogin()){
      this.getRoomList();

      this.$store.dispatch('my_room/IsInRoom').then(()=>{
        this.$store.dispatch('common/SetTitle2','('+this.$store.getters['auth/user_id']+')');
      });

    }
  },
  computed : {
    your_room_link: function(){
      return '/room/'+this.$store.getters['my_room/room_id'];
    },
    room_list : function() {
      let room_list = this.$store.getters['room/list'];

      for(let room of room_list){
        room._title = '<mt-badge size="small">'+room.id+'</mt-badge>'+' '+room.title;
        room._title = (room.id<100?room.id<10?'00'+room.id:'0'+room.id:room.id)+' '+room.title;

        if(room.password!==''){
          room._title += '[lock]';
        }
      }
      return room_list;
    }
  },
  methods: {
    getRoomList(){
      this.$store.dispatch('room/List');
    },
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
      return this.$store.getters['auth/is_login'];
    },

    enterRoom(room_id){
      let that = this;
      this.$store.dispatch('my_room/Enter',{room_id:room_id}).then((res)=>{
        if(res.success){
          that.$router.push('/room/'+room_id);
        }else {
          MessageBox.alert(res.msg + '(' + room_id + ')').then(action => {
            //console.log(action);
          });
        }
      })
    },
    isInRoom(){
      return this.$store.getters['my_room/room_id'];

    }

  }
}