<template>
    <el-dialog id="loginDialog" title="登录" v-bind:visible=true size="full"  v-bind:showClose=false>
        <el-form id="loginForm" ref="form" :model="form" label-width="80px">
            <el-form-item label="账号">
                <el-input v-model="form.username" @keydown.enter.native="onSubmit" ></el-input>
            </el-form-item>
            <el-form-item label="密码">
                <el-popover :content="errormsg" placement="right" trigger="manual" ref="popover4"  effect="light">
                </el-popover>
                <el-input  @keydown.enter.native="onSubmit" type="password" v-model="form.password" v-popover:popover4></el-input>

                <!--<el-input type="password" v-model="form.password"></el-input>-->
            </el-form-item>
            <!--<el-form-item label="">
                <el-alert :title="errormsg" type="error" v-show=errormsg?true:false></el-alert>
            </el-form-item>-->
            <el-form-item>
                <el-button type="primary" @click="onSubmit">登录</el-button>
            </el-form-item>
        </el-form>
    </el-dialog>
</template>

<script>
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
        methods: {
            onSubmit() {
                this.$store.dispatch('auths/Login', this.form).then((res) => {
                    if(res.data.success){
                        //this.$store.dispatch('auths/SetStore', res.data);

                        /*this.$store.dispatch('auths/GenerateRoutes', {roles: this.$store.getters['auths/roles'], router: this.$router}).then(() => { // 生成可访问的路由表
  //                          this.$router.addRoutes(this.$store.getters.auth_add_routes) // 动态添加可访问路由表

                            this.$router.push({ path: '/' }); //登录成功之后重定向到首页
                        });*/


                        var redirectUrl = this.$route.query.redirectUrl;
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
</script>

<style lang='less' >
  #loginDialog {
    .el-dialog {
      padding-top: 160px;
    }
  }


  #loginDialog {
    .el-dialog__header,
    .el-dialog__body {
      width:400px;
      margin:0 auto;
    }
  }

  #loginDialog {
    .el-dialog__header {
      text-align: center;
    }
  }
</style>
