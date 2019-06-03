<template>
  <mu-container>
    <mu-breadcrumbs class="home-nav" divider="—">
      <mu-breadcrumbs-item
        class="home-nav-item"
        v-for="item in items"
        :key="item.text"
        :disabled="item.disabled"
        :to="item.to"
        :class="item.class"
      >{{item.text}}</mu-breadcrumbs-item>
    </mu-breadcrumbs>
    <el-row class="home-btn-row">
      <el-button type="primary" class="home-btn" @click="handleClick">
        <span class="iconfont">&#xe604;</span>&nbsp;换一批
      </el-button>
    </el-row>
    <el-row :gutter="20">
      <div class="home-artist" v-for="artist in artists" :key="artist.website.slice(26)">
        <el-col :span="8">
          <div>
            <div style="width:300px;height:220px">
              <router-link :to="{name: 'Art',params:{id: artist.masterpiece_id}}">
                <img
                  :src="artist.masterpiece + '?x-oss-process=image/resize,m_lfit,h_300,w_300'"
                  alt="图片加载失败，请稍等"
                  style="width:300px;height:200px"
                >
              </router-link>
            </div>
            <div style="text-align:center">
              <router-link
                :to="{name: 'UserWorks',params:{id: artist.website.slice(26)}}"
                style="font-family: 'Microsoft YaHei';font-size:20px"
              >{{artist.nickname}}</router-link>
              <p style="font-family: 'Microsoft YaHei';font-size:15px">
                发表过
                <span
                  style="font-family: 'stup';font-size:25px;color: purple"
                >{{artist.works_number}}</span>件作品
              </p>
              <p style="font-family: 'Microsoft YaHei';font-size:15px">
                被
                <span
                  style="font-family: 'stup';font-size:25px;color: purple"
                >{{artist.followers_number}}</span>人关注
              </p>
              <p style="font-family: 'Microsoft YaHei';font-size:15px">
                累计点赞次数:
                <span
                  style="font-family: 'stup';font-size:25px;color: purple"
                >{{artist.likes_number}}</span>
              </p>
            </div>
          </div>
        </el-col>
      </div>
    </el-row>
    <div class="next-one">
      <div class="next-icon">
        <router-link :to="{name: 'Register'}" style="text-decoration: none;">
          <p style="font-family:'stup'">WHO IS THE NEXT?</p>
        </router-link>
      </div>
      <h3 style="font-family:'wenyiheiti';font-size:30px">下一个也许是你</h3>
      <p class="next-one-word">欢迎最棒的艺术家、专业机构和最富激情的爱好者们免费入驻ArtGallery!</p>
    </div>
  </mu-container>
</template>

<script>
export default {
  name: "Artist",
  props: ["artists"],
  data() {
    return {
      count: 1,
      items: [
        {
          text: "艺术家",
          disabled: false,
          to: { name: "Home" },
          class: "home-nav-item-artist"
        },
        {
          text: "作品",
          disabled: false,
          to: { name: "Works" },
          class: "home-nav-item-works"
        }
      ]
    };
  },
  methods: {
    handleClick() {
      this.count++;
      this.$emit("changeArtist", this.count);
    },
    handleFollow() {
      this.$router.push({ name: "Login" });
    }
  }
};
</script>

<style scoped>
.home-nav {
  width: 500px;
  margin: 0 auto;
}
.home-nav-item {
  font-size: 20px;
}
.home-nav-item-artist {
  font-family: "kaiti";
  color: red;
}
.home-nav-item-works {
  font-family: "kaiti";
  color: black;
}
.home-btn-row {
  margin: 0px 100px 20px 100px;
}
.home-btn {
  font-family: "Microsoft YaHei";
  float: right;
}
.next-one {
  text-align: center;
  margin: 50px 0px;
}
.next-icon {
  width: 150px;
  height: 150px;
  display: inline-block;
  border-radius: 150px;
  background: #24b552;
}
.next-icon p {
  font-size: 10px;
  line-height: 150px;
  text-align: center;
  color: #fff;
}
.next-one h3 {
  text-align: center;
  font-size: 20px;
  color: #2c2b2a;
  padding: 30px 0;
}
.next-one-word {
  font-family: "kaiti";
  line-height: 24px;
  font-size: 20px;
  color: #000;
  margin: 50px 0px;
}
</style>
