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
  list:[],
  your_room_id:false
};

const actions = {
  Enter({commit},params){
    return new Promise((resolve, reject) => {

      axios.post(
        '/room/enter'+'?access_token='+this.getters['auths/token'],
        {
          //access_token:this.getters['auths/token'],
          room_id:params.room_id,

        }
      )
        .then((res) => {
          if(res.data.success){
            commit('SetRoomId',res.data.data.room_id);
          }

          resolve(res.data);
        })
        .catch(error => {
          reject(error);
        });
    });
  },
  IsInRoom({commit},params){
    return new Promise((resolve, reject) => {

      axios.post(
        '/room/is-in-room'+'?access_token='+this.getters['auths/token'],
      )
        .then((res) => {

          if(res.data.success){
            commit('SetRoomId',res.data.data.room_id);
          }

          resolve(res.data);
        })
        .catch(error => {
          reject(error);
        });
    });
  },
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
  getCount : state => state.count,
  your_room_id:state=>state.your_room_id
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
    },
  SetRoomId(state, room_id){
      state.your_room_id = room_id;
  }
};

export default {
    namespaced:true,
    state,
    actions,
    getters,
    mutations
}
