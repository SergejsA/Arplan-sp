import Home from './pages/Home';
import Login from './pages/Login';
import Kopsavilkums from './pages/Kopsavilkums';
import Lietotaji from './pages/Lietotaji';
import Projekti from './pages/Projekti';
import Kontrole from './pages/Kontrole';
import ResetPassword from './pages/ResetPassword';
import FirstInit from './pages/FirstInit';

export default [
    {
        path: '/',
        component: Home,
        name: 'home'
    },
    {
        path: '/login',
        component: Login,
        name: "Login"
    },
    {
        path: '/kopsavilkums',
        component: Kopsavilkums,
        name: "Kopsavilkums"
    },
    {
        path: '/lietotaji',
        component: Lietotaji,
        name: "Lietotaji"
    },
    {
        path: '/projekti',
        component: Projekti,
        name: "Projekti"
    },
    {
        path: '/kontrole',
        component: Kontrole,
        name: "Kontrole"
    },
    {
        path: '/reset-password',
        component: ResetPassword,
        name: 'resetPassword'
    },
    {
        path: '/first-init',
        component: FirstInit,
        name: 'firstInit'
    }
]
