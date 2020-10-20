const getters = {
    sidebar: state => state.app.sidebar,
    device: state => state.app.device,
    token: state => state.user.token,
    menuTree: state => state.user.menuTree,
  }
  export default getters
