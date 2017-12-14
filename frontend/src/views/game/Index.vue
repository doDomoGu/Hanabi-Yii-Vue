<template>
    <div id="game">
        <section :class="'player-block' + (master_user.is_you?' is_you':'')">
            <div class="player-name">房主： {{'('+master_user.id+')'+master_user.name}}</div>

            <div class="hand-card">
                <li v-if="master_user.is_you===false" v-for="card in master_user.cards" :class="colors[card.color]+'-color'"
                    @click="showCardOperation(master_user.cards,card,1)">
                    <span>{{numbers[card.num]}}</span>
                </li>
                <li v-else class="no-color" @click="showCardOperation(master_user.cards,card,0)"></li>
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
        <section :class="'player-block' + (guest_user.is_you?' is_you':'')">
            <div class="player-name">玩家：{{'('+guest_user.id+')'+guest_user.name}}</div>

            <div class="hand-card">
                <li v-if="guest_user.is_you===false"  v-for="card in guest_user.cards" :class="colors[card.color]+'-color'"
                    @click="showCardOperation(guest_user.cards,card,1)">
                    <span @click="showCardOperation(card)">{{numbers[card.num]}}</span>
                </li>
                <li v-else class="no-color" @click="showCardOperation(guest_user.cards,card,0)"></li>
            </div>
        </section>
        <mt-button v-if="master_user.is_you>0" @click.native="endGame" size="large" class="game-end-btn" type="danger">结束游戏</mt-button>

        <x-dialog :show.sync="cardOperationShow" hide-on-blur :on-hide="clearSelect" class="">
            <div v-if="cardOperationType===1" class="oppsite-card-operation">
                操作对手手牌
            </div>
            <div v-if="cardOperationType===0" class="yourself-card-operation">
                <div class="selected-card-info">
                    {{cardSelectOrd}}
                </div>
                <div class="discard-btn">
                    是否要弃掉这张牌
                    <mt-button type="danger" size="small" @click.native="doDiscard">
                        弃掉
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
<style src="@css/game/index.css"></style>
