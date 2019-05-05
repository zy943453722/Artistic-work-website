<template>
  <el-container class="reset-container">
    <el-header class="reset-header">Artgallery</el-header>
    <el-main class="reset-main">
      <h3 style="text-align:center">设置新密码</h3>
      <h4 style="text-align:center">当前账号: {{this.$route.params.pin}}</h4>
      <el-form
        label-position="top"
        label-width="70px"
        :model="ruleForm"
        :rules="rules"
        ref="ruleForm"
        status-icon
      >
        <el-form-item label="新密码:" prop="password">
          <el-input type="password" placeholder="8-16位字母数字组合" v-model="ruleForm.password"></el-input>
        </el-form-item>
        <el-form-item label="确认密码:" prop="checkPass">
          <el-input type="password" placeholder="请确保与上方密码一致" v-model="ruleForm.checkPass"></el-input>
        </el-form-item>
        <div class="reset-btn">
          <el-button type="primary" @click="submitForm('ruleForm')">确定</el-button>
          <el-button type="success" @click="cancel">取消</el-button>
        </div>
      </el-form>
    </el-main>
    <el-footer class="reset-footer">
      ©2019
      <router-link :to="{name: 'Home'}">artgallery.com</router-link>
    </el-footer>
  </el-container>
</template>

<script>
import axios from "axios";

export default {
  name: "ResetPassword",
  data: function() {
    let passwordRule = (rule, value, callback) => {
      let regExp = /^[A-Za-z0-9]{8,16}$/;
      if (regExp.test(value) === false) {
        callback(new Error("请保证8-16位字母/数字组合"));
      } else {
        callback();
      }
    };
    let checkPassRule = (rule, value, callback) => {
      if (value !== this.ruleForm.password) {
        callback(new Error("请保证两次输入密码一致"));
      } else {
        callback();
      }
    };
    return {
      ruleForm: {
        password: "",
        checkPass: ""
      },
      rules: {
        password: [
          { required: true, message: "请输入新密码", trigger: "blur" },
          { validator: passwordRule, trigger: "blur" }
        ],
        checkPass: [
          { required: true, message: "请确认密码", trigger: "blur" },
          { validator: checkPassRule, trigger: "blur" }
        ]
      }
    };
  },
  methods: {
    cancel() {
      this.$router.push({ name: "Home" });
    },
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          axios({
            method: "put",
            url: "/api/users/findUserPassword",
            headers: {
              "Content-Type": "application/json"
            },
            data: {
              phoneNumber: this.$route.params.pin,
              password: this.ruleForm.checkPass
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
                  message: "找回密码成功,正在跳转...",
                  type: "success"
                });
                this.$router.push({ name: "Login" });
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
    }
  }
};
</script>

<style scoped>
.reset-container {
  background-color: honeydew;
}
.reset-main {
  border: thin solid black;
  margin: 30px 450px;
  background-color: white;
}
.reset-header {
  text-align: center;
  font-size: 60px;
  margin: 50px 0px 0px 0px;
  color: black;
}
.reset-footer {
  text-align: center;
}
.reset-btn {
  display: flex;
  justify-content: center;
}
</style>
