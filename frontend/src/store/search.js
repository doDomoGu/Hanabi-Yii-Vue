const state = {
    users: {
        username: '',
        name: '',
        mobile:'',
        email:'',
        status:'',
        verify:''
    },
    default_users:{}
};
const actions = {
    UpdateUsers({ commit }, formData) {
        commit('update_users',formData);
    },
    ResetUsers({ commit }) {
        commit('reset_users');
    }
};

const getters = {
    users: state => state.users,
    default_users: state => state.default_users
};

const mutations = {
    update_users: (state, data) => {
        state.users = data;
    },
    reset_users: state => {
        for(let i in state.default_users){
            if(state.default_users.hasOwnProperty(i) && state.users.hasOwnProperty(i)){
                state.users[i] = state.default_users[i];
            }
        }
    }
};

export default {
    namespaced:true,
    state,
    actions,
    getters,
    mutations
}
