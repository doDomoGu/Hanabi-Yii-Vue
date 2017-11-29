import Vue from 'vue'
import Vuex from 'vuex'

import common from './common';

import users from './modules/users';
//import websites from './modules/websites';
import auths from './modules/auths';
import search from './search';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        common,
        users,
        auths,
        search,
        //websites
    }
});



