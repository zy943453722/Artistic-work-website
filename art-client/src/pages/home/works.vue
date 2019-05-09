<template>
  <el-container>
    <el-header>
      <home-header></home-header>
    </el-header>
    <home-title></home-title>
    <el-container>
      <el-aside>
        <template v-if="accessToken">
          <i-sider-bar></i-sider-bar>
        </template>
        <template v-else>
          <sider-bar></sider-bar>
        </template>
      </el-aside>
      <el-main>
        <feed-back></feed-back>
        <template v-if="accessToken">
          <works-main
            :works="pinWorksData"
            :worksCount="pinWorksCount"
            @changeTag="pinGetWorksInfo"
          ></works-main>
        </template>
        <template v-else>
          <works-main
            :works="touristWorksData"
            :worksCount="touristWorksCount"
            @changeTag="touristGetWorksInfo"
          ></works-main>
        </template>
      </el-main>
    </el-container>
    <el-footer>
      <el-col :span="18" class="home-footer-left">
        ©copyright&nbsp;&nbsp;2019&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <router-link :to="{name: 'Terms'}">用户协议</router-link>&nbsp;&nbsp;&nbsp;&nbsp;
        <router-link :to="{name: 'About'}">关于我们</router-link>
      </el-col>
      <el-col :span="6" class="home-footer-right">
        <h4>ArtGallery</h4>
        <h5>http://www.artgallery.com</h5>
      </el-col>
    </el-footer>
  </el-container>
</template>

<script>
import HomeHeader from "./components/header.vue";
import HomeTitle from "./components/title.vue";
import SiderBar from "../common/siderBar.vue";
import iSiderBar from "../common/iSiderBar.vue";
import FeedBack from "../userSystem/feedback/feedback.vue";
import WorksMain from "./works/worksMain.vue";
import axios from "axios";

export default {
  name: "Works",
  components: {
    HomeHeader,
    HomeTitle,
    SiderBar,
    FeedBack,
    WorksMain,
    iSiderBar
  },
  data() {
    return {
      accessToken: localStorage.hasOwnProperty("accessToken"),
      pinWorksData: {},
      touristWorksData: {},
      touristWorksCount: 0,
      pinWorksCount: 0
    };
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
            this.$router.push({ name: "Works" });  
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
          this.$router.push({ name: "Login" });
        } else {
          this.$message.error("服务器请求错误");
          this.$router.push({ name: "Home" });
        }
      });
    },
    pinGetWorksInfo(parameter = {}) {
      parameter.pageSize = 9;
      if (!parameter.hasOwnProperty("pageNumber")) {
        parameter.pageNumber = 1;
      }
      axios({
        method: "get",
        url: "/api/works/pinList",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken
        },
        params: parameter
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.pinWorksCount = res.data.data.count;
            delete res.data.data.count;
            this.pinWorksData = res.data.data;
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
    },
    touristGetWorksInfo(parameter = {}) {
      parameter.pageSize = 9;
      if (!parameter.hasOwnProperty("pageNumber")) {
        parameter.pageNumber = 1;
      }
      axios
        .get("/api/works/touristList", {
          params: parameter
        })
        .then(res => {
          if (res.status === 200) {
            if (res.data.errno === 10000) {
              this.touristWorksCount = res.data.data.count;
              delete res.data.data.count;
              this.touristWorksData = res.data.data;
            } else {
              this.$message({
                message: "参数有误",
                type: "warning"
              });
              return false;
            }
          } else {
            this.$message.error("服务器请求错误");
            return false;
          }
        });
    }
  },
  mounted() {
    if (localStorage.hasOwnProperty("accessToken")) {
      this.pinGetWorksInfo();
    } else {
      this.touristGetWorksInfo();
    }
  }
};
</script>

<style scoped>
.home-footer-right {
  text-align: right;
}
.home-footer-left {
  text-align: bottom;
}
</style>

