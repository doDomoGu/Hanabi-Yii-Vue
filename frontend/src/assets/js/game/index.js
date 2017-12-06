import { MessageBox} from 'mint-ui';


export default {
  name: 'game',
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
        this.$store.getters['common/title_suffix']+' - '+(this.$store.getters['rooms/your_room_is_playing']?'游戏中':'错误')
      );

    });
  },
  computed : {

  },
  methods: {

  }
}