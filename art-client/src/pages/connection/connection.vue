<template>
  <el-container class="connect-container">
    <el-header class="connect-header">联系人</el-header>
    <i-sider-bar></i-sider-bar>
    <el-main class="connect-main">
      <feed-back></feed-back>
      <router-view
        :key="this.$route.path"
        :followData="followData"
        :count="count"
        :mutualCount="mutualCount"
      ></router-view>
    </el-main>
  </el-container>
</template>

<script>
import FeedBack from "../userSystem/feedback/feedback.vue";
import iSiderBar from "../common/iSiderBar.vue";
import axios from "axios";

export default {
  name: "Connection",
  components: {
    FeedBack,
    iSiderBar
  },
  data() {
    return {
      followData: {},
      mutualCount: 0,
      count: 0
    };
  },
  watch: {
    '$route'(to) {
      this.getFollowing(to.path);
    }
  },
  methods: {
    refreshHandle: function() {
      axios({
        method: "put",
        url: "/api/users/updateToken",
        headers: {
          "x-artgallery-refreshToken": localStorage.refreshToken,
          "x-artgallery-pin": localStorage.pin
        }
      }).then(response => {
        if (response.status === 200) {
          if (response.data.errno === 10000) {
            localStorage.accessToken = response.data.data.accessToken;
            location.reload();
          } else {
            this.$message({
              message: response.data.errmsg,
              type: "warning"
            });
            this.$router.push({ name: "Home" });
          }
        } else if (response.status === 401) {
          this.$message({
            message: response.data.errmsg,
            type: "warning"
          });
          localStorage.removeItem("refreshToken");
          localStorage.removeItem("accessToken");
          localStorage.removeItem("pin");
          localStorage.removeItem("id");
          this.$router.push({ name: "Login" });
        } else {
          this.$message.error("服务器请求错误");
          this.$router.push({ name: "Home" });
        }
      });
    },
    getFollowing(path) {
      let status;
      if (path.slice(12) === "follow") {
        status = 0;
      } else {
        status = 1;
      }
      axios({
        method: "get",
        url: "/api/users/getFollowing",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken
        },
        params: {
          status: status
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.mutualCount = res.data.data.mutualCount;
            this.count = res.data.data.count;
            delete res.data.data.mutualCount;
            delete res.data.data.count;
            this.followData = res.data.data;
          } else if (res.data.errno === 40005) {
            this.refreshHandle();
          } else {
            this.$message({
              message: "参数有误",
              type: "warning"
            });
            return false;
          }
        } else if (res.status === 401) {
          this.$message({
            message: res.data.errmsg,
            type: "warning"
          });
          this.$router.push({ name: "Home" });
        } else {
          this.$message.error("服务器请求错误");
          return false;
        }
      });
    }
  },
  mounted() {
    this.getFollowing(this.$route.path);
  }
};
</script>

<style scoped>
.connect-container {
  background-color: honeydew;
}
.connect-header {
  text-align: center;
  font-size: 60px;
  margin: 50px 0px 0px 0px;
  color: black;
}
.connect-main {
  border: none;
  margin: 30px 400px;
}
</style>
 