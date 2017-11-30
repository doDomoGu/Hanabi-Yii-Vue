<template>
    <section>
        <mt-field label="用户名" placeholder="请输入用户名" @keydown.enter.native="onSubmit" v-model="form.username"></mt-field>

        <mt-field label="密码" placeholder="请输入密码" type="password" v-model="form.password" @keydown.enter.native="onSubmit" ></mt-field>

    </section>



    <!--<el-dialog id="loginDialog" title="登录" v-bind:visible=true size="full"  v-bind:showClose=false>
        <el-form id="loginForm" ref="form" :model="form" label-width="80px">
            <el-form-item label="账号">
                <el-input v-model="form.username"  ></el-input>
            </el-form-item>
            <el-form-item label="密码">
                <el-popover :content="errormsg" placement="right" trigger="manual" ref="popover4"  effect="light">
                </el-popover>
                <el-input  @keydown.enter.native="onSubmit" type="password" v-model="form.password" v-popover:popover4></el-input>

            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="onSubmit">登录</el-button>
            </el-form-item>
        </el-form>
    </el-dialog>-->
</template>

<script src="@js/login.js"></script>

<!--<style lang='less' >
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
</style>-->
