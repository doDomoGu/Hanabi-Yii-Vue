<template>
    <div id="game">
        <section :class="'player-block' + (master_user.is_you?' is_you':'')">
            <div class="player-name">房主： {{'('+master_user.id+')'+master_user.name}}</div>

            <div class="hand-card">
                <li v-for="card in master_user.cards" :class="colors[card.color]+'-color'">
                    <span>{{numbers[card.num]}}</span>
                </li>
            </div>
        </section>
        <section class="table-block">
            <div class="library-block"></div>
            <div class="box-block"></div>
            <div class="discard-block"></div>
        </section>
        <section :class="'player-block' + (guest_user.is_you?' is_you':'')">
            <div class="player-name">玩家：{{'('+guest_user.id+')'+guest_user.name}}</div>

            <div class="hand-card">
                <li v-for="card in guest_user.cards" :class="colors[card.color]+'-color'">
                    <span>{{numbers[card.num]}}</span>
                </li>
            </div>
        </section>
        <mt-button v-if="master_user.is_you>0" @click.native="endGame" size="large" class="game-end-btn" type="danger">结束游戏</mt-button>
    </div>
</template>

<script src="@js/game/index.js"></script>
<style src="@css/game/index.css"></style>
