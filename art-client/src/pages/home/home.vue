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
        <feed-back></feed-back>
        <template v-if="accessToken">
          <pin-artist v-if="path" :artists="pinArtistData"></pin-artist>
          <router-view :works="pinWorksData"></router-view>
        </template>
        <template v-else>
          <artist v-if="path" :artists="touristArtistData" @changeArtist="touristGetArtistInfo"></artist>
          <router-view :works="touristWorksData"></router-view>
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
import axios from "axios";

export default {
  name: "Home",
  components: {
    HomeHeader,
    HomeTitle,
    SiderBar,
    FeedBack,
    Artist,
    PinArtist
  },
  data() {
    return {
      accessToken: localStorage.hasOwnProperty("accessToken"),
      path: this.$route.path.indexOf("/works") === -1? true: false,
      pinArtistData: Object,
      pinWorksData: Object,
      touristArtistData: Object,
      touristWorksData: Object
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
    pinGetWorksInfo() {},
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
    touristGetWorksInfo() {
      axios
        .get("/api/works/touristList", {
          params: {
            pageSize: 9,
            pageNumber: 1
          }
        })
        .then(res => {
          if (res.status === 200) {
            if (res.data.errno === 10000) {
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
      this.pinGetArtistInfo();
      this.pinGetWorksInfo();
    } else {
      this.touristGetArtistInfo(1);
      //this.touristGetWorksInfo();
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

