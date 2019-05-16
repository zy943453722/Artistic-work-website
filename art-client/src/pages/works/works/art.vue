<template>
  <el-container>
    <i-sider-bar></i-sider-bar>
    <el-main>
      <feed-back></feed-back>
      <div class="art-img">
        <img
          :src="worksData.instance + '?x-oss-process=image/resize,m_lfit,h_550,w_700'"
          alt="图片加载失败，请稍等"
          :title="worksData.name"
        >
      </div>
      <div class="art-info">
        <el-row>
          <el-col :span="10">
            <div style="text-align:center;height:60px">
              <router-link :to="{name: 'UserWorks',params:{id: worksData.uid}}">
                <mu-avatar size="50" style="margin-right:10px">
                  <img :src="worksData.avatar">
                </mu-avatar>
              </router-link>
              <router-link
                :to="{name: 'UserWorks',params:{id: worksData.uid}}"
                style="line-height:20px"
              >{{worksData.nickname}}</router-link>
            </div>
          </el-col>
          <el-col :span="14">
            <div style="height:60px">
              <span style="float:left;line-height:60px">分享至:&nbsp;</span>
              <share :config="config" style="float:left;line-height:60px"></share>
            </div>
          </el-col>
        </el-row>
        <el-row>
          <p style="margin:15px 100px 5px 100px;float:left">
            {{worksData.name}}&nbsp;&nbsp;|&nbsp;&nbsp;{{worksData.type}},
            &nbsp;&nbsp;{{worksData.length}}&times;{{worksData.height}}cm,&nbsp;&nbsp;
            {{worksData.make_at}}
          </p>
        </el-row>
        <el-row style="height:50px">
          <el-col :span="16">
            <p style="margin-left:100px;float:left">简介:&nbsp;&nbsp;{{worksData.introduction}}</p>
          </el-col>
          <el-col :span="8" style="line-height:50px" v-if="worksData.right">
            <el-tooltip class="item" effect="dark" content="修改作品" placement="bottom">
              <span class="iconfont">&#xe623;</span>
            </el-tooltip>&nbsp;&nbsp;
            <el-tooltip class="item" effect="dark" content="删除作品" placement="bottom">
              <span class="iconfont">&#xe686;</span>
            </el-tooltip>
          </el-col>
        </el-row>
      </div>
      <div style="text-align:center;margin: 20px 0px">
        <template v-if="worksData.relation">
          <el-button type="danger" @click="pinHandleLikeRelation">
            <span class="iconfont">&#xe621;</span>
            &nbsp;{{worksData.likes}}
          </el-button>
        </template>
        <template v-else>
          <el-button type="danger" v-if="accessToken" @click="pinHandleLike">
            <span class="iconfont">&#xe620;</span>
            &nbsp;{{worksData.likes}}
          </el-button>
          <el-button type="danger" v-if="!accessToken" @click="handleLike">
            <span class="iconfont">&#xe620;</span>
            &nbsp;{{worksData.likes}}
          </el-button>
        </template>
      </div>
      <div style="width:600px;float:left;margin:20px 350px">
        <div v-for="person in likeAvatar" :key="person.pin" style="display:inline">
          <router-link :to="{name: 'UserWorks',params:{id: person.website.slice(26)}}">
            <mu-avatar size="50" style="margin-right:10px">
              <img :src="person.avator+'?x-oss-process=image/resize,m_lfit,h_100,w_100'">
            </mu-avatar>
          </router-link>
        </div>
      </div>
      <h3 style="margin: 0px 300px">大家的评论</h3>
      <mu-divider shallow-inset class="works-divider"></mu-divider>
      <div
        v-for="comment in commentList"
        :key="comment.pin"
        style="text-align:center;margin: 10px 300px;height:100px"
      >
        <router-link
          :to="{name: 'UserWorks',params:{id: comment.website.slice(26)}}"
          style="float:left"
        >
          <mu-avatar size="70" style="margin-right:10px">
            <img :src="comment.avator+'?x-oss-process=image/resize,m_lfit,h_100,w_100'">
          </mu-avatar>
        </router-link>
        <div style="float:left;margin: 0px 20px">
          <p>
            <router-link
              :to="{name: 'UserWorks',params:{id: comment.website.slice(26)}}"
            >{{comment.nickname}}</router-link>
            &nbsp;&nbsp;发表于:{{handleTime(comment.create_at)}}
          </p>
        </div>
        <div>
          <span class="iconfont" style="float:left">&#xe63c;</span>
          <p v-if="comment.to_pin" style="float:left">
            回复@
            <router-link
              :to="{name: 'UserWorks',params:{id: comment.website.slice(26)}}"
            >{{comment.nickname}}</router-link>
            :&nbsp;{{comment.content}}
          </p>
          <p v-else style="float:left">{{comment.content}}</p>
          <span class="iconfont" style="float:left">&#xe63a;</span>
        </div>
        <div style="float:right">
          <el-button round>
            <span class="iconfont">&#xe663;</span>&nbsp;回复
          </el-button>
          <div v-if="comment.right" style="margin-top:5px">
            <el-tooltip class="item" effect="dark" content="删除评论" placement="bottom">
              <span class="iconfont">&#xe686;</span>
            </el-tooltip>
          </div>
        </div>
      </div>
      <mu-divider shallow-inset class="works-divider"></mu-divider>
      <div style="text-align:center;margin: 50px 300px 100px 300px">
        <el-input v-model="input" placeholder="写一条评论吧~~" style="width:500px"></el-input>
        <el-button type="success" round>发送</el-button>
      </div>
      <mu-divider shallow-inset class="works-divider"></mu-divider>
      <h2 style="text-align:center">{{worksData.nickname}}的更多作品</h2>
      <mu-divider shallow-inset class="works-divider"></mu-divider>
      <el-row :gutter="20" v-if="Object.keys(works).length !== 0">
        <div v-for="work in works" :key="work.id">
          <el-col :span="8">
            <div>
              <div>
                <router-link :to="{name: 'Art',params:{id: work.id}}">
                  <img
                  :src="work.instance + '?x-oss-process=image/resize,m_lfit,h_200,w_200'"
                  alt="图片加载失败，请稍等"
                  :title="work.name"
                  > 
                </router-link>
              </div>
              <router-link :to="{name: 'Art',params:{id: work.id}}">{{work.name}}</router-link>
              <template v-if="work.hasOwnProperty('relation')">
                <el-button @click="pinHandleLikeRelation" v-if="accessToken">
                  <span class="iconfont">&#xe621;</span>
                  &nbsp;{{work.likes}}
                </el-button>
              </template>
              <template v-else>
                <el-button @click="pinHandleLike" v-if="accessToken">
                  <span class="iconfont">&#xe620;</span>&nbsp;赞一下
                </el-button>
                <el-button @click="handleLike" v-if="!accessToken">
                  <span class="iconfont">&#xe620;</span>&nbsp;赞一下
                </el-button>
              </template>
              <p>{{work.make_at}}年作品</p>
            </div>
          </el-col>
        </div>
      </el-row>
      <el-row v-else>
        <p style="text-align:center">该作者没有更多作品了~~</p>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>
