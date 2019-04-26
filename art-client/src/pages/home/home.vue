<template>
  <el-container>
    <el-header>
      <home-header></home-header>
    </el-header>
        <home-title></home-title>
    <el-container>
      <el-aside>
        <sider-bar></sider-bar>
      </el-aside>
      <el-main>
          
      </el-main>
    </el-container>
    <el-footer></el-footer>
  </el-container>
</template>

<script>
import HomeHeader from "./components/header.vue";
import HomeTitle from "./components/title.vue";
import SiderBar from '../common/siderBar.vue';

export default {
  name: "Home",
  components: {
    HomeHeader,
    HomeTitle,
    SiderBar
  }
};
</script>

<style scoped>

</style>

