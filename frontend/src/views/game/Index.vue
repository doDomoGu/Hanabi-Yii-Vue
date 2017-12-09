<template>
    <div id="game">
        <section>
            <div class="player1 player-info">
                房主：
                <div v-if="master_user.id>0" class="user-info">

                    <div>{{'('+master_user.id+(master_user.is_you?'*':'')+')'+master_user.name}}</div>

                    <div class="game-card" >
                        <span v-for="card in master_user.cards">{{card.color+' - '+card.num}} </span>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="player2 player-info">

            </div>
        </section>

    </div>
</template>

<script src="@js/game/index.js"></script>
<!--<style src="@css/game/index.css"></style>-->
