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
  your_room_id:false,
  your_room_master_user:{
    id:0,
    username:"",
    name:""
  },
  your_room_guest_user:{
    id:0,
    username:"",
    name:""
  },
  your_room_is_playing:false

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
  Exit({commit}){
    return new Promise((resolve, reject) => {

      axios.post(
        '/room/exit'+'?access_token='+this.getters['auths/token']
      )
        .then((res) => {
          if(res.data.success){
            commit('ExitRoom');
          }

          resolve(res.data);
        })
        .catch(error => {
          reject(error);
        });
    });
  },
  IsInRoom({commit}){
    return new Promise((resolve, reject) => {

      axios.post(
        '/room/is-in-room'+'?access_token='+this.getters['auths/token'],
      )
        .then((res) => {

          if(res.data.success){
            commit('SetRoomId',res.data.data.room_id);
          }else{
            commit('ClearRoom');
          }

          resolve(res.data);
        })
        .catch(error => {
          reject(error);
        });
    });
  },
  GetRoomInfo({commit},room_id=this.getters['rooms/your_room_id']){
    return new Promise((resolve, reject) => {
      axios.post(
        '/room/get-room-info'+'?access_token='+this.getters['auths/token'],
        {
          room_id:room_id
        }
      )
        .then((res) => {

          if(res.data.success){
            commit('SetRoomInfo',res.data.data);
          }else{
            commit('ClearRoomInfo');
          }

          resolve(res.data);
        })
        .catch(error => {
          reject(error);
        });
    });
  },
  DoReady({commit},room_id=this.getters['rooms/your_room_id']){
    return new Promise((resolve, reject) => {

      axios.post(
        '/room/do-ready'+'?access_token='+this.getters['auths/token'],
        {
          room_id:room_id
        }
      )
        .then((res) => {

          if(res.data.success){
            //commit('SetRoomUser',res.data.data);
          }else{
            //commit('ClearRoomUser');
          }

          resolve(res.data);
        })
        .catch(error => {
          reject(error);
        });
    });
  },
  StartGame({commit},room_id=this.getters['rooms/your_room_id']){
    return new Promise((resolve, reject) => {

      axios.post(
        '/room/start-game'+'?access_token='+this.getters['auths/token'],
        {
          room_id:room_id
        }
      )
        .then((res) => {

          if(res.data.success){
            commit('SetRoomIsPlaying');
            //commit('SetRoomUser',res.data.data);
          }else{
            //commit('ClearRoomUser');
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
        '/room',
        {
          params:{
            access_token : this.getters['auths/token']
          }
        }
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
  your_room_id:state=>state.your_room_id,
  your_room_master_user:state=>state.your_room_master_user,
  your_room_guest_user:state=>state.your_room_guest_user,
  your_room_is_playing:state=>state.your_room_is_playing,
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
  },
  ExitRoom(state){
    state.your_room_id = false;
  },
  ClearRoom(state){
    state.your_room_id = false;
  },
  SetRoomInfo(state, data){
    state.your_room_master_user = data.master_user;
    state.your_room_guest_user = data.guest_user;
    state.your_room_is_playing = data.is_playing;
  },
  SetRoomIsPlaying(state){
    state.your_room_is_playing = true;
  },
  ClearRoomUser(state){
    state.your_room_master_user = {
      id:0,
      username:"",
      name:""
    };
    state.your_room_guest_user = {
      id:0,
      username:"",
      name:""
    };
  }
};

export default {
    namespaced:true,
    state,
    actions,
    getters,
    mutations
}
