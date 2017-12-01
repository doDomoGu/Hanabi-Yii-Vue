import * as types from './../types.js';
import axios from '../../axios'


const state = {
    count: 0,
    attributes:{
        /*id: 0,
        username: "",
        password: "",
        name:"",
        mobile: "",
        email: "",
        status: "1",
        verify: "1",
        usergroups: []*/
    },
    list:[]
};

const actions = {
  [types.LIST]({commit}){
    return new Promise((resolve, reject) => {

      axios.get(
        '/room'
      )
        .then((res) => {
          commit(types.LIST,res.data);
          resolve(res);
        })
        .catch(error => {
          reject(error);
        });
    });
  }
/*    [types.ADD]({ commit }, res) {
        commit(types.ADD, res);
    },
    [types.UPDATE]({ commit }, res) {
        commit(types.UPDATE, res);
    },
    [types.DELETE]({ commit }, res) {
        commit(types.DELETE, res);
    },*/


    /*,
    increment2 (context,obj) {
        context.commit('increment',obj)
    }*/
};

const getters = {

    attributes:state => state.attributes,
    list: state => state.list,
    getCount : state => state.count
};

const mutations = {
    /*[types.ADD](state, res) {
        state.list.push(res);
    },
    [types.DELETE](state, res) {

        state.list.push(res);
    },
    [types.UPDATE]( state, res) {
        state.list.push(res);
    },*/

    [types.LIST]( state, res) {
      state.list = res;
      state.count = res.length;
    }
};

export default {
    namespaced:true,
    state,
    actions,
    getters,
    mutations
}
