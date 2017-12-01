//import axios from 'axios';


export default {
  name: 'LoginForm',
  data() {
    return {
      form: {
        username: 'admin',
        password: '123123'
      },
      errormsg: ''
    }
  },
  mounted: function(){
    this.$store.dispatch('common/SetTitle','登录');
  },
  methods: {
    onSubmit() {
      this.$store.dispatch('auths/Login', this.form).then((res) => {
        if(res.data.success){
          //this.$store.dispatch('auths/SetStore', res.data);

          /*this.$store.dispatch('auths/GenerateRoutes', {roles: this.$store.getters['auths/roles'], router: this.$router}).then(() => { // 生成可访问的路由表
//                          this.$router.addRoutes(this.$store.getters.auth_add_routes) // 动态添加可访问路由表

              this.$router.push({ path: '/' }); //登录成功之后重定向到首页
          });*/


          let redirectUrl = this.$route.query.redirectUrl;
          if(redirectUrl)
            this.$router.push({ path: redirectUrl });
          else
            this.$router.push({ path: '/' });
        }else{
          //console.log('submit login failure');
          //this.errormsg = res.data.errormsg;

          this.$message.error(res.data.error_msg); //登录失败提示错误
        }
      }).catch(err => {
        //console.log('submit login error');
        //console.log(err);
        this.$message.error(err); //登录失败提示错误
      });
    }
  }
}