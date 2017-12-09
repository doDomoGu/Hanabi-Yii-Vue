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
  is_playing:false
};

const actions = {
  Enter({commit},params){
    return new Promise((resolve, reject) => {
      axios.post(
        '/room/enter'+'?access_token='+this.getters['auth/token'],
        {
          room_id:params.room_id
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
        '/room/exit'+'?access_token='+this.getters['auth/token']
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
        '/room/is-in-room'+'?access_token='+this.getters['auth/token']
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
  GetRoomInfo({commit},room_id=this.getters['my_room/room_id']){
    return new Promise((resolve, reject) => {
      axios.post(
        '/room/get-room-info'+'?access_token='+this.getters['auth/token'],
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
  DoReady({commit},room_id=this.getters['my_room/room_id']){
    return new Promise((resolve, reject) => {

      axios.post(
        '/room/do-ready'+'?access_token='+this.getters['auth/token'],
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
  StartGame({commit},room_id=this.getters['my_room/room_id']){
    return new Promise((resolve, reject) => {

      axios.post(
        '/room/start-game'+'?access_token='+this.getters['auth/token'],
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

};

const getters = {
  room_id:state=>state.room_id,
  master_user:state=>state.master_user,
  guest_user:state=>state.guest_user,
  is_playing:state=>state.is_playing
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
    state.is_playing = data.is_playing;
  },
  SetRoomIsPlaying(state){
    state.is_playing = true;
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
