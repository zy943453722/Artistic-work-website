<template>
  <el-container>
    <el-header>
      <mu-breadcrumbs class="home-works-nav" divider="|">
        <mu-breadcrumbs-item
          class="home-works-nav-item"
          v-for="item in items"
          :key="item.text"
          :disabled="item.disabled"
          :to="item.to"
          :class="item.class"
        >{{item.text}}</mu-breadcrumbs-item>
      </mu-breadcrumbs>
    </el-header>
    <el-container style="margin: 50px 0px 100px 0px">
      <el-aside width="600px" style="margin-right:50px">
        <div class="block">
          <el-carousel height="300px">
            <el-carousel-item v-for="item in imageItems" :key="item.id">
              <img :src="item.url" class="bannerImg">
            </el-carousel-item>
          </el-carousel>
        </div>
        <div>
          <div v-for="card in allArtists" :key="card.pin">
            <mu-card style="width: 100%; max-width: 550px;margin: 50px auto;">
              <mu-card-header :title="card.nickname" :sub-title="card.followers_number+'人关注过'">
                <mu-avatar slot="avatar">
                  <img
                    :src="card.avator + '?x-oss-process=image/resize,m_lfit,h_100,w_100'"
                    :title="card.nickname"
                  >
                </mu-avatar>
              </mu-card-header>
              <mu-card-media
                :title="card.name+' '+' '+' '+card.make_at+'年作品'"
                :sub-title="card.likes+'人赞过'"
              >
                <router-link :to="{name: 'Art',params:{id: card.masterpiece_id}}">
                  <img
                    :src="card.masterpiece + '?x-oss-process=image/resize,m_lfit,h_550,w_550'"
                    alt="图片加载失败，请稍等"
                    :title="card.name"
                  >
                </router-link>
              </mu-card-media>
            </mu-card>
          </div>
          <template v-if="pageNumber < (~~(artistCount/9 + 1))">
            <div style="text-align:center">
              <el-button type="primary" @click="loadMore">加载更多</el-button>
            </div>
          </template>
          <template v-else>
            <div style="text-align:center">
              <p>没有更多了~~</p>
            </div>
          </template>
        </div>
      </el-aside>
      <el-main>
        <div class="art-main">
          <el-row :gutter="20">
            <el-col :span="8" style="text-align:center;margin-top:20px">
              <router-link
                :to="{name: 'UserWorks',params:{id: userDetail.id}}"
              >
                <mu-avatar>
                  <img
                    :src="userDetail.avator + '?x-oss-process=image/resize,m_lfit,h_100,w_100'"
                    :title="userDetail.nickname"
                  >
                </mu-avatar>
              </router-link>
            </el-col>
            <el-col :span="16">
              <p>{{userDetail.nickname}}</p>
              <router-link
                :to="{name: 'UserWorks',params:{id: userDetail.id}}"
                style="text-align:center"
              >{{userDetail.website}}</router-link>
            </el-col>
          </el-row>
          <mu-divider></mu-divider>
          <div style="text-align:center;margin:15px 0px">
            <el-button type="success" @click="uploadWorks">发布作品</el-button>
          </div>
        </div>
        <div class="art-img-container">
          <img src="http://artgallery1.oss-cn-beijing.aliyuncs.com/guanggao.png?x-oss-process=image/resize,m_lfit,h_500,w_500">
        </div>
        <div class="art-img-container">
          <img src="http://artgallery1.oss-cn-beijing.aliyuncs.com/guanggao1.png?x-oss-process=image/resize,m_lfit,h_500,w_500">
        </div>
      </el-main>
    </el-container>
  </el-container>
</template>

<script>
export default {
  name: "PinArtist",
  props: ["artists", "artistCount", "userDetail"],
  data() {
    return {
      pageNumber: 1,
      num: 20,
      prvArtists: [],
      imageItems: [
        {
          id: 1,
          url: "http://artgallery1.oss-cn-beijing.aliyuncs.com/lunbo1.jpg?x-oss-process=image/resize,m_lfit,h_500,w_600"
        },
        {
          id: 2,
          url: "http://artgallery1.oss-cn-beijing.aliyuncs.com/lunbo2.jpg?x-oss-process=image/resize,m_lfit,h_500,w_600"
        },
        {
          id: 3,
          url: "http://artgallery1.oss-cn-beijing.aliyuncs.com/lunbo3.jpg?x-oss-process=image/resize,m_lfit,h_500,w_600"
        },
        {
          id: 4,
          url: "http://artgallery1.oss-cn-beijing.aliyuncs.com/lunbo4.jpg?x-oss-process=image/resize,m_lfit,h_500,w_600"
        }
      ],
      items: [
        {
          text: "首页",
          disabled: false,
          to: { name: "Home" },
          class: "home-nav-item-artist"
        },
        {
          text: "所有作品",
          disabled: false,
          to: { name: "Works" },
          class: "home-nav-item-works"
        }
      ]
    };
  },
  computed: {
    allArtists() {
      this.prvArtists = this.prvArtists.concat(this.artists);
      return this.prvArtists;
    }
  },
  methods: {
    loadMore() {
      this.pageNumber++;
      this.$emit("changeTag", this.pageNumber);
    },
    uploadWorks() {
      this.$router.push({
        name: "UploadWorks",
        params: { id: localStorage.id }
      });
    }
  }
};
</script>

<style scoped>
.art-carousel {
  height: 200px;
  color: red;
}
.art-personal {
  height: 100px;
  color: blue;
}
.home-works-nav {
  width: 500px;
  margin: 0 auto;
}
.home-works-nav-item {
  font-size: 20px;
}
.home-nav-item-artist {
  font-family: 'kaiti';
  color: red;
}
.home-nav-item-works {
  font-family: 'kaiti';
  color: black;
}
.art-main {
  border-style: inset;
}
.art-img-container {
  margin: 30px 0px;
  overflow: hidden;
  position: relative;
  width: 320px;
  height: 400px;
}
.art-img-container > img {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
