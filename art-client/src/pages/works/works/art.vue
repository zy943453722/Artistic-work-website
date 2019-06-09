<template>
  <el-container>
    <template v-if="accessToken">
      <i-sider-bar></i-sider-bar>
    </template>
    <template v-else>
      <sider-bar></sider-bar>
    </template>
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
                style="line-height:20px;font-family: 'Microsoft YaHei';font-size:15px"
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
          <p
            style="margin:15px 100px 5px 100px;float:left;font-family: 'Microsoft YaHei';font-size:15px"
          >
            {{worksData.name}}&nbsp;&nbsp;|&nbsp;&nbsp;{{worksData.type}},
            &nbsp;&nbsp;{{worksData.length}}&times;{{worksData.height}}cm,&nbsp;&nbsp;
            {{worksData.make_at}}
          </p>
        </el-row>
        <el-row style="height:50px">
          <el-col :span="16">
            <p
              style="margin-left:100px;float:left;font-family: 'Microsoft YaHei';font-size:15px"
            >简介:&nbsp;&nbsp;{{worksData.introduction}}</p>
          </el-col>
          <el-col :span="8" style="line-height:50px" v-if="worksData.right">
            <el-tooltip class="item" effect="dark" content="修改作品" placement="bottom">
              <span class="iconfont" @click="handleEdit" style="cursor:pointer">&#xe623;</span>
            </el-tooltip>&nbsp;&nbsp;
            <el-tooltip class="item" effect="dark" content="删除作品" placement="bottom">
              <span class="iconfont" @click="handleDelete" style="cursor:pointer">&#xe686;</span>
            </el-tooltip>
          </el-col>
        </el-row>
      </div>
      <div style="text-align:center;margin: 20px 0px">
        <template v-if="worksData.relation">
          <el-button type="danger" @click="pinHandleLikeRelation(worksData.pin,worksData.id)">
            <span class="iconfont">&#xe621;</span>
            &nbsp;{{worksData.likes}}
          </el-button>
        </template>
        <template v-else>
          <el-button
            type="danger"
            v-if="accessToken"
            @click="pinHandleLike(worksData.id,worksData.pin)"
          >
            <span class="iconfont">&#xe620;</span>
            &nbsp;赞一下
          </el-button>
          <el-button type="danger" v-if="!accessToken" @click="handleLike">
            <span class="iconfont">&#xe620;</span>
            &nbsp;赞一下
          </el-button>
        </template>
      </div>
      <div style="width:600px;float:left;margin:20px 350px">
        <div v-for="person in likeAvatar" :key="person.pin" style="display:inline">
          <router-link :to="{name: 'UserWorks',params:{id: person.website.slice(26)}}">
            <mu-avatar size="50" style="margin-right:10px">
              <img :src="person.avator+'?x-oss-process=image/resize,m_lfit,h_100,w_100'"
              alt="图片加载失败，请稍等"
              :title="person.nickname">
            </mu-avatar>
          </router-link>
        </div>
      </div>
      <h3 style="margin: 0px 300px">大家的评论</h3>
      <mu-divider shallow-inset class="works-divider"></mu-divider>
      <div
        v-for="(comment,index) in commentList"
        :key="index"
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
              :to="{name: 'UserWorks',params:{id: comment.to_id}}"
            >{{comment.to_nickname}}</router-link>
            :&nbsp;{{comment.content}}
          </p>
          <p v-else style="float:left">{{comment.content}}</p>
          <span class="iconfont" style="float:left">&#xe63a;</span>
        </div>
        <div style="float:right">
          <el-button round @click="handleReply('input',comment.nickname,comment.pin)">
            <span class="iconfont">&#xe663;</span>&nbsp;回复
          </el-button>
          <div v-if="comment.right" style="margin-top:5px">
            <el-tooltip class="item" effect="dark" content="删除评论" placement="bottom">
              <span
                class="iconfont"
                @click="handleDeleteComment(comment.id)"
                style="cursor:pointer"
              >&#xe686;</span>
            </el-tooltip>
          </div>
        </div>
      </div>
      <mu-divider shallow-inset class="works-divider" v-if="Object.keys(commentList).length !== 0"></mu-divider>
      <div style="text-align:center;margin: 50px 300px 100px 300px">
        <el-input
          v-model="input"
          type="text"
          maxlength="50"
          show-word-limit
          placeholder="写一条评论吧~~"
          style="width:500px"
          ref="input"
        ></el-input>
        <el-button type="success" round @click="sendComment">发送</el-button>
      </div>
      <mu-divider shallow-inset class="works-divider"></mu-divider>
      <h2 style="text-align:center">{{worksData.nickname}}的更多作品</h2>
      <mu-divider shallow-inset class="works-divider"></mu-divider>
      <el-row :gutter="20" v-if="Object.keys(works).length !== 0" style="margin: 20px 150px">
        <div v-for="work in allWorks" :key="work.id">
          <el-col :span="8">
            <div>
              <div style="width:300px;height:220px">
                <router-link :to="{name: 'Art',params:{id: work.id}}">
                  <img
                    :src="work.instance + '?x-oss-process=image/resize,m_lfit,h_300,w_300'"
                    alt="图片加载失败，请稍等"
                    :title="work.name"
                    style="width:300px;height:200px"
                  >
                </router-link>
              </div>
              <router-link :to="{name: 'Art',params:{id: work.id}}">{{work.name}}</router-link>
              <template v-if="work.hasOwnProperty('relation')">
                <el-button
                  size="mini"
                  @click="pinHandleLikeRelation(work.pin,work.id)"
                  v-if="accessToken"
                  style="float:right"
                >
                  <span class="iconfont">&#xe621;</span>
                  &nbsp;{{work.likes}}
                </el-button>
              </template>
              <template v-else>
                <el-button
                  size="mini"
                  @click="pinHandleLike(work.id, work.pin)"
                  v-if="accessToken"
                  style="float:right"
                >
                  <span class="iconfont">&#xe620;</span>&nbsp;赞一下
                </el-button>
                <el-button size="mini" @click="handleLike" v-if="!accessToken" style="float:right">
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
      <template v-if="pageNumber < (~~(worksCount/9 + 1))">
        <div style="text-align:center">
          <el-button type="primary" @click="loadMore">加载更多</el-button>
        </div>
      </template>
      <template v-else-if="Object.keys(works).length !== 0">
        <div style="text-align:center">
          <p>没有更多了~~</p>
        </div>
      </template>
      <template v-else>
        <span></span>
      </template>
    </el-main>
  </el-container>
