import { MessageBox } from 'mint-ui';


export default {
  name: 'index',
  data () {
    return {
      //rooms: this.$store.getters['rooms/list']
    }
  },
  mounted: function(){
    this.$store.dispatch('common/SetTitle','首页');

  },
  created: function(){
    this.getRoom();
/*    if(this.isLogin()){
      this.getRoom();
    }*/
    //this.rooms = this.getRoom();
  },
  computed : {
    rooms : function() {
      let rooms = this.$store.getters['rooms/list'];


      for(let room of rooms){
        room._title = '<mt-badge size="small">'+room.id+'</mt-badge>'+' '+room.title;
        room._title = (room.id<100?room.id<10?'00'+room.id:'0'+room.id:room.id)+' '+room.title;


        if(room.password!=''){
          room._title += '[lock]';
          //room._title = room.id'[lock]';
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
      this.$store.dispatch('rooms/Enter',{room_id:room_id}).then((res)=>{
        //that.rooms = res.data;
        //console.log(res);
        //return res.data;
        MessageBox.alert(res.data.msg+'('+room_id+')').then(action => {

        });
      })


    }
  }
}