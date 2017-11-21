<template>
    <div id="sidebar">
        <div style="padding-left:20px;color:#eee;line-height:40px;">
            用户：{{username}}
        </div>
        <el-menu :default-active="$route.path" class="el-menu-vertical-demo" theme="dark" v-bind:unique-opened=true @select="handleSelect" @open="handleOpen" @close="handleClose" router>
            <el-menu-item index="/"><i class="el-icon-menu"></i>首页</el-menu-item>
            <el-menu-item v-if="isAuth('/website')" index="/website"><i class="el-icon-setting"></i>网站</el-menu-item>
            <el-menu-item v-if="isAuth('/setting')" index="/setting"><i class="el-icon-setting"></i>设置</el-menu-item>

            <el-submenu v-if="isAuth('/admin')" index="/admin">
                <template slot="title">
                    <i class="el-icon-caret-right"></i>后台管理
                </template>
                <el-menu-item v-if="isAuth('/admin/user')" index="/admin/user">用户</el-menu-item>
                <el-menu-item v-if="isAuth('/admin/usergroup')" index="/admin/usergroup">用户组</el-menu-item>
                <el-menu-item v-if="isAuth('/admin/website')" index="/admin/website">站点</el-menu-item>
            </el-submenu>
            <el-menu-item index="/logout"><i class="el-icon-caret-right"></i>退出</el-menu-item>
        </el-menu>
        <!--<div>
            <input v-model="username"/>  {{username}}
             {{username22}}

        </div>-->
    </div>
</template>
<script>
export default {
    data(){

        return {
            username:this.$store.getters['auths/user_info'].name,
        }
    },
    methods: {
        isAuth(path){
            let ret = false;
            let routes = this.$store.getters['auths/routes'];
            let roles = this.$store.getters['auths/roles'];

            if(path in routes){
                let route = routes[path];

                if(typeof route==='undefined' || !('requireAuths' in route) || route.requireAuths !== true || roles.indexOf('super_admin')>-1){
                    ret = true;
                } else {
                    for(let i in route.requireRoles){
                        if(ret === false && roles.indexOf(route.requireRoles[i])>-1){
                            ret = true;
                        }
                    }
                }
            }
            return ret;
        },
        handleOpen(key, keyPath) {
            //console.log('open',key, keyPath);
        },
        handleClose(key, keyPath) {
            //console.log('close',key, keyPath);
        },
        handleSelect(key, keyPath) {
            //console.log('select',key, keyPath);
        }/*,
        test() {
            console.log('s    ');
            console.log('is_login : ',this.$store.getters['auths/is_login']);

            if(this.$store.getters['auths/is_login']){
                this.$store.dispatch('auths/SetIsLogin',false);
            }else{
                this.$store.dispatch('auths/SetIsLogin',true);
            }

            console.log('is_login : ',this.$store.getters['auths/is_login']);
            console.log(this.is_login);

        }*/

    },
    created(){
        //console.log('sidebar created');
    }
}
</script>
<style>
    #sidebar {
        height:100%;
        width:240px;
        position:absolute;
        background-color:#324157;
    }
    #sidebar .el-menu {
        border-radius: 0;
    }
</style>