</template>

<script>
import FeedBack from "../../userSystem/feedback/feedback.vue";
import iSiderBar from "../../common/iSiderBar.vue";
import SiderBar from "../../common/siderBar.vue";
import axios from "axios";

export default {
  name: "Art",
  components: {
    FeedBack,
    iSiderBar,
    SiderBar
  },
  data() {
    return {
      to: "",
      worksCount: 0,
      pageNumber: 1,
      prvWorks: [],
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
  watch: {
    $route() {
      if (this.$route.params.id) {
        this.$router.push({
          name: "Empty",
          params: { toPath: this.$route.params.id }
        });
      }
    }
  },
  computed: {
    allWorks() {
      let _this = this;
      _this.prvWorks = _this.prvWorks.concat(Object.values(this.works));
      return _this.prvWorks;
    }
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
    sendComment() {
      if (!this.accessToken) {
        this.$router.push({ name: "Login" });
        return false;
      }
      if (this.input === "") {
        this.$message({
          message: "不能发送空消息",
          type: "warning"
        });
        return false;
      }
      let factor;
      if (this.to === "") {
        factor = {
          worksId: this.$route.params.id,
          content: this.input
        };
      } else {
        let arr = this.input.split(":");
        if (arr[1] === "") {
          this.$message({
            message: "不能发送空消息",
            type: "warning"
          });
          return false;
        }
        let index = this.input.indexOf(":") + 1;
        let text = this.input.slice(index);
        factor = {
          worksId: this.$route.params.id,
          toPin: this.to,
          content: text
        };
      }
      axios({
        method: "post",
        url: "/api/works/addComments",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken,
          "Content-Type": "application/json"
        },
        data: factor,
        transformRequest: [
          function(data) {
            data = JSON.stringify(data);
            return data;
          }
        ]
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.input = "";
            this.$message({
              message: "评论成功",
              type: "success"
            });
            this.pinGetCommentDetail(this.$route.params.id);
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
    handleReply(name, nickname, pin) {
      if (!this.accessToken) {
        this.$router.push({ name: "Login" });
        return false;
      }
      this.$refs[name].focus();
      this.input = "回复@" + nickname + ":";
      this.to = btoa(pin);
    },
    handleDeleteComment(id) {
      this.$confirm("确定删除当前评论?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          axios({
            method: "delete",
            url: "/api/works/deleteComments",
            headers: {
              Authorization: "Bearer " + localStorage.accessToken,
              "Content-Type": "application/json"
            },
            data: {
              id: id
            },
            transformRequest: [
              function(data) {
                data = JSON.stringify(data);
                return data;
              }
            ]
          }).then(res => {
            if (res.status === 200) {
              if (res.data.errno === 10000) {
                this.$message({
                  message: "评论删除成功",
                  type: "success"
                });
                this.pinGetCommentDetail(this.$route.params.id);
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
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消操作"
          });
        });
    },
    handleEdit() {
      this.$router.push({
        name: "EditWorks",
        params: { id: this.worksData.uid, worksId: this.$route.params.id }
      });
    },
    handleDelete() {
      this.$confirm("确定删除当前作品?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          axios({
            method: "delete",
            url: "/api/works/delete",
            headers: {
              Authorization: "Bearer " + localStorage.accessToken,
              "Content-Type": "application/json"
            },
            data: {
              id: this.$route.params.id
            },
            transformRequest: [
              function(data) {
                data = JSON.stringify(data);
                return data;
              }
            ]
          }).then(res => {
            if (res.status === 200) {
              if (res.data.errno === 10000) {
                this.$message({
                  message: "删除成功",
                  type: "success"
                });
                this.$router.push({
                  name: "UserWorks",
                  params: { id: localStorage.id }
                });
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
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消操作"
          });
        });
    },
    loadMore() {
      this.pageNumber++;
      if (this.accessToken)
        this.pinGetWorksList(this.$route.params.id, this.pageNumber);
      else this.touristGetWorksList(this.$route.params.id, this.pageNumber);
    },
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
            this.worksCount = res.data.data.count;
            delete res.data.data.count;
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
            this.worksCount = res.data.data.count;
            delete res.data.data.count;
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
      let date = new Date(time * 1000);
      let Y = date.getFullYear() + "年";
      let M =
        (date.getMonth() + 1 < 10
          ? "0" + (date.getMonth() + 1)
          : date.getMonth() + 1) + "月";
      let D = this.formatNumber(date.getDate()) + "日";
      let h = this.formatNumber(date.getHours()) + ":";
      let m = this.formatNumber(date.getMinutes()) + ":";
      let s = this.formatNumber(date.getSeconds());
      return Y + M + D + h + m + s;
    },
    formatNumber(n) {
      n = n.toString();
      return n[1] ? n : "0" + n;
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
        url: "/api/works/touristGetCommentsDetail",
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
    pinHandleLike(id, pin) {
      axios({
        method: "post",
        url: "/api/works/addLikes",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken,
          "Content-Type": "application/json"
        },
        data: {
          worksId: id,
          pin: btoa(pin)
        },
        transformRequest: [
          function(data) {
            data = JSON.stringify(data);
            return data;
          }
        ]
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.$message({
              message: "点赞成功",
              type: "success"
            });
            this.pinGetWorksDetail(this.$route.params.id);
            this.getLikeDetail(this.$route.params.id);
          } else if (res.data.errno === 40005) {
            this.refreshHandle();
          } else {
            this.$message({
              message: "参数有误",
              type: "warning"
            });
            return false;
          }
        } else if (res === 401) {
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
    handleLike() {
      this.$router.push({ name: "Login" });
    },
    pinHandleLikeRelation(pin, id) {
      this.$confirm("确定不再喜欢了?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.handleCancelLike(pin, id);
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消操作"
          });
        });
    },
    handleCancelLike(pin, id) {
      axios({
        method: "delete",
        url: "/api/works/deleteLikes",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken,
          "Content-Type": "application/json"
        },
        data: {
          worksId: id,
          pin: btoa(pin)
        },
        transformRequest: [
          function(data) {
            data = JSON.stringify(data);
            return data;
          }
        ]
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.$message({
              message: "取消点赞成功",
              type: "success"
            });
            this.worksData.relation = "";
            this.pinGetWorksDetail(this.$route.params.id);
            this.getLikeDetail(this.$route.params.id);
          } else if (res.data.errno === 40005) {
            this.refreshHandle();
          } else {
            this.$message({
              message: "参数有误",
              type: "warning"
            });
            return false;
          }
        } else if (res === 401) {
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