import FeedBack from "../../userSystem/feedback/feedback.vue";
import iSiderBar from "../../common/iSiderBar.vue";
import axios from "axios";

export default {
  name: "Art",
  components: {
    FeedBack,
    iSiderBar
  },
  data() {
    return {
      works: [],
      input: "",
      likeAvatar: [],
      commentList: [],
      accessToken: localStorage.hasOwnProperty("accessToken"),
      config: {
        url: "http://artgallery.com",
        source: "http://artgallery.com",
        title: "ArtGallery浏览之旅",
        description: "很开心和大家分享在ArtGallery中发现的很漂亮的一幅画",
        image: "",
        sites: [
          "qzone",
          "qq",
          "weibo",
          "wechat",
          "douban",
          "google",
          "facebook"
        ],
        disabled: ["twitter"]
      },
      worksData: {
        uid: "",
        id: "",
        instance: "",
        name: "",
        type: "",
        length: "",
        height: "",
        introduction: "",
        nickname: "",
        avatar: "",
        make_at: "",
        likes: "",
        right: "",
        relation: ""
      },
      options: [
        {
          value: "1",
          label: "油画"
        },
        {
          value: "2",
          label: "丙烯"
        },
        {
          value: "3",
          label: "水彩"
        },
        {
          value: "4",
          label: "雕塑"
        },
        {
          value: "5",
          label: "国画"
        },
        {
          value: "6",
          label: "版画"
        },
        {
          value: "7",
          label: "复制品"
        },
        {
          value: "8",
          label: "综合材料"
        },
        {
          value: "9",
          label: "装置"
        },
        {
          value: "10",
          label: "摄影"
        },
        {
          value: "11",
          label: "素描"
        },
        {
          value: "12",
          label: "数码绘画"
        },
        {
          value: "13",
          label: "漆画"
        },
        {
          value: "14",
          label: "新媒体"
        },
        {
          value: "15",
          label: "壁画"
        },
        {
          value: "16",
          label: "插画"
        },
        {
          value: "17",
          label: "其他"
        }
      ]
    };
  },
  mounted() {
    if (this.accessToken) {
      this.pinGetWorksDetail(this.$route.params.id);
      this.pinGetCommentDetail(this.$route.params.id);
    } else {
      this.touristGetWorksDetail(this.$route.params.id);
      this.touristGetCommentDetail(this.$route.params.id);
    }
    this.getLikeDetail(this.$route.params.id);
  },
  methods: {
    pinGetWorksList(id, number) {
      axios({
        method: "get",
        url: "/api/users/pinListWorks",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken
        },
        params: {
          pin: btoa(this.worksData.pin),
          worksId: id,
          pageSize: 9,
          pageNumber: number
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.works = res.data.data;
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
    touristGetWorksList(id, number) {
      axios({
        method: "get",
        url: "/api/users/touristListWorks",
        params: {
          pin: btoa(this.worksData.pin),
          worksId: id,
          pageSize: 9,
          pageNumber: number
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.works = res.data.data;
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
    handleTime(time) {
      let date = new Date(time);
      let Y = date.getFullYear() + "年";
      let M =
        (date.getMonth() + 1 < 10
          ? "0" + (date.getMonth() + 1)
          : date.getMonth() + 1) + "月";
      let D = date.getDate() + "日";
      let h = date.getHours() + ":";
      let m = date.getMinutes() + ":";
      let s = date.getSeconds();
      return Y + M + D + h + m + s;
    },
    pinGetCommentDetail(id) {
      axios({
        method: "get",
        url: "/api/works/pinGetCommentsDetail",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken
        },
        params: {
          worksId: id
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.commentList = res.data.data;
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
    touristGetCommentDetail(id) {
      axios({
        method: "get",
        url: "/api/works/pinGetCommentsDetail",
        params: {
          worksId: id
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.commentList = res.data.data;
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
    getLikeDetail(id) {
      axios
        .get("/api/works/getLikesDetail", {
          params: {
            worksId: id
          }
        })
        .then(res => {
          if (res.status === 200) {
            if (res.data.errno === 10000) {
              this.likeAvatar = res.data.data;
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
    pinHandleLike() {},
    handleLike() {},
    pinHandleLikeRelation() {},
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
    pinGetWorksDetail(id) {
      axios({
        method: "get",
        url: "/api/works/pinGetWorksDetail",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken
        },
        params: {
          worksId: id
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            let obj = {};
            obj = this.options.find(item => {
              return item.value == res.data.data.type;
            });
            this.worksData.pin = res.data.data.pin;
            this.worksData.uid = res.data.data.website.slice(26);
            this.worksData.id = res.data.data.id;
            this.worksData.instance = res.data.data.instance;
            this.worksData.name = res.data.data.name;
            this.worksData.nickname = res.data.data.nickname;
            this.worksData.length = res.data.data.length;
            this.worksData.height = res.data.data.height;
            this.worksData.introduction = res.data.data.introduction;
            this.worksData.avatar = res.data.data.avator;
            this.worksData.make_at = res.data.data.make_at;
            this.worksData.likes = res.data.data.likes;
            this.worksData.type = obj.label;
            if (res.data.data.hasOwnProperty("right"))
              this.worksData.right = res.data.data.right;
            if (res.data.data.hasOwnProperty("relation"))
              this.worksData.relation = res.data.data.relation;
            this.pinGetWorksList(this.$route.params.id, 1);
          } else if (res.data.errno === 40005) {
            this.refreshHandle();
          } else if (res.data.errno === 30002) {
            this.$message({
              message: "作品不存在",
              type: "warning"
            });
            return false;
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
    touristGetWorksDetail(id) {
      axios
        .get("/api/works/touristGetWorksDetail", {
          params: {
            worksId: id
          }
        })
        .then(res => {
          if (res.status === 200) {
            if (res.data.errno === 10000) {
              let obj = {};
              obj = this.options.find(item => {
                return item.value == res.data.data.type;
              });
              this.worksData.pin = res.data.data.pin;
              this.worksData.uid = res.data.data.website.slice(26);
              this.worksData.id = res.data.data.id;
              this.worksData.instance = res.data.data.instance;
              this.worksData.name = res.data.data.name;
              this.worksData.nickname = res.data.data.nickname;
              this.worksData.length = res.data.data.length;
              this.worksData.height = res.data.data.height;
              this.worksData.introduction = res.data.data.introduction;
              this.worksData.avatar = res.data.data.avator;
              this.worksData.make_at = res.data.data.make_at;
              this.worksData.type = obj.label;
              this.touristGetWorksList(this.$route.params.id, 1);
            } else if (res.data.errno === 30002) {
              this.$message({
                message: "作品不存在",
                type: "warning"
              });
              return false;
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
  }
};
</script>

<style scoped>
.works-divider {
  margin: 10px 300px;
  width: 700px;
}
.art-img {
  text-align: center;
  margin: 20px 0px;
}
.art-info {
  text-align: center;
  margin: 20px 300px;
}
</style>
