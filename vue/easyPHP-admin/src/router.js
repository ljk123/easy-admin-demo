import Vue from 'vue'
import Router from 'vue-router'
import store from './store/index'
import Full from '@/containers/Full'
// 把下面的路由 作懒加载处理
const Login = () => import('@/views/Login.vue')
const Dashboard = () => import('@/views/dashboard/Dashboard.vue')
const NotFind = () => import('@/views/404.vue')

Vue.use(Router)

const router = new Router({
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
          path: '/article/lists',
          name: 'Article',
          component: ()=>import('@/views/article/Article.vue'),
          meta: {
            title: '文章管理',
            breadcrumb: '内容管理',
            requireLogin: true
          }
        },
        {
          path: '/article/cate',
          name: 'ArticleCate',
          component: ()=>import('@/views/article/Cate.vue'),
          meta: {
            title: '分组管理',
            breadcrumb: '分组管理',
            requireLogin: true
          }
        },
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
