export default {
  name: 'room2',
  data () {
    return {
    }
  },
  mounted: function(){
    let canvas = document.querySelector('canvas'),
      ctx = canvas.getContext('2d')
    /*console.log(window);
    console.log(canvas);*/
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight - 40;
    ctx.fillStyle = "#cececa";
    ctx.fillRect(0,0,window.innerWidth,window.innerHeight);
    /*ctx.lineWidth = .3;
    ctx.strokeStyle = (new Color(150)).style;*/
  },
  created: function(){
  },
  computed : {

  },
  methods: {

  }
}