<template>
  <el-container class="forgot-container">
    <el-header class="forgot-header">Artgallery</el-header>
    <sider-bar></sider-bar>
    <el-main class="forgot-main">
      <feed-back></feed-back>
      <h3 style="text-align:center">找回密码</h3>
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
        <el-form-item label="验证码:" class="forgot-code" prop="code">
          <el-input placeholder="4位数字验证码" class="forgot-code-input" v-model="ruleForm.code"></el-input>
          <i-time-btn class="forgot-code-btn" :phone="ruleForm.phone"></i-time-btn>
        </el-form-item>
        <el-form-item class="forgot-btn">
          <el-button type="primary" class="forgot-btn-commit" @click="submitForm('ruleForm')">修改密码</el-button>
          <el-button type="success" class="forgot-btn-cancel" @click="cancelForm">取消</el-button>
        </el-form-item>
      </el-form>
    </el-main>
    <el-footer class="forgot-footer">
      ©2019
      <router-link :to="{name: 'Home'}">artgallery.com</router-link>
    </el-footer>
  </el-container>
</template>

<script>
import SiderBar from "../../common/siderBar.vue";
import FeedBack from "../feedback/feedback.vue";
import iTimeBtn from "../../common/iTimeBtn.vue";
import axios from "axios";

export default {
  name: "ForgotPassword",
  components: {
    SiderBar,
    FeedBack,
    iTimeBtn
  },
  data: function() {
    let phoneRule = (rule, value, callback) => {
      let regExp = /^(13[0-9]|14[5|7|9]|15[0|1|2|3|5|6|7|8|9]|16[6]|17[0|1|2|3|5|6|7|8]|18[0-9]|19[8|9])\d{8}$/;
      if (regExp.test(value) === false) {
        callback(new Error("请保证11位大陆手机号"));
      } else {
        callback();
      }
    };
    let codeRule = (rule, value, callback) => {
      let regExp = /^\d{4}$/;
      if (regExp.test(value) === false) {
        callback(new Error("请保证4位数字"));
      } else {
        callback();
      }
    };
    return {
      ruleForm: {
        phone: "",
        code: ""
      },
      rules: {
        phone: [
          { required: true, message: "请输入手机号", trigger: "blur" },
          { validator: phoneRule, trigger: "blur" }
        ],
        code: [
          { required: true, message: "请输入验证码", trigger: "blur" },
          { validator: codeRule, trigger: "blur" }
        ]
      }
    };
  },
  methods: {
    submitForm: function(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          axios
            .get("/api/users/verifyUserCode", {
              params: {
                phoneNumber: this.ruleForm.phone,
                verifyCode: this.ruleForm.code
              }
            })
            .then(res => {
              if (res.status === 200) {
                if (res.data.errno === 50001) {
                    this.$message({
                    message: "验证码有误，请重新输入",
                    type: "warning"
                   });
                   return false;
                } else if (res.data.errno === 10000) {
                    this.$message({
                    message: "验证成功，正在跳转...",
                    type: "success"
                   });
                   this.$router.push({name: 'ResetPassword',params: {pin: this.ruleForm.phone}});
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
        } else {
          this.$message({
            message: "信息有误，请仔细检查",
            type: "warning"
          });
          return false;
        }
      });
    },
    cancelForm: function() {
      this.$router.push({ name: "Home" });
    }
  }
};
</script>

<style scoped>
.forgot-container {
  background-color: honeydew;
}
.forgot-header {
  text-align: center;
  font-size: 60px;
  margin: 50px 0px 0px 0px;
  color: black;
}
.forgot-footer {
  text-align: center;
}
.forgot-main {
  border: thin solid black;
  margin: 30px 450px;
  background-color: white;
}
.forgot-code-input {
  width: 61%;
}
.forgot-btn {
  display: flex;
  justify-content: center;
}
.forgot-code-btn {
  display: inline;
}
</style>
