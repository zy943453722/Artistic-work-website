<template>
  <el-container>
    <el-header class="user-header">
      <template v-if="accessToken">
        <el-button
          v-if="relation===0"
          type="success"
          @click="handleSetting"
          style="width:90px;float:right"
        >我的设置</el-button>
        <el-button
          v-if="relation===1"
          type="success"
          @click="handleFollow"
          style="width:90px;float:right"
        >
          <span class="iconfont">&#xe61a;</span>&nbsp;关注
        </el-button>
        <el-button
          v-if="relation===2"
          type="success"
          @click="handleCancel"
          style="width:90px;float:right"
        >已关注</el-button>
        <el-button
          v-if="relation===3"
          type="success"
          @click="handleMutual"
          style="width:90px;float:right"
        >互相关注</el-button>
      </template>
      <template v-else>
        <div style="float:right;line-height:100px">
          <el-button type="success" @click="handleLogin" style="width:80px">登录</el-button>
          <el-button type="primary" @click="handleRegister" style="width:80px">注册</el-button>
        </div>
      </template>
    </el-header>
    <template v-if="accessToken">
      <i-sider-bar></i-sider-bar>
    </template>
    <template v-else>
      <sider-bar></sider-bar>
    </template>
    <el-main>
      <feed-back></feed-back>
      <div style="text-align:center">
        <mu-avatar size="150">
          <img :src="userRecord.avator">
        </mu-avatar>
        <h2>{{userRecord.nickname}}</h2>
        <p style="font-family: 'Microsoft YaHei';font-size:15px">
          <span style="font-family: 'stup';font-size:25px;color: purple">{{userRecord.works_number}}</span>
          件作品&nbsp;|&nbsp;
          <span
            style="font-family: 'stup';font-size:25px;color: purple"
          >{{userRecord.followers_number}}</span>人
          关注&nbsp;|&nbsp;共收到
          <span style="font-family: 'stup';font-size:25px;color: purple">{{userRecord.likes_number}}</span>个赞
        </p>
        <div>
          <template v-if="userRecord.city === '' ">
            <p style="font-family: 'Microsoft YaHei';font-size:15px">该用户不在服务区~~</p>
          </template>
          <template v-else>
            <span class="iconfont">&#xe62d;</span>
            &nbsp;{{userRecord.province}}&nbsp;|&nbsp;{{userRecord.citys}}&nbsp;
            |&nbsp;{{userRecord.county}}
          </template>
        </div>
        <mu-divider shallow-inset style="margin: 100px 300px 20px 300px;width:700px"></mu-divider>
        <template v-if="accessToken">
          <router-view
            :userRecord="userRecord"
            :relation="relation"
            :works="works"
            :worksCount="worksCount"
            :introduction="userRecord.introduction"
            :iLikeForm="iLikeForm"
            :likemeForm="likemeForm"
            :commentsForm="commentsForm"
            @changePageNumber="pinGetWorksList"
            @changeLike="getUserRecord"
          ></router-view>
        </template>
        <template v-else>
          <router-view
            :userRecord="userRecord"
            :relation="relation"
            :works="works"
            :worksCount="worksCount"
            :introduction="userRecord.introduction"
            :iLikeForm="iLikeForm"
            :likemeForm="likemeForm"
            :commentsForm="commentsForm"
            @changePageNumber="touristGetWorksList"
          ></router-view>
        </template>
      </div>
    </el-main>
  </el-container>
</template>

<script>
import FeedBack from "../userSystem/feedback/feedback.vue";
import iSiderBar from "../common/iSiderBar.vue";
import SiderBar from "../common/siderBar.vue";
import axios from "axios";
import { regionData } from "element-china-area-data";

