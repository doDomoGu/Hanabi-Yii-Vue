<template>
    <div id="game">
        <section :class="'player-block' + (host_player.is_you?' is_you':'')">
            <div class="player-name">房主： {{'('+host_player.id+')'+host_player.name}} {{round_player_is_host?'++':''}}</div>

            <div class="hand-card">
                <li v-if="host_player.is_you===false" v-for="card in host_player.cards" :class="colors[card.color]+'-color'"
                    @click="showCardOperation(host_player.cards,card,1)">
                    <span>{{numbers[card.num]}}</span>
                </li>
                <li v-else class="no-color" @click="showCardOperation(host_player.cards,card,0)"></li>
            </div>
        </section>

        <section class="middle-block">
            <div class="library-block">
                牌库<br/>{{library_cards_num}}张
            </div>
            <div class="box-block">
                提示数: {{cue_num}}
                <br/>机会数: {{chance_num}}
            </div>
            <div class="table-block">
                2222
            </div>
            <div class="discard-block">
                弃牌<br/>{{discard_cards_num}}张
            </div>
        </section>

        <section :class="'player-block' + (guest_player.is_you?' is_you':'')">
            <div class="player-name">玩家：{{'('+guest_player.id+')'+guest_player.name}} {{round_player_is_host?'':'++'}}</div>

            <div class="hand-card">
                <li v-if="guest_player.is_you===false"  v-for="card in guest_player.cards" :class="colors[card.color]+'-color'"
                    @click="showCardOperation(guest_player.cards,card,1)">
                    <span>{{numbers[card.num]}}</span>
                </li>
                <li v-else class="no-color" @click="showCardOperation(guest_player.cards,card,0)"></li>
            </div>
        </section>

        <mt-button v-if="host_player.is_you>0" @click.native="endGame" size="large" class="game-end-btn" type="danger">结束游戏</mt-button>

        <x-dialog :show.sync="cardOperationShow" hide-on-blur :on-hide="clearSelect" class="">
            <div v-if="cardOperationType===1" class="opposite-card-operation">
                <div class="selected-card-info">
                    {{cardSelectOrd+1}}
                </div>
            </div>
            <div v-if="cardOperationType===0" class="yourself-card-operation">
                <div class="selected-card-info">
                    {{cardSelectOrd+1}}
                </div>
                <div class="discard-btn">
                    是否要弃掉这张牌
                    <mt-button type="danger" size="small" @click.native="doDiscard">
                        弃掉
                    </mt-button>
                </div>
                <div class="play-btn">
                    是否要打出这张牌
                    <mt-button type="success" size="small" @click.native="doPlay">
                        打出
                    </mt-button>
                </div>
                <div class="change-card">
                    <div>选择一张牌，与之调换位置</div>
                    <li v-for="ordOne in [1,2,3,4,5]" class="no-color" @click="" v-if="ordOne!==cardSelectOrd">{{ordOne}}</li>
                </div>
            </div>
        </x-dialog>

    </div>
</template>

<script src="@js/game/index.js"></script>
<style lang="scss" src="@css/game/index.scss"></style>