<template>
  <el-container class="dealworks-container">
    <el-header class="dealworks-header">ArtGallery</el-header>
    <i-sider-bar></i-sider-bar>
    <el-main class="dealworks-main"> 
      <feed-back></feed-back>
      <router-view></router-view>
    </el-main>
  </el-container>
</template>

<script>
import FeedBack from "../../userSystem/feedback/feedback.vue";
import iSiderBar from "../../common/iSiderBar.vue";

export default {
  name: "DealWorks",
  components: {
    FeedBack,
    iSiderBar
  }
};
</script>

<style scoped>
.dealworks-container {
  background-color: honeydew;
}
.dealworks-header {
  text-align: center;
  font-size: 60px;
  margin: 50px 0px 0px 0px;
  color: black;
}
.dealworks-main {
  border: none;
  margin: 30px 400px;
}
</style>