export default {
  name: "UserHome",
  components: {
    FeedBack,
    iSiderBar,
    SiderBar
  },
  data() {
    return {
      cancel: 1,
      accessToken: localStorage.hasOwnProperty("accessToken"),
      relation: "",
      options: regionData,
      pin: "",
      userRecord: {},
      works: [],
      worksCount: 0,
      iLikeForm: [],
      likemeForm: [],
      commentsForm: []
    };
  },
  mounted() {
    this.getUserPin();
  },
  watch: {
    $route(to, from) {
      if (to.params.id !== from.params.id) {
        this.$router.push({
          name: "UserEmpty",
          params: { toPath: this.$route.params.id }
        });
      }
    }
  },
  methods: {
    getUserPin() {
      axios({
        method: "get",
        url: "/api/users/getPin",
        params: {
          id: this.$route.params.id
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.pin = res.data.data.pin;
            if (this.accessToken) {
              this.getRight();
              this.pinGetWorksList(1);
            } else {
              this.touristGetWorksList(1);
            }
            this.getUserRecord();
            this.getILikeDetail();
            this.getLikemeDetail();
            this.getComments();
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
    getILikeDetail() {
      axios({
        method: "get",
        url: "/api/users/getILikeDetail",
        params: {
          pin: btoa(this.pin)
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.iLikeForm = res.data.data;
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
    getLikemeDetail() {
      axios({
        method: "get",
        url: "/api/users/getLikemeDetail",
        params: {
          pin: btoa(this.pin)
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.likemeForm = res.data.data;
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
    getComments() {
      axios({
        method: "get",
        url: "/api/users/getComments",
        params: {
          pin: btoa(this.pin)
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.commentsForm = res.data.data;
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
    pinGetWorksList(number) {
      axios({
        method: "get",
        url: "/api/users/pinListWorks",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken
        },
        params: {
          pin: btoa(this.pin),
          pageSize: 9,
          pageNumber: number
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.worksCount = res.data.data.count;
            delete res.data.data.count;
            this.works = Object.values(res.data.data);
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
    touristGetWorksList(number) {
      axios({
        method: "get",
        url: "/api/users/touristListWorks",
        params: {
          pin: btoa(this.pin),
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
    getUserRecord() {
      axios({
        method: "get",
        url: "/api/users/getUserRecord",
        params: {
          pin: btoa(this.pin)
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.userRecord = res.data.data[0];
            if (this.userRecord.city !== "") {
              let arr = this.userRecord.city.split("|");
              let obj = {};
              obj = this.options.find(item => {
                return item.value == arr[0];
              });
              this.userRecord.province = obj.label;
              obj = obj.children;
              obj = obj.find(item => {
                return item.value == arr[1];
              });
              this.userRecord.citys = obj.label;
              obj = obj.children;
              obj = obj.find(item => {
                return item.value == arr[2];
              });
              this.userRecord.county = obj.label;
            }
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
    refreshHandle() {
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
    handleFollow() {
      axios({
        method: "post",
        url: "/api/users/addFollowing",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken,
          "Content-Type": "application/json"
        },
        data: {
          friendPin: btoa(this.pin)
        },
        transformRequest: [
          function(data) {
            data = JSON.stringify(data);
            return data;
          }
        ]
      }).then(res => {
        if (res.status === 201) {
          if (res.data.errno === 10000) {
            this.$message({
              message: "关注成功",
              type: "success"
            });
            this.getRight();
            this.getUserRecord();
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
    handleDeleteFollow() {
      axios({
        method: "delete",
        url: "/api/users/deleteFollowing",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken,
          "Content-Type": "application/json"
        },
        data: {
          friendPin: btoa(this.pin)
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
              message: "取消关注成功",
              type: "success"
            });
            this.getRight();
            this.getUserRecord();
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
    handleModifyFollow() {
      axios({
        method: "put",
        url: "/api/users/modifyFollowing",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken,
          "Content-Type": "application/json"
        },
        data: {
          friendPin: btoa(this.pin)
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
              message: "取消关注成功",
              type: "success"
            });
            this.getRight();
            this.getUserRecord();
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
    handleCancel() {
      this.$confirm("确定不再关注了?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.cancel = 1;
          this.handleCancelRelation();
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消操作"
          });
        });
    },
    handleMutual() {
      this.$confirm("确定不再关注了?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.cancel = 2;
          this.handleCancelRelation();
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消操作"
          });
        });
    },
    handleCancelRelation() {
      if (this.cancel === 1) {
        this.handleDeleteFollow();
      } else {
        this.handleModifyFollow();
      }
    },
    handleSetting() {
      this.$router.push({ name: "BasicSetting" });
    },
    handleLogin() {
      this.$router.push({ name: "Login" });
    },
    handleRegister() {
      this.$router.push({ name: "Register" });
    },
    getRight() {
      axios({
        method: "get",
        url: "/api/users/getRight",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken
        },
        params: {
          pin: btoa(this.pin)
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.relation = res.data.data.relation;
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
  }
};
</script>

<style scoped>
.user-header {
  height: 100px;
}
.user-red {
  color: red;
}
.user-black {
  color: black;
}
.setting-nav {
  width: 220px;
  margin: 30px auto;
}
.setting-nav-item {
  font-size: 20px;
}
</style>
