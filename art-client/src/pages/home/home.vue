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
          <pin-artist
            :artists="pinArtistData"
            :artistCount="artistCount"
            @changeTag="pinGetArtistInfo"
            :userDetail="userDetail"
          ></pin-artist>
        </template>
        <template v-else>
          <artist :artists="touristArtistData" @changeArtist="touristGetArtistInfo"></artist>
        </template>
      </el-main>
    </el-container>
    <mu-divider shallow-inset></mu-divider>
    <el-footer>
      <el-col :span="18" class="home-footer-left">
        ©copyright&nbsp;&nbsp;2019&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <router-link :to="{name: 'Terms'}" style="font-family: 'kaiti'">用户协议</router-link>&nbsp;&nbsp;&nbsp;&nbsp;
        <router-link :to="{name: 'About'}" style="font-family: 'kaiti'">关于我们</router-link>
      </el-col>
      <el-col :span="6" class="home-footer-right">
        <h4 style="font-family: 'Microsoft YaHei'">ArtGallery</h4>
        <h5 style="font-family: 'Microsoft YaHei'">http://www.artgallery.com</h5>
      </el-col>
    </el-footer>  
  </el-container>
</template>

<script>
import HomeHeader from "./components/header.vue";
import HomeTitle from "./components/title.vue";
import SiderBar from "../common/siderBar.vue";
import FeedBack from "../userSystem/feedback/feedback.vue";
import Artist from "./artist/artist.vue";
import PinArtist from "./artist/pinArtist.vue";
import iSiderBar from "../common/iSiderBar.vue";
import axios from "axios";

export default {
  name: "Home",
  components: {
    HomeHeader,
    HomeTitle,
    SiderBar,
    FeedBack,
    Artist,
    PinArtist,
    iSiderBar
  },
  data() {
    return {
      accessToken: localStorage.hasOwnProperty("accessToken"),
      pinArtistData: [],
      touristArtistData: {},
      artistCount: 0,
      userDetail: {}
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
            this.$router.push({ name: "Home" });
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
    pinGetArtistInfo(number) {
      axios({
        method: "get",
        url: "/api/users/pinListUserRecord",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken
        },
        params: {
          pageSize: 9,
          pageNumber: number
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.artistCount = res.data.data.count;
            delete res.data.data.count;
            this.pinArtistData = Object.values(res.data.data);
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
    touristGetArtistInfo(count) {
      axios
        .get("/api/users/touristListUserRecord", {
          params: {
            pageSize: 9,
            pageNumber: count
          }
        })
        .then(res => {
          if (res.status === 200) {
            if (res.data.errno === 10000) {
              this.touristArtistData = res.data.data;
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
    },
    getUserDetail() {
      axios({
        method: "get",
        url: "/api/users/getUserInfo",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.userDetail = res.data.data[0];
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
    if (localStorage.hasOwnProperty("accessToken")) {
      this.pinGetArtistInfo(1);
      this.getUserDetail();
    } else {
      this.touristGetArtistInfo(1);
    }
  }
};
</script>

<style scoped>
.home-footer-right {
  text-align: right;
}
.home-footer-left {
  font-family: 'Microsoft YaHei';
  text-align: bottom;
}
</style>

