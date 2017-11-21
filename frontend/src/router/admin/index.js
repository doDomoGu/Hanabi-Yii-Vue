import Main from '../../views/layouts/main';
import User from '../../views/admin/user'
import Usergroup from '../../views/admin/usergroup'
import Website from '../../views/admin/website'

var routes = {
    path: '/admin',
    name: '后台管理',
    meta: {
        requireAuths: true,
        requireRoles: ['user_admin','website_admin']
    },
    component: Main,
    children: [
        {
            path: 'user',
            name: '用户',
            component: User,
            meta: {
                requireAuths: true,
                requireRoles: ['user_admin']
            }
        },
        {
            path: 'usergroup',
            name: '用户组',
            component: Usergroup,
            meta: {
                requireAuths: true,
                requireRoles: ['user_admin']
            }
        },
        {
            path: 'website',
            name: '网站',
            component: Website,
            meta: {
                requireAuths: true,
                requireRoles: ['website_admin']
            }
        }
    ]
};

export default routes;