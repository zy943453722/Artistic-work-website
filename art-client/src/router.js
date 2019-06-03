/**
 * @description route
 */

import Router from "vue-router";
import Vue from 'vue';

Vue.use(Router);

export default new Router({
    mode: 'history',
    routes: [{
        path: '/',
        name: 'Home',
        component: resolve => require(['@/pages/home/home.vue'], resolve)
      },
      {
        path: '/works',
        name: 'Works',
        component: resolve => require(['@/pages/home/works.vue'], resolve),
      }, 
      {
        path: '/about',
        name: 'About',
        component: resolve => require(['@/pages/about/about.vue'], resolve)
      },
      {
        path: '/about/terms',
        name: 'Terms',
        component: resolve => require(['@/pages/about/terms.vue'], resolve)
      },
      {
        path: '/login',
        name: 'Login',
        component: resolve => require(['@/pages/userSystem/login/login.vue'], resolve)
      },
      {
        path: '/register',
        name: 'Register',
        component: resolve => require(['@/pages/userSystem/register/register.vue'], resolve)
      },
      {
        path: '/logout',
        name: 'Logout',
        component: resolve => require(['@/pages/userSystem/logout/logout.vue'], resolve)
      },
      {
        path: '/forgotPassword',
        name: 'ForgotPassword',
        component: resolve => require(['@/pages/userSystem/forgotPassword/forgot.vue'], resolve)
      },
      {
        path: '/forgotPassword/reset',
        name: 'ResetPassword',
        component: resolve => require(['@/pages/userSystem/resetPassword/reset.vue'], resolve)
      },
      {
        path: '/uid/:id',
        name: 'UserHome',
        component: resolve => require(['@/pages/user/userHome.vue'], resolve),
        children: [
          {
            path: '',
            name: 'UserWorks',
            component: resolve => require(['@/pages/user/works/userWorks.vue'], resolve),
          },
          {
            path: 'about',
            name: 'UserAbout',
            component: resolve => require(['@/pages/user/about/userAbout.vue'], resolve)
          },
          {
            path: 'ilike',
            name: 'UserILike',
            component: resolve => require(['@/pages/user/ilike/userILike.vue'], resolve)
          },
          {
            path: 'likeme',
            name: 'UserLikeme',
            component: resolve => require(['@/pages/user/likeme/userLikeme.vue'], resolve)
          },
          {
            path: 'comments',
            name: 'UserComments',
            component: resolve => require(['@/pages/user/comments/userComments.vue'], resolve)
          }
        ]
      },
      {
        path: '/setting',
        name: 'Setting',
        component: resolve => require(['@/pages/setting/setting.vue'], resolve),
        children: [
        {
          path: 'basics',
          name: 'BasicSetting',
          component: resolve => require(['@/pages/setting/basicSetting.vue'], resolve)
        },
        {
          path: 'password',
          name: 'PasswordSetting',
          component: resolve => require(['@/pages/setting/passwordSetting.vue'], resolve)
        }
      ]
      },
      {
        path: '/connection',
        name: 'Connection',
        component: resolve => require(['@/pages/connection/connection.vue'], resolve),
        children: [
        {
          path: 'follow',
          name: 'Follow',
          component: resolve => require(['@/pages/connection/follow.vue'], resolve)
        },
        {
          path: 'fans',
          name: 'Fans',
          component: resolve => require(['@/pages/connection/fans.vue'], resolve)
        }
      ]
      },
      {
        path: '/artid/:id',
        name: 'Art',
        component: resolve => require(['@/pages/works/works/art.vue'], resolve),
      },
      {
        path: 'xxxx',
        name: 'Empty',
        component: resolve => require(['@/pages/common/empty.vue'], resolve)
      },
      {
        path: 'yyy',
        name: 'UserEmpty',
        component: resolve => require(['@/pages/common/userEmpty.vue'], resolve)
      },
      {
        path: '/uid/:id/works',
        name: 'DealWorks',
        component: resolve => require(['@/pages/works/dealWorks/dealWorks.vue'], resolve),
        children: [
          {
            path: 'write',
            name: 'UploadWorks',
            component: resolve => require(['@/pages/works/dealWorks/uploadWorks.vue'], resolve)
          },{
            path: 'edit',
            name: 'EditWorks',
            component: resolve => require(['@/pages/works/dealWorks/editWorks.vue'], resolve)
          }
        ]
      }
    ]
});