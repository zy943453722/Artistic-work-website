<template>
  <div style="text-align:center">
    <mu-breadcrumbs class="setting-nav" divider="|">
      <mu-breadcrumbs-item
        class="setting-nav-item"
        v-for="item in items"
        :key="item.text"
        :disabled="item.disabled"
        :to="item.to"
        :class="item.class"
      >{{item.text}}</mu-breadcrumbs-item>
    </mu-breadcrumbs>
    <el-form label-position="right" :model="ruleForm" :rules="rules" ref="ruleForm" status-icon>
      <el-form-item label="旧密码:" prop="oldPassword">
        <el-input type="password" placeholder="请输入您的旧密码" v-model="ruleForm.oldPassword"></el-input>
      </el-form-item>
      <el-form-item label="新密码:" prop="newPassword">
        <el-input type="password" placeholder="8-16位字母数字组合" v-model="ruleForm.newPassword"></el-input>
      </el-form-item>
      <el-form-item label="确认密码:" prop="checkPassword">
        <el-input type="password" placeholder="请确保与上方密码一致" v-model="ruleForm.checkPassword"></el-input>
      </el-form-item>
      <div class="setting-btn">
        <el-button type="primary" @click="save('ruleForm')">保存</el-button>
        <el-button type="success" @click="cancel">取消</el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "PasswordSetting",
  data() {
    let oldPasswordRule = (rule, value, callback) => {
      let regExp = /^[A-Za-z0-9]{8,16}$/;
      if (regExp.test(value) === false) {
        callback(new Error("请保证8-16位字母/数字组合"));
      } else {
        callback();
      }
    };
    let newPasswordRule = (rule, value, callback) => {
      let regExp = /^[A-Za-z0-9]{8,16}$/;
      if (regExp.test(value) === false) {
        callback(new Error("请保证8-16位字母/数字组合"));
      } else if (value === this.ruleForm.oldPassword) {
        callback(new Error("请保证新旧密码不一样"));
      } else {
        callback();
      }
    };
    let checkPassRule = (rule, value, callback) => {
      if (value !== this.ruleForm.newPassword) {
        callback(new Error("请保证两次输入密码一致"));
      } else {
        callback();
      }
    };
    return {
      ruleForm: {
        oldPassword: "",
        newPassword: "",
        checkPassword: ""
      },
      rules: {
        oldPassword: [
          { required: true, message: "请输入旧密码", trigger: "blur" },
          { validator: oldPasswordRule, trigger: "blur" }
        ],
        newPassword: [
          { required: true, message: "请输入新密码", trigger: "blur" },
          { validator: newPasswordRule, trigger: "blur" }
        ],
        checkPassword: [
          { required: true, message: "请确认密码", trigger: "blur" },
          { validator: checkPassRule, trigger: "blur" }
        ]
      },
      items: [
        {
          text: "基本资料",
          disabled: false,
          to: { name: "BasicSetting" },
          class: "setting-nav-item-black"
        },
        {
          text: "修改密码",
          disabled: false,
          to: { name: "PasswordSetting" },
          class: "setting-nav-item-red"
        }
      ]
    };
  },
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
          this.$router.push({ name: "Logout" });
        } else {
          this.$message.error("服务器请求错误");
          this.$router.push({ name: "Home" });
        }
      });
    },
    save(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          axios({
            method: "put",
            url: "/api/users/modifyUserPassword",
            headers: {
              Authorization: "Bearer " + localStorage.accessToken,
              "Content-Type": "application/json"
            },
            data: {
              oldPassword: this.ruleForm.oldPassword,
              newPassword: this.ruleForm.checkPassword
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
                  message: "修改成功",
                  type: "success"
                });
                localStorage.removeItem("refreshToken");
                localStorage.removeItem("accessToken");
                localStorage.removeItem("pin");
                localStorage.removeItem("id");
                this.$router.push({ name: "Login" });
              } else if (res.data.errno === 40005) {
                this.refreshHandle();
              } else if (res.data.errno === 40004) {
                this.$message({
                  message: "旧密码不正确",
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
        } else {
          this.$message({
            message: "信息有误，请仔细检查",
            type: "warning"
          });
          return false;
        }
      });
    },
    cancel() {
      this.$router.push({ name: "UserWorks", params: { id: localStorage.id } });
    }
  }
};
</script>

<style scoped>
.setting-nav {
  width: 220px;
  margin: 30px auto;
}
.setting-nav-item {
  font-size: 20px;
}
.setting-nav-item-black {
  color: black;
}
.setting-nav-item-red {
  color: red;
}
</style>
