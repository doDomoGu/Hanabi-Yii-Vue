import axios from '../../axios'

const state = {
  room_id:0,
  master_user:{
    id:0,
    username:"",
    name:""
  },
  guest_user:{
    id:0,
    username:"",
    name:""
  },
};

const actions = {
  Enter({commit},room_id){
    return new Promise((resolve, reject) => {
      axios.post(
        '/my-room/enter'+'?access_token='+this.getters['auth/token'],
        {
          room_id:room_id
        }
      )
      .then((res) => {
        if(res.data.success){
          commit('SetRoomId',room_id);
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
        '/my-room/exit'+'?access_token='+this.getters['auth/token']
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
        '/my-room/is-in-room'+'?access_token='+this.getters['auth/token']
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
  GetRoomInfo({commit}){
    return new Promise((resolve, reject) => {
      axios.post(
        '/my-room/get-info'+'?access_token='+this.getters['auth/token']
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
  DoReady({commit}){
    return new Promise((resolve, reject) => {

      axios.post(
        '/my-room/do-ready'+'?access_token='+this.getters['auth/token']
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
};

const getters = {
  room_id:state=>state.room_id,
  master_user:state=>state.master_user,
  guest_user:state=>state.guest_user,
};

const mutations = {
  SetRoomId(state, room_id){
    state.room_id = room_id;
  },
  ExitRoom(state){
    state.room_id = 0;
  },
  ClearRoom(state){
    state.room_id = 0;
  },
  SetRoomInfo(state, data){
    state.master_user = data.master_user;
    state.guest_user = data.guest_user;
  },
  ClearRoomUser(state){
    state.master_user = {
      id:0,
      username:"",
      name:""
    };
    state.guest_user = {
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
