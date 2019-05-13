<template>
  <el-container class="setting-container">
    <el-header class="setting-header">设置</el-header>
    <i-sider-bar></i-sider-bar>
    <el-main class="setting-main">
        <feed-back></feed-back>
        <router-view></router-view>
    </el-main>
  </el-container>
</template>

<script>
import FeedBack from "../userSystem/feedback/feedback.vue";
import iSiderBar from "../common/iSiderBar.vue";

export default {
  name: "Setting",
  components: {
    FeedBack,
    iSiderBar
  },
  data () {
    return {

    }
  },
  methods: {

  }
};
</script>

<style scoped>
.setting-container {
  background-color: honeydew;
}
.setting-header {
  text-align: center;
  font-size: 60px;
  margin: 50px 0px 0px 0px;
  color: black;
}
.setting-main {
  border: none;
  margin: 30px 400px;
}
</style>

