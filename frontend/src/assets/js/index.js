import { MessageBox } from 'mint-ui';


export default {
  name: 'index',
  data () {
    return {
      //rooms: this.$store.getters['rooms/list']
    }
  },
  mounted: function(){


  },
  created: function(){
    this.$store.dispatch('common/SetTitle','Hanabi');
    if(this.isLogin()){
      this.$store.dispatch('rooms/IsInRoom').then(()=>{
        this.$store.dispatch('common/SetTitle','Hanabi ('+this.$store.getters['auths/user_id']+')');
      });
      this.getRoom();
    }
  },
  computed : {
    your_room_link: function(){
      return '/room/'+this.$store.getters['rooms/your_room_id'];
    },
    rooms : function() {
      let rooms = this.$store.getters['rooms/list'];

      for(let room of rooms){
        room._title = '<mt-badge size="small">'+room.id+'</mt-badge>'+' '+room.title;
        room._title = (room.id<100?room.id<10?'00'+room.id:'0'+room.id:room.id)+' '+room.title;

        if(room.password!==''){
          room._title += '[lock]';
        }
      }
      return rooms;
    }
  },
  methods: {
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
      return this.$store.getters['auths/is_login'];
    },
    getRoom(){
      this.$store.dispatch('rooms/LIST');


      /*this.$store.dispatch('rooms/LIST').then((res)=>{
        //that.rooms = res.data;

        //return res.data;
      })*/

      //return ;
    },
    enterRoom(room_id){
      let that = this;
      this.$store.dispatch('rooms/Enter',{room_id:room_id}).then((res)=>{
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
/*      let your_room_id = this.$store.getters['rooms/your_room_id'];
      if(your_room_id!==false){

      }*/

      return this.$store.getters['rooms/your_room_id'];

    }

  }
}