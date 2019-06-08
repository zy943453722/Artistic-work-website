<template>
  <div style="text-align:center">
    <mu-breadcrumbs class="connect-nav" divider="|">
      <mu-breadcrumbs-item
        class="connect-nav-item"
        v-for="item in items"
        :key="item.text"
        :disabled="item.disabled"
        :to="item.to"
        :class="item.class"
      >{{item.text}}</mu-breadcrumbs-item>
    </mu-breadcrumbs>
    <mu-divider shallow-inset class="works-divider"></mu-divider>
    <p style="font-family: 'Microsoft YaHei';font-size:15px">
      您关注了
      <span style="font-family: 'stup';font-size:25px;color: purple">{{count}}</span>
      个人，其中互粉好友
      <span style="font-family: 'stup';font-size:25px;color: purple">{{mutualCount}}</span>人
    </p>
    <mu-divider shallow-inset class="works-divider"></mu-divider>
    <p v-if="count === 0" style="font-family: 'Microsoft YaHei';font-size:15px">您还未关注任何人，快去找寻吧~~</p>
    <div v-for="card in followData" :key="card.website.slice(26)">
      <el-row>
        <el-col :span="8" style="height:50px">
          <router-link
            :to="{name: 'UserWorks',params:{id: card.website.slice(26)}}"
            style="float:left;margin-right:10px"
          >
            <mu-avatar>
              <img
                :src="card.avator + '?x-oss-process=image/resize,m_lfit,h_100,w_100'"
                :title="card.nickname"
              >
            </mu-avatar>
          </router-link>
          <router-link
            :to="{name: 'UserWorks',params:{id: card.website.slice(26)}}"
            style="float:left;line-height:50px"
          >{{card.nickname}}</router-link>
        </el-col>
        <el-col :span="16">
          <template v-if="card.status === 0">
            <el-button
              type="primary"
              style="float:right;width:100px"
              @click="handleCancel(card.friend_pin)"
            >
              <span class="iconfont">&#xe62b;</span>&nbsp;取消
            </el-button>
          </template>
          <template v-else-if="card.status === 2">
            <el-button
              type="primary"
              style="float:right;width:100px"
              @click="handleMutual(card.friend_pin)"
            >互相关注</el-button>
          </template>
          <template v-else>
            <el-button type="primary" style="float:right;width:100px">
              <span class="iconfont">&#xe61a;</span>&nbsp;关注
            </el-button>
          </template>
        </el-col>
      </el-row>
      <mu-divider shallow-inset class="works-divider"></mu-divider>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Follow",
  props: ["followData", "count", "mutualCount"],
  data() {
    return {
      cancel: 1,
      items: [
        {
          text: "关注的人",
          disabled: false,
          to: { name: "Follow" },
          class: "connect-nav-item-red"
        },
        {
          text: "我的粉丝",
          disabled: false,
          to: { name: "Fans" },
          class: "connect-nav-item-black"
        }
      ]
    };
  },
  methods: {
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
    deleteFollowing(pin) {
      axios({
        method: "delete",
        url: "/api/users/deleteFollowing",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken,
          "Content-Type": "application/json"
        },
        data: {
          friendPin: btoa(pin)
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
            this.$emit("changeFollow", this.$route.path);
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
    modifyFollowing(pin) {
      axios({
        method: "put",
        url: "/api/users/modifyFollowing",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken,
          "Content-Type": "application/json"
        },
        data: {
          friendPin: btoa(pin)
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
            this.$emit("changeFollow", this.$route.path);
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
    handleCancel(pin) {
      this.$confirm("确定不再关注了?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.cancel = 1;
          this.handleCancelRelation(pin);
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消操作"
          });
        });
    },
    handleMutual(pin) {
      this.$confirm("确定不再关注了?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.cancel = 2;
          this.handleCancelRelation(pin);
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消操作"
          });
        });
    },
    handleCancelRelation(pin) {
      if (this.cancel === 1) {
        this.deleteFollowing(pin);
      } else {
        this.modifyFollowing(pin);
      }
    }
  }
};
</script>

<style scoped>
.works-divider {
  margin: 20px 20px;
}
.connect-nav {
  width: 220px;
  margin: 0 auto;
}
.connect-nav-item {
  font-size: 20px;
}
.connect-nav-item-black {
  color: black;
}
.connect-nav-item-red {
  color: red;
}
</style>
