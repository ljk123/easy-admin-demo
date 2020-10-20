import Vue from 'vue'
import Router from 'vue-router'
import store from './store/index'
import Full from '@/containers/Full'
// 把下面的路由 作懒加载处理
const Login = () => import('@/views/Login.vue')
const Dashboard = () => import('@/views/dashboard/Dashboard.vue')
const Diagram = () => import(/* webpackChunkName: "charts" */ '@/views/charts/Diagram.vue')
const Slider = () => import(/* webpackChunkName: "dashboard" */ '@/views/charts/Slider.vue')
const Tables = () => import(/* webpackChunkName: "tables" */ '@/views/tables/Tables.vue')
const List = () => import(/* webpackChunkName: "tables" */ '@/views/tables/list/List.vue')
const Details = () => import(/* webpackChunkName: "tables" */ '@/views/tables/details/Details.vue')
const Setting = () => import(/* webpackChunkName: "tables" */ '@/views/setting/Setting.vue')
const NotFind = () => import(/* webpackChunkName: "home" */ '@/views/404.vue')

Vue.use(Router)

// const asyncRouter=[
//     {
//       path: '/',
//       redirect: '/dashboard',
//       name: 'Full',
//       component: Full,
//       meta: {
//         breadcrumb: '首页',
//         requireLogin: true
//       },
//       children: [
//         {
//           path: '/dashboard',
//           name: 'Dashboard',
//           component: Dashboard,
//           meta: {
//             breadcrumb: '概览',
//             title: '概览',
//             requireLogin: true
//           }
//         },
//         {
//           path: '/charts/diagram',
//           name: 'Diagram',
//           component: Diagram,
//           meta: {
//             breadcrumb: '图表一',
//             title: 'Diagram',
//             requireLogin: true
//           }
//         },
//         {
//           path: '/charts/slider',
//           name: 'Slider',
//           component: Slider,
//           meta: {
//             breadcrumb: '图表二',
//             title: 'Slider',
//             requireLogin: true
//           }
//         },
//         {
//           path: '/tables',
//           redirect: '/tables/list',
//           name: 'Tables',
//           component: Tables,
//           meta: {
//             breadcrumb: '表格',
//             requireLogin: true
//           },
//           children: [
//             {
//               path: '/tables/list',
//               name: 'List',
//               component: List,
//               meta: {
//                 title: 'list',
//                 breadcrumb: '列表',
//                 requireLogin: true
//               }
//             },
//             {
//               path: '/tables/details',
//               name: 'Details',
//               component: Details,
//               meta: {
//                 title: 'details',
//                 breadcrumb: '详情',
//                 requireLogin: true
//               }
//             }
//           ]
//         },
//         {
//           path: '/setting',
//           name: 'Setting',
//           component: Setting,
//           meta: {
//             breadcrumb: '设置',
//             requireLogin: true
//           },
//         }],
//         }
// ]

//todo addrouter 可以多添加个Full redirect到dashboard
const router = new Router({
  // mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      redirect: '/dashboard',
      name: 'Full',
      component: Full,
      meta: {
        breadcrumb: '首页',
        requireLogin: true
      },
      children: [
          {
            path: '/dashboard',
            name: 'Dashboard',
            component: Dashboard,
            meta: {
              breadcrumb: '概览',
              title: '概览',
              requireLogin: true
            }
          },
        {
          path: '/users/user',
          name: 'User',
          component: ()=>import('@/views/users/User.vue'),
          meta: {
            title: '账号管理',
            breadcrumb: '账号',
            requireLogin: true
          }
        },
        {
          path: '/users/group',
          name: 'Group',
          component: ()=>import('@/views/users/Group.vue'),
          meta: {
            title: '分组管理',
            breadcrumb: '分组',
            requireLogin: true
          }
        },
        
        
        
        
        
        
        
        
        
        {
          path: '/charts/diagram',
          name: 'Diagram',
          component: Diagram,
          meta: {
            breadcrumb: '图表一',
            title: 'Diagram',
            requireLogin: true
          }
        },
        {
          path: '/charts/slider',
          name: 'Slider',
          component: Slider,
          meta: {
            breadcrumb: '图表二',
            title: 'Slider',
            requireLogin: true
          }
        },
        {
          path: '/tables',
          redirect: '/tables/list',
          name: 'Tables',
          component: Tables,
          meta: {
            breadcrumb: '表格',
            requireLogin: true
          },
          children: [
            {
              path: '/tables/list',
              name: 'List',
              component: List,
              meta: {
                title: 'list',
                breadcrumb: '列表',
                requireLogin: true
              }
            },
            {
              path: '/tables/details',
              name: 'Details',
              component: Details,
              meta: {
                title: 'details',
                breadcrumb: '详情',
                requireLogin: true
              }
            }
          ]
        },
        {
          path: '/setting',
          name: 'Setting',
          component: Setting,
          meta: {
            breadcrumb: '设置',
            requireLogin: true
          },
        }
        
      ]
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
      meta: {
        title: '登录'
      }
    },
    {
      path: '*',
      name: 'NotFind',
      component: NotFind,
      meta: {
        title: '404'
      }
    }
  ]
})
const hasPermission = (path) => {
    let has=true
    store.getters.menuTree.forEach(item=>{
        if(has)
        {
           return 
        }
        has=item.link===path
        if(!has && item.sub)
        {
            item.sub.forEach(sub=>{
                if(has)
                {
                   return 
                }
                has=sub.link===path
            })
        }
    })
    return has
}

// 页面刷新时，重新赋值token
if (localStorage.getItem('token')) {
  store.commit('BIND_LOGIN', localStorage.getItem('token'))
}
// 全局导航钩子
router.beforeEach((to, from, next) => {
  if (to.meta.title) { // 路由发生变化修改页面title
    document.title = to.meta.title
  }
  if(store.getters.token && store.getters.menuTree.length===0)
  {
      store
        .dispatch("LoadMenu")
        .then((resposon) => {
            let data=resposon.data
            if(data.status===1)
            {
                store.commit('SET_MENU_TREE',data.data)
                next({ ...to, replace: true });
            }
            else{
                next("/");
            }
          
        })
        .catch(() => {
          next("/");
        });
  }
  else if (to.meta.requireLogin) {
    if (store.getters.token) {
      let redirect = to.path
      if (Object.keys(from.query).length > 0 && redirect !== from.query.redirect) { 
        redirect = from.query.redirect
        next({ path: redirect })
      } 
      else if(hasPermission(to.path)){
        next()
      }
      else{
        //没权限
        next({ ...from, replace: true });
      }
      
    } else {
      next({
        path: '/login',
        query: { redirect: to.fullPath } // 将跳转的路由path作为参数，登录成功后跳转到该路由
      })
    }
  } else {
      if (to.path==='/login' && store.getters.token) {
          let redirect = from.query.redirect || '/dashboard' // 如果来源路由有query
          if (to.path === redirect) { // 避免 next 无限循环
              next()
          } else {
              next({ path: redirect }) // 跳转到目的路由
          }
      }
      else{
          next()
      }
  }
})

export default router
