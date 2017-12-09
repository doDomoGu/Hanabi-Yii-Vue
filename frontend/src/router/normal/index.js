import Main from '../../views/layouts/main';
import Index from '../../views/Index'

import Login from '../../views/Login'
import NoAuth from '../../views/NoAuth'

import Room from '../../views/room/Index'
import Game from '../../views/game/Index'

var routes = [{
  path: '/',
  component: Main,
  /*meta: {
    requireAuth: true,
    requireRoles: '*'
  },*/
  children: [
    {
      path: '',
      name: '首页',
      component: Index,
      /*meta: {
        requireAuth: true,
        requireRoles: '*'
      }*/
    },
    {
      path: 'room/:room_id',
      name: '房间',
      component: Room,
      meta: {
        requireAuth: true,
        requireRoles: '*'
      }
    },
    {
      path: 'game',
      //path: 'game/:game_id',
      name: '游戏中',
      component: Game,
      meta: {
        requireAuth: true,
        requireRoles: '*'
      }
    },
    {
      path: 'no-auth',
      name: '没有权限',
      component: NoAuth,
      meta: {
        requireAuth: true,
        requireRoles: '*'
      }
    },
    {
      path: 'login',
      component: Login,
      name: '登录页',
    },
  ]
},
{
  path: 'logout',
  name: '登出',
  meta: {
    requireAuth: true,
    requireRoles: '*'
  }
}];

export default routes;