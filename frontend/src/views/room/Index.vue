<template>
    <div id="room">
        <section class="user-block">
            房主：
            <div v-if="master_user.id>0" class="user-info">

                <div>{{'('+master_user.id+(master_user.is_you?'*':'')+')'+master_user.name}}</div>

                <div v-if="master_user.is_you">
                    <mt-button size="small" @click.native="startGame" :disabled="(guest_user.id>0 && guest_user.is_ready)?false:true" class="start-game-btn">开始游戏</mt-button>
                </div>
            </div>
        </section>
        <section class="user-block">
            玩家：
            <div v-if="guest_user.id>0" class="user-info">

                <div>{{'('+guest_user.id+(guest_user.is_you?'*':'')+')'+guest_user.name}}</div>

                <div v-if="guest_user.is_you">
                    <mt-button size="small" @click.native="doReady" class="get-ready-btn">{{guest_user.is_ready?'取消准备':'准备'}}</mt-button>
                </div>
                <div v-else>
                    {{guest_user.is_ready?'准备完成':'准备中'}}
                </div>

            </div>
        </section>
        <mt-button @click.native="exit" size="large" class="room-exit-btn" type="danger">退出房间</mt-button>
    </div>
</template>

<script src="@js/room/index.js"></script>
<style src="@css/room/index.css"></style>
