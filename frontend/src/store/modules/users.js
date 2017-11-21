import * as types from './../types.js';

const state = {
    count: 0,
    attributes:{
        id: 0,
        username: "",
        password: "",
        name:"",
        mobile: "",
        email: "",
        status: "1",
        verify: "1",
        usergroups: []
    },
    list:[]
};

const actions = {
    [types.ADD]({ commit }, res) {
        commit(types.ADD, res);
    },
    [types.UPDATE]({ commit }, res) {
        commit(types.UPDATE, res);
    },
    [types.DELETE]({ commit }, res) {
        commit(types.DELETE, res);
    },


    /*,
    increment2 (context,obj) {
        context.commit('increment',obj)
    }*/
};

const getters = {
    /*users_list: state => {
        if(state.list == ''){
            return localStorage.getItem('user_list');
        }else{
            return state.list;
        }
    },*/
    attributes:state => state.attributes,
    list: state => state.list,
    getCount : state => state.count
};

const mutations = {
    [types.ADD](state, res) {
        state.list.push(res);
    },
    [types.DELETE](state, res) {

        state.list.push(res);
    },
    [types.UPDATE]( state, res) {
        state.list.push(res);
    }
};

export default {
    namespaced:true,
    state,
    actions,
    getters,
    mutations
}
