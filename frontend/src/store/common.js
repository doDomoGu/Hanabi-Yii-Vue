const state = {
  title:'Hanabi',
  title_suffix:'Hanabi'
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
    title_suffix: state => state.title_suffix,
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
