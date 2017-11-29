const state = {
    title:'common'
};
const actions = {
    SetTitle({ commit }, data) {
        commit('set_title',data);
    },
    /*ResetUsers({ commit }) {
        commit('reset_users');
    }*/
};

const getters = {
    title: state => state.title,
    //default_users: state => state.default_users
};

const mutations = {
    set_title: (state, data) => {
        state.title = data;
    },
    /*reset_users: state => {
        for(let i in state.default_users){
            if(state.default_users.hasOwnProperty(i) && state.users.hasOwnProperty(i)){
                state.users[i] = state.default_users[i];
            }
        }
    }*/
};

export default {
    namespaced:true,
    state,
    actions,
    getters,
    mutations
}
