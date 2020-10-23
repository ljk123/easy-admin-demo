// axios 配置
import axios from 'axios'
import store from './store'
import router from './router'

axios.defaults.baseURL = '//easy-admin.mt234.cn';
//创建 axios 实例
let instance = axios.create({
  timeout: 5000, // 请求超过5秒即超时返回错误
  headers: { 'Content-Type': 'application/json;charset=UTF-8' },
})

instance.interceptors.request.use(
  config => {
    if (store.getters.token) { // 若存在token，则每个Http Header都加上token
      config.headers.Authorization = `${store.getters.token}`
    }
    return config
  },
  err => {
    return Promise.reject(err)
  })
  
  const message_map={
      'UNLOGIN':'请先登录',
      'NOAUTH':'您没有该权限',
  }

// http response 拦截器
instance.interceptors.response.use(
  response => {
    if(response.status!==200)
    {
        return Promise.reject(response) // 返回接口返回的错误信息
    }
    if(response.data.status === 0 )
    {
        if(response.data.msg === 'UNLOGIN')
        {
            store.commit('BIND_LOGOUT')
            router.replace({
                path: '/login',
                query: {
                  redirect: router.currentRoute.fullPath
                }
            })
        }
    }
    if(message_map[response.data.msg])
    {
        response.data.msg=message_map[response.data.msg]
    }
    return response
  },
  error => {
      if(!error.response.data.msg)
      {
          error.response.data={data:null,msg:"系统错误",status:0}
      }
    return Promise.reject(error.response) // 返回接口返回的错误信息
  }
)

export default {
  // 登录
  userLogin (data) {
    return instance.post('/admin/user/login', data)
  },
  userInfo(){
      return instance.get('/admin/user/info')
  },
  menu(){
      return instance.get('admin/user/menu')
  },
  allRoles(){
      return instance.get('/admin/role/index')
  },
  userLists(){
      return instance.get('/admin/user/lists')
  },
  saveUser(data){
      return instance.post('/admin/user/save',data)
  },
  delUser(id){
      return instance.post('/admin/user/del',{id})
  },
  //用户组
  userGroupLists(){
      return instance.get('/admin/group/index')
  },
  saveUserGroup(data){
      return instance.post('/admin/group/save',data)
  },
  delUserGroup(id){
      return instance.post('/admin/group/del',{id})
  },
  //文章
  articleLists(){
      return instance.get('/admin/article/lists')
  },
  saveArticle(data){
      return instance.post('/admin/article/save',data)
  },
  delArticle(id){
      return instance.post('/admin/article/del',{id})
  },
  //分类
  articleCateLists(){
      return instance.get('/admin/article/cate_lists')
  },
  saveArticleCate(data){
      return instance.post('/admin/article/save_cate',data)
  },
  delArticleCate(id){
      return instance.post('/admin/article/del_cate',{id})
  },
}
