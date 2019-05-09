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
          <pin-artist :artists="pinArtistData"></pin-artist>
        </template>
        <template v-else>
          <artist :artists="touristArtistData" @changeArtist="touristGetArtistInfo"></artist>
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
      pinArtistData: Object,
      touristArtistData: Object,
    };
  },
  methods: {
    pinGetArtistInfo() {
      axios({
        method: "get",
        url: "/api/users/pinListUserRecord",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken
        },
        params: {
          pageSize: 9,
          pageNumber: 1
        }
      }).then(res => {
        if (res.status === 200) {
          return true;
        } else {
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
  },
  mounted() {
    if (localStorage.hasOwnProperty("accessToken")) {
      this.pinGetArtistInfo();
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
  text-align: bottom;
}
</style>

