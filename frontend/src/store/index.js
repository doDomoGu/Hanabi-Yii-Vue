import Vue from 'vue'
import Vuex from 'vuex'

import common from './common';

import users from './modules/users';
import room from './modules/room';
import your_game from './modules/your_game';
import auth from './modules/auth';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        common,
        users,
        auth,
        room,
        your_game
    }
});



