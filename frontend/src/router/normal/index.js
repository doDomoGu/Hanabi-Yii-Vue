import Main from '../../views/layouts/main';
import Index from '../../views/Index'
import About from '../../components/About'
import Login from '../../views/Login'
import NoAuth from '../../views/NoAuth'

var routes = [{
    path: '/',
    component: Main,
    meta: {
        requireAuths: true,
        requireRoles: '*'
    },
    children: [
        {
            path: '',
            name: '首页',
            component: Index,
            meta: {
                requireAuths: true,
                requireRoles: '*'
            }
        },
        {
            path: 'no-auth',
            name: '没有权限',
            component: NoAuth,
            meta: {
                requireAuths: true,
                requireRoles: '*'
            }
        },
        {
            path: 'about',
            component: About,
            name: '关于我们',
            //hidden: true //hidden为自定义属性，侧边栏那章会纤细解释
        },

    ]
},
{
    path: '/login',
    component: Login,
    name: '登录页',
},
{
    path: 'logout',
    name: '登出',
    meta: {
        requireAuths: true,
        requireRoles: '*'
    }
}];

export default routes;