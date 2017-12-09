import axios from '../../axios'

const state = {
  is_login: false,
  user_id: 0,
  user_info:{},
  token: '',


  //roles: [],
  //routes: [],

  //add_routes: []
};

const actions = {
  Login({commit }, [username,password]) {
    return new Promise((resolve, reject) => {
      axios.post(
        '/auth',
        {
          username: username,
          password: password
        }
      )
      .then((res) => {
        if(res.data && res.data.success){
          commit('setToken',{token:res.data.token,forceUpdate:true});
          commit('setLoginState');
          commit('setUserId',{user_id:res.data.user_id});
          commit('setUserInfo',{user_info:res.data.user_info});
        }
        resolve(res);
      })
      .catch(error => {
        reject(error);
      });
    });
  },
  CheckToken({commit},token){
    return new Promise((resolve, reject) => {
      axios.get(
        '/auth',
        {
          params: {
            access_token: token
          }
        }
      )
      .then((res) => {
        if(res.data && res.data.success) {
          commit('setToken',{token:res.data.token});
          commit('setLoginState');
          commit('setUserId',{user_id:res.data.user_id});
          commit('setUserInfo',{user_info:res.data.user_info});
        }else{
          //提交的token 错误
          commit('cleanLoginState');
        }
        resolve(res);
      })
      .catch(error => {
        reject(error);
      });
    });
  },
  Logout({ dispatch,commit}){
    return new Promise((resolve, reject) => {
      dispatch('room/Exit',null,{root:true}).then(()=>{
        axios.delete(
          '/auth',
          {
            params: {
              access_token: this.getters['auth/token']
            }
          }
        )
          .then((res) => {
            if(res.data && res.data.success) {
              commit('cleanLoginState');
            }
            resolve(res);
          })
          .catch(error => {
            reject(error);
          });
      });
    })
  },

    SetRoutes({commit},routes){

        let getRoutes = function(path,_routes){
            //console.log(path,_routes);
            let ret = [];
            if(_routes.length>0){
                //let path2 = path;
                if(path!=='' && path!=='/'){
                    path = path + '/';
                }

                for(let i in _routes){

                    //ret.push(_routes[i]);
                    if(_routes[i].path==='*'){
                        ret['*'] = _routes[i].meta;
                    }else{
                        ret[path + _routes[i].path] = _routes[i].meta;

                        if(_routes[i].children && _routes[i].children.length>0){
                            let children = getRoutes(path + _routes[i].path,_routes[i].children);
                            for(let j in children){
                                ret[j] = children[j];
                            }
                        }
                    }
                }
            }
//console.log(ret);
            return ret;

        };

        let data = getRoutes('',routes);




        commit('setRoutes',data);
    }
};

const getters = {
  token: state => state.token,
  user_id: state => state.user_id,
  user_info: state => state.user_info,
  is_login: state => state.is_login,

  //routes: state => state.routes
};

const mutations = {
  setToken: (state, data) => {
    state.token = data.token;
    if(data.forceUpdate) {
        localStorage.__HANABI_AUTH_TOKEN__ = data.token;
    }
  },
  setLoginState: (state) => {
    state.is_login = true;
  },
  setUserId: (state, data) => {
    state.user_id = data.user_id;
  },
  setUserInfo: (state, data) => {
    state.user_info = data.user_info;
  },



    /*setRoles: (state, data) => {
        state.roles = data.roles;
    },*/
    /*setAddRoutes: (state, data) => {
        state.add_routes = data;
    },*/
    cleanLoginState: (state) => {
        state.is_login = false;
        state.user_id = 0;
        state.user_info = {};
        state.token = '';
        //state.roles = [];
        localStorage.removeItem('__HANABI_AUTH_TOKEN__');
    },
    setIsLogin: (state,isLogin) => {
        state.is_login = isLogin;
    },
    setRoutes: (state,data) => {
        state.routes = data;
    }
};

export default {
    namespaced:true,
    state,
    actions,
    getters,
    mutations
}
