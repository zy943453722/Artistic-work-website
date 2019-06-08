<template>
  <div>
    <div style="text-align:center">
      <template v-if="relation === 0">
        <p>
          <router-link
            :to="{name: 'UserWorks', params:{id: this.$route.params.id}}"
            style="color:red;font-size:20px"
          >主页</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserAbout', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >简介</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserILike', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >我赞过的</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserLikeme', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >赞过我的</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserComments', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >评论我的</router-link>
        </p>
      </template>
      <template v-else>
        <p>
          <router-link
            :to="{name: 'UserWorks', params:{id: this.$route.params.id}}"
            style="color:red;font-size:20px"
          >主页</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserAbout', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >简介</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserILike', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >他赞过的</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserLikeme', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >赞过他的</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserComments', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >评论他的</router-link>
        </p>
      </template>
    </div>
    <mu-divider shallow-inset style="margin: 20px 300px;width:700px"></mu-divider>
    <el-row :gutter="20" v-if="Object.keys(works).length !== 0" style="margin: 20px 150px">
      <div v-for="work in works" :key="work.id">
        <el-col :span="8">
          <div style="width:300px;height:300px">
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
            <div style="margin-bottom: 10px;height:15px">
              <router-link :to="{name: 'Art',params:{id: work.id}}" style="float:left">{{work.name}}</router-link>
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
            </div>
            <p style="float:left">{{work.make_at}}年作品</p>
          </div>
        </el-col>
      </div>
    </el-row>
    <el-row v-else-if="relation !== 0">
      <p style="text-align:center">该艺术家还没有作品呢~~</p>
    </el-row>
    <el-row v-else>
      <p style="text-align:center">您还没有作品呢,快去发表吧~~</p>
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
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "UserWorks",
  props: ["relation", "works", "worksCount"],
  data() {
    return {
      pageNumber: 1,
      accessToken: localStorage.hasOwnProperty("accessToken")
    };
  },
  methods: {
    loadMore() {
      this.pageNumber++;
      this.$emit("changePageNumber", this.pageNumber);
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
            this.$emit("changePageNumber", this.pageNumber);
            this.$emit("changeLike");
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
            this.$emit("changePageNumber", this.pageNumber);
            this.$emit("changeLike");
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
    handleChange() {
      this.pageNumber++;
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
    }
  }
};
</script>

<style scoped>
</style>
