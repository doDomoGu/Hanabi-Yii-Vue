import axios from '../../axios'

const state = {
  game_id:0,
  master_user_hand_cards:[],
  guest_user_hand_cards:[],
};

const actions = {
  Start({commit}){
    return new Promise((resolve, reject) => {
      axios.post(
        '/my-game/start'+'?access_token='+this.getters['auth/token']
      )
      .then((res) => {
        if(res.data.success){
          commit('SetGameId',res.data.data.game_id);
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
  GetGameInfo({commit},game_id=this.getters['my_game/game_id']){
    return new Promise((resolve, reject) => {
      axios.post(
        '/my-game/get-info'+'?access_token='+this.getters['auth/token']
      )
      .then((res) => {
        if(res.data.success){
          commit('SetInfo',res.data.data);
        }else{
          alert('222');
          //commit('ClearInfo');
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
  game_id:state=>state.game_id,
  master_user_hand_cards:state=>state.master_user_hand_cards,
  guest_user_hand_cards:state=>state.guest_user_hand_cards,

};

const mutations = {
  SetGameId(state, game_id){
    state.game_id = game_id;
  },
  SetInfo(state, data){
    state.master_user_hand_cards = data.master_user_hand_cards;
    state.guest_user_hand_cards = data.guest_user_hand_cards;
  },
  ClearInfo(state){
    state.game_id = 0;
    state.master_user_hand_cards = [];
    state.guest_user_hand_cards = [];
  },
};

export default {
    namespaced:true,
    state,
    actions,
    getters,
    mutations
}
