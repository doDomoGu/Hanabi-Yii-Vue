<template>
    <div id="room">
        <section class="user-block">
            房主：
            <div v-if="host_player.id>0" class="user-info">

                <div>{{'('+host_player.id+(host_player.is_you?'*':'')+')'+host_player.name}}</div>

                <div v-if="host_player.is_you">
                    <mt-button size="small" @click.native="startGame" :disabled="(guest_player.id>0 && guest_player.is_ready)?false:true" class="start-game-btn">开始游戏</mt-button>
                </div>
            </div>
        </section>
        <section class="user-block">
            玩家：
            <div v-if="guest_player.id>0" class="user-info">

                <div>{{'('+guest_player.id+(guest_player.is_you?'*':'')+')'+guest_player.name}}</div>

                <div v-if="guest_player.is_you">
                    <mt-button size="small" @click.native="doReady" class="get-ready-btn">{{guest_player.is_ready?'取消准备':'准备'}}</mt-button>
                </div>
                <div v-else>
                    {{guest_player.is_ready?'准备完成':'准备中'}}
                </div>

            </div>
        </section>
        <mt-button @click.native="exit" size="large" class="room-exit-btn" type="danger">退出房间</mt-button>
    </div>
</template>

<script src="@js/room/index.js"></script>
<style src="@css/room/index.css"></style>
