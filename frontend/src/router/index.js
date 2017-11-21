import Vue from 'vue'

import Router from 'vue-router'

import store from '../store'

import NormalRoutes from './normal'
import AdminRoutes from './admin'
import NotFound404Routes from './NotFound404'


Vue.use(Router);

//console.warn('1. in router');

let routes = [];

//载入通用路由
for(let i in NormalRoutes){
    routes.push(NormalRoutes[i]);
}

/*routes.push(NormalRoutes);*/

//载入admin相关路由
routes.push(AdminRoutes);

//最后载入404页面
routes.push(NotFound404Routes);

store.dispatch('auths/SetRoutes',routes);

const router = new Router({
    mode:'history',
    routes
});

//console.warn('2. beforeEach router');

//console.log(router);

//let beCount = 0;

router.beforeEach((to, from, next) => {
    //console.log('  ');
    //beCount++;

    //console.warn('3. '+beCount+' path :'+ to.fullPath);

    if (to.path === "/logout") {

        store.dispatch('auths/Logout').then(() => {

            next({path: '/login'});

        })

    } else {

        //if (router.meta..routePathsNotRequiredAuth.indexOf(to.path) !== -1) { // 在路由免登录白名单，直接进入
        if (!(to.meta.requireAuths)) {

            next();

        }else{
            let isLogin = store.getters['auths/is_login'];

            if(isLogin){

                //权限验证
                let authFlag = false;
                let requireRoles = to.meta.requireRoles;
                let userRoles = store.getters['auths/roles'];

                if(requireRoles === '*' || userRoles.includes('super_admin')){
                    authFlag = true;
                }else{
                    for(let i in requireRoles){
                        if(authFlag === false && userRoles.includes(requireRoles[i])){
                            authFlag = true;
                        }
                    }
                }
                if(authFlag){
                    next();
                }else{
                    next({path:'/no-auth'});
                }
            }else {
                let tokenInLocalStorage = localStorage.__WPC_AUTH_TOKEN__;

                if (typeof(tokenInLocalStorage)==='string' && tokenInLocalStorage !=='') {

                    //console.warn(' checkToken start ',new Date().getTime());

                    store.dispatch('auths/CheckToken',tokenInLocalStorage).then(() => {

                        //console.warn(' checkToken finish',new Date().getTime());

                        if(store.getters['auths/is_login']){
                            next(to.path);
                        }else{
                            //console.log('token 222');
                            next('/login');
                        }
                    });
                }else {
                    //next({path: '/login', query: {redirectUrl: to.fullPath}});
                    next({path: '/login',query:{redirectUrl:to.fullPath}});
                }
            }
        }
    }
});

export default router;