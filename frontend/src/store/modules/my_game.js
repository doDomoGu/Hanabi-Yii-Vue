import axios from '../../axios'

const state = {
  game_id:0,
  master_user_hand_cards:[],
  guest_user_hand_cards:[],
  round_player:0,
  library_cards_num:0,
  discard_cards_num:0,
  cue_num:0,
  chance_num:0,
  table_cards:[]
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
  End({commit}){
    return new Promise((resolve, reject) => {
      axios.post(
        '/my-game/end'+'?access_token='+this.getters['auth/token']
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
  GetGameInfo({commit}){
    return new Promise((resolve, reject) => {
      axios.post(
        '/my-game/get-info'+'?access_token='+this.getters['auth/token']
      )
      .then((res) => {
        if(res.data.success){
          commit('SetGameInfo',res.data.data.game);
          commit('SetCardInfo',res.data.data.card);
        }else{
          //commit('ClearInfo');
        }

        resolve(res.data);
      })
      .catch(error => {
        reject(error);
      });
    });
  },
  IsInGame({commit}){
    return new Promise((resolve, reject) => {
      axios.post(
        '/my-game/is-in-game'+'?access_token='+this.getters['auth/token']
      )
      .then((res) => {
        if(res.data.success){
          commit('SetGameId',res.data.data.game_id);
        }else{
          commit('ClearInfo');
        }

        resolve(res.data);
      })
      .catch(error => {
        reject(error);
      });
    });
  },
  DoDiscard({commit},cardSelectOrd){
    return new Promise((resolve, reject) => {
      axios.post(
        '/my-game/do-discard'+'?access_token='+this.getters['auth/token'],
        {
          cardSelectOrd:cardSelectOrd
        }
      )
      .then((res) => {
        if(res.data.success){
          //commit('SetGameId',res.data.data.game_id);
        }else{
          //commit('ClearInfo');
        }

        resolve(res.data);
      })
      .catch(error => {
        reject(error);
      });
    });
  }

};

const getters = {
  game_id : state=>state.game_id,
  master_user_hand_cards : state=>state.master_user_hand_cards,
  guest_user_hand_cards : state=>state.guest_user_hand_cards,
  round_player : state=>state.round_player,
  library_cards_num : state=>state.library_cards_num,
  discard_cards_num : state=>state.discard_cards_num,
  cue_num : state=>state.cue_num,
  chance_num : state=>state.chance_num,
  table_cards : state=>state.table_cards
};

const mutations = {
  SetGameId(state, game_id){
    state.game_id = game_id;
  },
  SetCardInfo(state,data){
    state.master_user_hand_cards = data.master_hands;
    state.guest_user_hand_cards = data.guest_hands;
    state.library_cards_num = data.library_cards_num;
    state.discard_cards_num = data.discard_cards_num;
    state.cue_num = data.cue_num;
    state.chance_num = data.chance_num;
    state.table_cards = data.table_cards;
  },
  SetGameInfo(state, data){
    state.round_player = data.round_player;
  },
  ClearInfo(state){
    state.game_id = 0;
    state.master_user_hand_cards = [];
    state.guest_user_hand_cards = [];
    state.round_player = 0;
    state.library_cards_num = 0;
    state.discard_cards_num = 0;
    state.cue_num = 0;
    state.chance_num = 0;
    state.table_cards = [];
  },
};

export default {
    namespaced:true,
    state,
    actions,
    getters,
    mutations
}
