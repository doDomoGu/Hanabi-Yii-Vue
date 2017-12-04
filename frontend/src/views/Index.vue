<template>
    <div class="hello">
        <div v-if="isLogin()" >
            <div v-if="isInRoom()" >
                <mt-cell title="您已在房间中！"
                         :to="your_room_link"
                         is-link
                         value="进入">
                </mt-cell>
            </div>
            <div  v-else v-for="item in rooms">
                <mt-cell :title="item._title"
                         is-link
                         @click.native="enterRoom(item.id)"
                         value="进入">
                </mt-cell>

                <!--<mt-cell v-title="item.title">22</mt-cell>-->
            </div>
        </div>

        <div v-else>
            <mt-button type="primary" size="large" @click.native="toLogin">登录</mt-button><br/>
            <mt-button type="danger" size="large" @click.native="toRegister">注册</mt-button>

        </div>
        <!--<router-link to="/ssr">admin user</router-link>-->

        <!--<h2>Essential Links2</h2>
        <router-link to="/about">关于</router-link>-->



        <!--<mt-popup v-model="loginPopupVisible" position="right">
            <login></login>
        </mt-popup>-->
    </div>
</template>

<script src="@js/index.js"></script>
<style src="@css/index.css"></style>
