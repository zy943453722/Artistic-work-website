<template>
  <el-container class="login-container">
    <el-header class="login-header">Artgallery</el-header>
    <sider-bar></sider-bar>
    <el-main class="login-main">
      <feed-back></feed-back>
      <h3 style="text-align:center">登录到Artgallery!</h3>
      <el-form
        label-position="right"
        label-width="70px"
        :model="ruleForm"
        :rules="rules"
        ref="ruleForm"
        status-icon
      >
        <el-form-item label="手机号:" prop="phone">
          <el-input placeholder="请输入您注册的手机号" v-model="ruleForm.phone"></el-input>
        </el-form-item>
        <el-form-item label="密码:" prop="password">
          <el-input type="password" placeholder="请输入您的密码" v-model="ruleForm.password"></el-input>
        </el-form-item>
        <div class="login-btn">
          <el-button type="primary" class="login-btn-commit" @click="submit('ruleForm')">登录</el-button>
          <el-button type="info" class="login-btn-find" @click="find">找回密码</el-button>
          <el-button type="success" class="login-btn-cancel" @click="cancel">取消</el-button>
        </div>
        <div>
          <h3 style="text-align:center">
            还没注册ArtGallery?
            <router-link :to="{name: 'Register'}">立即注册</router-link>
          </h3>
        </div>
      </el-form>
    </el-main>
    <el-footer class="login-footer">
      ©2019
      <router-link :to="{name: 'Home'}">artgallery.com</router-link>
    </el-footer>
  </el-container>
</template>

<script>
import SiderBar from "../../common/siderBar.vue";
import FeedBack from '../feedback/feedback.vue';
import axios from "axios";

export default {
  name: "Login",
  components: {
    SiderBar,
    FeedBack
  },
  data: function() {
    let phoneRule = (rule, value, callback) => {
      let regExp = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/;
      if (regExp.test(value) === false) {
        callback(new Error("请保证11位大陆手机号"));
      } else {
        callback();
      }
    };
    let passwordRule = (rule, value, callback) => {
      let regExp = /^[A-Za-z0-9]{8,16}$/;
      if (regExp.test(value) === false) {
        callback(new Error("请保证8-16位字母/数字组合"));
      } else {
        callback();
      }
    };
    return {
      ruleForm: {
        phone: "",
        password: ""
      },
      rules: {
        phone: [
          { required: true, message: "请输入手机号", trigger: "blur" },
          { validator: phoneRule, trigger: "blur" }
        ],
        password: [
          { required: true, message: "请输入密码", trigger: "blur" },
          { validator: passwordRule, trigger: "blur" }
        ]
      }
    };
  },
  methods: {
    successHandle: function() {
      let pin = btoa(this.phone);
      axios({
        method: "get",
        url: "/api/users/token",
        headers: {
          "x-artgallery-pin": pin
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.$message({
              message: "登录成功",
              type: "success"
            });
            localStorage.accessToken = res.data.data.accessToken;
            localStorage.refreshToken = res.data.data.refreshToken;
            localStorage.pin = pin;
            this.$router.push({ name: "Home" });
          } else {
            this.$message({
              message: res.data.errmsg,
              type: "warning"
            });
          }
        } else {
          this.$message.error("服务器请求错误");
          return false;
        }
      });
    },
    submit: function(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          axios({
            method: "post",
            url: "/api/users/login",
            headers: {
              "Content-Type": "application/json"
            },
            data: {
              phoneNumber: this.ruleForm.phone,
              password: this.ruleForm.password
            },
            transformRequest: [
              function(data) {
                data = JSON.stringify(data);
                return data;
              }
            ]
          }).then(response => {
            if (response.status === 200) {
              switch (response.data.errno) {
                case 20001:
                  this.$message({
                    message: "参数错误",
                    type: "warning"
                  });
                  return false;
                case 40001:
                  this.$message({
                    message: "该用户尚未注册",
                    type: "warning"
                  });
                  return false;
                case 40003:
                  this.$message({
                    message: "该用户已登录,请勿重复登录",
                    type: "warning"
                  });
                  return false;
                case 40004:
                  this.$message({
                    message: "密码错误,登录失败",
                    type: "warning"
                  });
                  return false;
                case 10000:
                  this.successHandle();
                  break;
                default:
                  break;
              }
            } else {
              this.$message.error("服务器请求错误");
              return false;
            }
          });
        } else {
          this.$message({
            message: "信息有误，请仔细检查",
            type: "warning"
          });
          return false;
        }
      });
    },
    find: function() {
      this.$router.push({ name: "ForgotPassword" });
    },
    cancel: function() {
      this.$router.push({ name: "Home" });
    }
  }
};
</script>

<style scoped>
.login-container {
  background-color: honeydew;
}
.login-header {
  text-align: center;
  font-size: 60px;
  margin: 50px 0px 0px 0px;
  color: black;
}
.login-footer {
  text-align: center;
}
.login-main {
  border: thin solid black;
  margin: 30px 400px;
  background-color: white;
}
.el-form-item {
  width: 90%;
}
.login-btn {
  display: flex;
  justify-content: center;
}
</style>
