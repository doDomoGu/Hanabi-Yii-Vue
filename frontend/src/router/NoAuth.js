import Main from '../views/layouts/main';
import NoAuth from '../views/NoAuth'

var routes = {
    path: '/',
    component: Main,
    children: [
        {
            path: '/no-auth',
            name: 'no-auth',
            component: NoAuth
        }
    ]
};

export default routes;