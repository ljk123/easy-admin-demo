import api from '@/api.js'

const user = {
  state: {
    token: localStorage.getItem('token'),
    menuTree:[],
  },

  mutations: {
    BIND_LOGIN: (state, data) => {
      localStorage.setItem('token', data)
      state.token = data
    },
    BIND_LOGOUT: (state) => {
      localStorage.removeItem('token')
      state.token = null
    },
    SET_MENU_TREE(state, menuTree) {
      state.menuTree = menuTree;
    },
  },

  actions: {
    LoadMenu(context){
        return api.menu()
    },
  }
}

export default user
