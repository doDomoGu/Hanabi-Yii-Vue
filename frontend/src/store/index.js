import Vue from 'vue'
import Vuex from 'vuex'

import common from './common';

import room from './modules/room';
import my_room from './modules/my_room';

import your_game from './modules/your_game';
import auth from './modules/auth';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        common,
        auth,
        room,
        my_room,
        your_game
    }
});



