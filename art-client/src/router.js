/**
 * @description route
 */

import Router from "vue-router";
import Vue from 'vue';
import Home from '@/pages/home/home.vue';
import Works from '@/pages/home/works.vue';
import About from '@/pages/about/about.vue';
import Terms from '@/pages/about/terms.vue';
import Login from '@/pages/userSystem/login/login.vue';
import Register from '@/pages/userSystem/register/register.vue';
import Logout from '@/pages/userSystem/logout/logout.vue';
import ForgotPassword from '@/pages/userSystem/forgotPassword/forgot.vue';
import ResetPassword from '@/pages/userSystem/resetPassword/reset.vue';
import UserHome from '@/pages/user/userHome.vue';
import UserWorks from '@/pages/user/works/userWorks.vue';
import UserAbout from '@/pages/user/about/userAbout.vue';
import UserComments from '@/pages/user/comments/userComments.vue';
import UserILike from '@/pages/user/ilike/userILike.vue';
import UserLikeme from '@/pages/user/likeme/userLikeme.vue';
import Fans from '@/pages/connection/fans.vue';
import Connection from '@/pages/connection/connection.vue';
import Follow from '@/pages/connection/follow.vue';
import Setting from '@/pages/setting/setting.vue';
import BasicSetting from '@/pages/setting/basicSetting.vue';
import PasswordSetting from '@/pages/setting/passwordSetting.vue';
import Art from '@/pages/works/works/art.vue';
import DealWorks from '@/pages/works/dealWorks/dealWorks.vue';
import EditWorks from '@/pages/works/dealWorks/editWorks.vue';
import UploadWorks from '@/pages/works/dealWorks/uploadWorks.vue';

Vue.use(Router);

export default new Router({
    mode: 'history',
    routes: [{
        path: '/',
        name: 'Home',
        component: Home
      },
      {
        path: '/works',
        component: Works,
        name: 'Works'
      }, 
      {
        path: '/about',
        name: 'About',
        component: About
      },
      {
        path: '/about/terms',
        name: 'Terms',
        component: Terms
      },
      {
        path: '/login',
        name: 'Login',
        component: Login
      },
      {
        path: '/register',
        name: 'Register',
        component: Register
      },
      {
        path: '/logout',
        name: 'Logout',
        component: Logout
      },
      {
        path: '/forgotPassword',
        name: 'ForgotPassword',
        component: ForgotPassword
      },
      {
        path: '/forgotPassword/reset',
        name: 'ResetPassword',
        component: ResetPassword
      },
      {
        path: '/uid/:id',
        name: 'UserHome',
        component: UserHome,
        children: [
          {
            path: '',
            name: 'UserWorks',
            component: UserWorks,
          },
          {
            path: 'about',
            name: 'UserAbout',
            component: UserAbout
          },
          {
            path: 'ilike',
            name: 'UserILike',
            component: UserILike
          },
          {
            path: 'likeme',
            name: 'UserLikeme',
            component: UserLikeme
          },
          {
            path: 'comments',
            name: 'UserComments',
            component: UserComments
          }
        ]
      },
      {
        path: '/setting',
        name: 'Setting',
        component: Setting,
        children: [
        {
          path: 'basics',
          name: 'BasicSetting',
          component: BasicSetting
        },
        {
          path: 'password',
          name: 'PasswordSetting',
          component: PasswordSetting
        }
      ]
      },
      {
        path: '/connection',
        name: 'Connection',
        component: Connection,
        children: [
        {
          path: 'follow',
          name: 'Follow',
          component: Follow
        },
        {
          path: 'fans',
          name: 'Fans',
          component: Fans
        }
      ]
      },
      {
        path: '/artid/:id',
        name: 'Art',
        component: Art,
      },
      {
        path: '/uid/:id/works',
        name: 'DealWorks',
        component: DealWorks,
        children: [
          {
            path: 'write',
            name: 'UploadWorks',
            component: UploadWorks
          },{
            path: 'edit',
            name: 'EditWorks',
            component: EditWorks
          }
        ]
      }
    ]
});