<template>
  <div class="sidebar">
    <el-menu
      :default-active="activeIndex"
      router
      class="el-menu-vertical-demo"
      :collapse="isCollapse"
      background-color="#545c64"
      text-color="#fff"
      active-text-color="#ffd04b"
    >
    <template v-for="menu in this.$store.getters.menuTree" >
      <el-submenu :index="menu.link" v-if="menu.sub"  :key="menu.link">
        <template slot="title">
            <i :class="menu.icon"></i>
            <span slot="title">{{menu.name}}</span>
        </template>
        <el-menu-item :index="sub.link" v-for="sub in menu.sub" :key="sub.link">{{sub.name}}</el-menu-item>
      </el-submenu>
      <el-menu-item :index="menu.link" v-else :key="menu.link" >
        <i :class="menu.icon"></i>
        <span slot="title">{{menu.name}}</span>
      </el-menu-item>
    </template>
    </el-menu>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Sidebar',
  data () {
    return {
    }
  },
  computed: {
    ...mapGetters([
      'sidebar'
    ]),
    isCollapse () {
      return !this.sidebar.opened
    },
    activeIndex () {
      return this.$route.path
    }
  },
  methods: {
  }
}
</script>

<style scoped>
  .sidebar {
    background-color: rgb(84, 92, 100);
  }
  .el-menu-vertical-demo:not(.el-menu--collapse) {
    width: 200px;
    min-height: 100vh;
    height: 100%;
  }

  .el-menu-vertical-demo {
    min-height: 100vh;
    height: 100%;
  }

  .el-menu-item.is-active,
  .el-submenu.is-active >>> .el-submenu__title {
    background: #484e5c !important;
  }

  .el-submenu .el-menu-item {
    padding-left: 60px !important;
    min-width: auto;
  }
  .el-submenu.is-active .el-menu-item {
    background: #484e5c !important;
  }
  .el-submenu.is-active .el-menu-item.is-active  {
    background: #3a4046 !important;
  }
  .el-submenu .el-menu-item:hover {
    padding-left: 60px !important;
    background: rgb(67, 74, 80) !important;
  }
  .el-menu-item i {
    padding-bottom: 4px;
  }

  .el-submenu__title i {
    padding-bottom: 4px;
  }

  @media all and (max-width: 768px) {
    .el-menu--collapse {
      width: 0;
      overflow: hidden;
    }
  }


</style>
