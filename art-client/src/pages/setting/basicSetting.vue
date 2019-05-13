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
    <el-form
      label-position="right"
      label-width="70px"
      :model="ruleForm"
      :rules="rules"
      ref="ruleForm"
      status-icon
    >
      <el-form-item label="头像:">
        <mu-avatar style="float:left">
          <img src="../../assets/avator.jpg">
        </mu-avatar>
        <el-button type="success" round style="float:left">更改头像</el-button>
      </el-form-item>
      <el-form-item label="昵称:" prop="nickname">
        <el-input placeholder="请输入您的昵称" v-model="ruleForm.nickname" @change="changeNickname"></el-input>
      </el-form-item>
      <el-form-item label="性别:">
        <el-radio-group
          v-model="ruleForm.sex"
          style="float:left;margin-top:12px"
          @change="changeRadio"
        >
          <el-radio :label="1">男</el-radio>
          <el-radio :label="2">女</el-radio>
          <el-radio :label="3">保密</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item class="setting-block" label="生日:">
        <el-date-picker
          v-model="ruleForm.time"
          type="date"
          placeholder="选择日期"
          style="float:left"
          @change="changeTime"
          value-format="timestamp"
        ></el-date-picker>
      </el-form-item>
      <el-form-item label="地区:">
        <el-cascader
          size="large"
          :options="options"
          v-model="ruleForm.area"
          @change="changeArea"
          style="float:left"
        ></el-cascader>
      </el-form-item>
      <el-form-item label="个人简介:">
        <el-input
          type="textarea"
          :rows="5"
          v-model="ruleForm.text"
          placeholder="不超过500个字符"
          @change="changeText"
        ></el-input>
      </el-form-item>
      <el-form-item label="手机号:">
        <span style="float:left">{{ruleForm.phone}}</span>
      </el-form-item>
      <div class="setting-btn">
        <el-button type="primary" @click="save('ruleForm')">保存</el-button>
        <el-button type="success" @click="cancel">取消</el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import { regionData } from "element-china-area-data";
import axios from "axios";

export default {
  name: "BasicSetting",
  data() {
    let nicknameRule = (rule, value, callback) => {
      let regExp = /^[\u4E00-\u9FA5A-Za-z0-9]{1,16}$/;
      if (regExp.test(value) === false) {
        callback(new Error("请保证1-16位字母/数字/中文组合"));
      } else {
        callback();
      }
    };
    return {
      options: regionData,
      ruleForm: {
        nickname: "",
        sex: "",
        time: "",
        area: [],
        text: "",
        phone: ""
      },
      rules: {
        nickname: [
          { required: true, message: "取个好听的昵称吧", trigger: "change" },
          { validator: nicknameRule, trigger: "change" }
        ],
        sex: [{ required: true, message: "请选择性别", trigger: "change" }],
        text: [
          {
            required: false,
            message: "个人简介不要超过500个字符",
            trigger: "change"
          }
        ]
      },
      changeForm: {},
      items: [
        {
          text: "基本资料",
          disabled: false,
          to: { name: "BasicSetting" },
          class: "setting-nav-item-red"
        },
        {
          text: "修改密码",
          disabled: false,
          to: { name: "PasswordSetting" },
          class: "setting-nav-item-black"
        }
      ]
    };
  },
  mounted() {
    this.getBasicInfo();
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
    getBasicInfo() {
      axios({
        method: "get",
        url: "/api/users/getUserInfo",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.ruleForm.nickname = res.data.data[0].nickname;
            this.ruleForm.sex = res.data.data[0].sex;
            this.ruleForm.time = res.data.data[0].birthday * 1000;
            this.ruleForm.area = res.data.data[0].city.split("|");
            this.ruleForm.text = res.data.data[0].introduction;
            this.ruleForm.phone = res.data.data[0].pin;
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
    changeNickname() {
      this.changeForm.nickname = this.ruleForm.nickname;
    },
    changeText() {
      this.changeForm.introduction = this.ruleForm.text;
    },
    changeTime() {
      this.changeForm.birthday = this.ruleForm.time / 1000;
    },
    changeArea() {
      this.changeForm.city = this.ruleForm.area.join("|");
    },
    changeRadio() {
      this.changeForm.sex = this.ruleForm.sex;
    },
    save(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          axios({
            method: "put",
            url: "/api/users/modifyUserInfo",
            headers: {
              Authorization: "Bearer " + localStorage.accessToken,
              "Content-Type": "application/json"
            },
            data: this.changeForm,
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
