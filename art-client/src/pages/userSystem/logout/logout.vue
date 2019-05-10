<template>
  <h3>正在登出，请稍后......</h3>
</template>

<script>
import axios from "axios";

export default {
  name: "Logout",
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
            this.$router.push({ name: "Logout" });  
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
          this.$router.push({ name: "Login" });
        } else {
          this.$message.error("服务器请求错误");
          this.$router.push({ name: "Home" });
        }
      });
    }
  },
  mounted() {
    axios({
      method: "put",
      url: "/api/users/logout",
      headers: {
        "x-artgallery-refreshToken": localStorage.refreshToken,
        Authorization: "Bearer " + localStorage.accessToken
      }
    }).then(res => {
      if (res.status === 200) {
        switch (res.data.errno) {
          case 40005:
            this.refreshHandle();
            break;
          case 40006:
            this.$message({
              message: "缺少refreshToken,登出失败",
              type: "warning"
            });
            this.$router.push({ name: "Home" });
            break;
          case 10000:
            localStorage.removeItem("refreshToken");
            localStorage.removeItem("accessToken");
            localStorage.removeItem("pin");
            localStorage.removeItem("id");
            this.$message({
              message: "登出成功",
              type: "success"
            });
            this.$router.push({ name: "Home" });
            break;
          default:
            break;
        }
      } else if (res.status === 401) {
        this.$message({
          message: res.data.errmsg,
          type: "warning"
        });
        this.$router.push({ name: "Home" });
      } else {
        this.$message.error("服务器请求错误");
        this.$router.push({ name: "Home" });
      }
    });
  }
};
</script>

<style scoped>
</style>
