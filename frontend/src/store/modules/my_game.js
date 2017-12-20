import axios from '../../axios'

const state = {
  is_playing:false,
  host_hands:[],
  guest_hands:[],
  round_num:-1,
  round_player_is_host:false,
  library_cards_num:-1,
  discard_cards_num:-1,
  cue_num:-1,
  chance_num:-1,
  success_cards:[]
};

const actions = {
  Start({commit}){
    return new Promise((resolve, reject) => {
      axios.post(
        '/my-game/start'+'?access_token='+this.getters['auth/token']
      )
      .then((res) => {
        if(res.data.success){
          commit('SetGameIsPlaying');
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
            commit('ClearInfo')
            //commit('SetGameId',res.data.data.game_id);
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
          commit('SetGameIsPlaying');
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
          commit('SetGameIsPlaying');
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
  },
  DoPlay({commit},cardSelectOrd){
    return new Promise((resolve, reject) => {
      axios.post(
        '/my-game/do-play'+'?access_token='+this.getters['auth/token'],
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
  is_playing : state=>state.is_playing,
  host_hands : state=>state.host_hands,
  guest_hands : state=>state.guest_hands,
  round_num : state=>state.round_num,
  round_player_is_host : state=>state.round_player_is_host,
  library_cards_num : state=>state.library_cards_num,
  discard_cards_num : state=>state.discard_cards_num,
  cue_num : state=>state.cue_num,
  chance_num : state=>state.chance_num,
  success_cards : state=>state.success_cards
};

const mutations = {
  SetGameIsPlaying(state){
    state.is_playing = true;
  },
  SetCardInfo(state,data){
    state.host_hands = data.host_hands;
    state.guest_hands = data.guest_hands;
    state.library_cards_num = data.library_cards_num;
    state.discard_cards_num = data.discard_cards_num;
    state.cue_num = data.cue_num;
    state.chance_num = data.chance_num;
    state.success_cards = data.success_cards;
  },
  SetGameInfo(state, data){
    state.round_num = data.round_num;
    state.round_player_is_host = data.round_player_is_host;
  },
  ClearInfo(state){
    state.is_playing = false;
    state.host_hands = [];
    state.guest_hands = [];
    state.round_num = -1;
    state.round_player_is_host = false;
    state.library_cards_num = -1;
    state.discard_cards_num = -1;
    state.cue_num = -1;
    state.chance_num = -1;
    state.success_cards = [];
  },
};

export default {
    namespaced:true,
    state,
    actions,
    getters,
    mutations
}
