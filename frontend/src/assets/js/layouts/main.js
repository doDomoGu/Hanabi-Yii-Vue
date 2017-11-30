import Sidebar from '@/views/layouts/sidebar'
//import { mapState,mapMutations} from 'vuex'

export default {
  /*data(){
      return {
      }
  },*/

  name: 'app',

  components: { 'sidebar': Sidebar },

  methods: {
    getTitle(){
      let title = this.$store.getters['common/title'];
      document.title = title;
      return title;
    },
    isTopPath(){
      return this.$route.path !=='/';
    }
  },

  /*created(){

  },*/
}