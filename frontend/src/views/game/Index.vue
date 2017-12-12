<template>
    <div id="game">
        <section>
            <div class="player1 player-block">
                房主：
                <div v-if="master_user.id>0" class="player-info">

                    <div>{{'('+master_user.id+(master_user.is_you?'*':'')+')'+master_user.name}}</div>

                    <div class="hand-card">

                        <li v-for="card in master_user.cards" >
                            <span :class="colors[card.color]+'-color'">
                                {{card.color+' - '+card.num}}
                            </span>
                        </li>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="player2 player-block">
                玩家：
                <div v-if="guest_user.id>0" class="player-info">

                    <div>{{'('+guest_user.id+(guest_user.is_you?'*':'')+')'+guest_user.name}}</div>

                    <div class="hand-card">
                        <li v-for="card in guest_user.cards" >
                            <span :class="colors[card.color]+'-color'">
                                {{card.color+' - '+card.num}}
                            </span>
                        </li>
                    </div>
                </div>
            </div>
        </section>
        <mt-button v-if="master_user.is_you>0" @click.native="endGame" size="large" class="game-end-btn" type="danger">结束游戏</mt-button>
    </div>
</template>

<script src="@js/game/index.js"></script>
<style src="@css/game/index.css"></style>
