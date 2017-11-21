import Vue from 'vue'
import Vuex from 'vuex'

import users from './modules/users';
//import websites from './modules/websites';
import auths from './modules/auths';
import search from './search';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        users,
        auths,
        search,
        //websites
    }
});



