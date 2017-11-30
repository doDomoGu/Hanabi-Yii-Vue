<template>
    <div class="hello">
        <mt-button type="primary" size="large" @click.native="toLogin">登录</mt-button><br/>
        <mt-button type="danger" size="large" @click.native="toRegister">注册</mt-button>
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
