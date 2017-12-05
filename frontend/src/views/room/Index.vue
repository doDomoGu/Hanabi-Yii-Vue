<template>
    <div id="room">
        <section class="user-block">
            房主：<div v-if="master_user.id>0" class="user-info">{{'('+master_user.id+')'+master_user.name}}</div>
        </section>
        <section class="user-block">
            玩家：<div v-if="guest_user.id>0" class="user-info">{{'('+guest_user.id+')'+guest_user.name}}</div>
        </section>
        <mt-button @click.native="exit" size="large" class="room-exit-btn" type="danger">退出房间</mt-button>
    </div>
</template>

<script src="@js/room/index.js"></script>
<style src="@css/room/index.css"></style>
