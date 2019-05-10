<template>
  <el-container class="register-container">
    <el-header class="register-header">Artgallery</el-header>
    <sider-bar></sider-bar>
    <el-main class="register-main">
      <feed-back></feed-back>
      <h3 style="text-align:center">欢迎入驻Artgallery!</h3>
      <el-form label-position="right" label-width="90px" :model="ruleForm" :rules="rules" ref="ruleForm" status-icon>
        <el-form-item label="昵称:" prop="nickname">
          <el-input placeholder="1-16位中文/字母/数字组合" v-model="ruleForm.nickname"></el-input>
        </el-form-item>
        <el-form-item label="手机号:" prop="phone">
          <el-input placeholder="11位中国大陆手机号" v-model="ruleForm.phone"></el-input>
        </el-form-item>
        <el-form-item label="密码:" prop="password">
          <el-input type="password" placeholder="8-16位字母数字组合" v-model="ruleForm.password"></el-input>
        </el-form-item>
        <el-form-item label="确认密码:" prop="checkPass">
          <el-input type="password" placeholder="请确保与上方密码一致" v-model="ruleForm.checkPass"></el-input>
        </el-form-item>
        <el-form-item label="验证码:" class="register-code" prop="code">
          <el-input placeholder="4位数字验证码" class="register-code-input" v-model="ruleForm.code"></el-input>
          <timer-btn class="register-code-btn" :phone="ruleForm.phone"></timer-btn>
        </el-form-item>
        <div class="register-btn">
          <el-button type="primary" class="register-btn-commit" @click="submitForm('ruleForm')">提交</el-button>
          <el-button type="info" class="register-btn-reset" @click="resetForm('ruleForm')">重置</el-button>
          <el-button type="success" class="register-btn-cancel" @click="cancelForm">取消</el-button>
        </div>
        <div>
          <h3 style="text-align:center">
            您的账号开通后代表已同意
            <router-link :to="{name: 'Terms'}">&laquo;用户注册协议&raquo;</router-link>
          </h3>
          <h3 style="text-align:center">
            若已有账号，请直接
            <router-link :to="{name: 'Login'}">登录</router-link>
          </h3>
        </div>
      </el-form>
    </el-main>
    <el-footer class="register-footer">
      ©2019
      <router-link :to="{name: 'Home'}">artgallery.com</router-link>
    </el-footer>
  </el-container>
</template>

<script>
import TimerBtn from '../../common/timerBtn.vue';
import SiderBar from '../../common/siderBar.vue';
import FeedBack from '../feedback/feedback.vue';
import axios from 'axios';

export default {
  name: "Register",
  components: {
    TimerBtn,
    SiderBar,
    FeedBack
  },
  data: function() {
    let nicknameRule = (rule, value, callback) => {
      let regExp = /^[\u4E00-\u9FA5A-Za-z0-9]{1,16}$/;
      if (regExp.test(value) === false) {
        callback(new Error('请保证1-16位字母/数字/中文组合'));
      } else {
        callback();
      }
    };
    let phoneRule = (rule, value, callback) => {
      let regExp = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/;
      if (regExp.test(value) === false) {
        callback(new Error('请保证11位大陆手机号'));
      } else {
        callback();
      }
    };
    let passwordRule = (rule, value, callback) => {
      let regExp = /^[A-Za-z0-9]{8,16}$/;
      if (regExp.test(value) === false) {
        callback(new Error('请保证8-16位字母/数字组合'));
      } else {
        callback();
      }
    };
    let checkPassRule = (rule, value, callback) => {
      if (value !== this.ruleForm.password) {
        callback(new Error('请保证两次输入密码一致'));
      } else {
        callback();
      }
    };
    let codeRule = (rule, value, callback) => {
      let regExp = /^\d{4}$/;
      if (regExp.test(value) === false) {
        callback(new Error('请保证4位数字'));
      } else {
        callback();
      }
    };
    return {
      ruleForm: {
        nickname: '',
        phone: '',
        password: '',
        checkPass: '',
        code: ''
      },
      rules: {
        nickname: [
          {required: true, message: '取个好听的昵称吧', trigger: 'blur'},
          {validator: nicknameRule, trigger:'blur'}
        ],
        phone: [
          {required: true, message: '请输入手机号', trigger: 'blur'},
          {validator: phoneRule, trigger: 'blur'}
        ],
        password: [
          {required: true, message: '请输入密码', trigger: 'blur'},
          {validator: passwordRule, trigger: 'blur'}
        ],
        checkPass: [
          {required: true, message: '请确认密码', trigger: 'blur'},
          {validator: checkPassRule, trigger: 'blur'}
        ],
        code: [
          {required: true, message: '请输入验证码', trigger: 'blur'},
          {validator: codeRule, trigger: 'blur'}
        ]
      }
    }
  },
  methods: {
    submitForm: function(formName) {
      this.$refs[formName].validate((valid) => {
          if (valid) {
            axios(
              {
                method: 'post',
                url: '/api/users/add',
                headers: {
                  'Content-Type': 'application/json'
                },
                data: {
                  phoneNumber: this.ruleForm.phone,
                  password: this.ruleForm.checkPass,
                  nickname: this.ruleForm.nickname,
                  verifyCode: this.ruleForm.code
                },
                transformRequest: [function(data) {
                  data = JSON.stringify(data);
                  return data;
                }]
              }).then(response => {
              if (response.status === 201) {
                  let pin = btoa(response.data.data.pin);
                  let id = response.data.data.id;
                  axios({
                    method: 'get',
                    url: '/api/users/token',
                    headers: {
                      'x-artgallery-pin': pin
                    }
                  }).then(
                    res => {
                      if (res.status === 200) {
                        if (res.data.errno === 10000) {
                          this.$message({
                            message: '注册成功',
                            type: 'success'
                          });
                          localStorage.accessToken = res.data.data.accessToken;
                          localStorage.refreshToken = res.data.data.refreshToken;
                          localStorage.pin = pin;
                          localStorage.id = id;
                          this.$router.push({name: 'Home'});
                        } else {
                            this.$message({
                            message: res.data.errmsg,
                            type: 'warning'
                          });
                        }
                      } else {
                        this.$message.error('服务器请求错误');
                        return false;
                      }
                    }
                  );
              } else if(response.status === 200) {
                  if(response.data.errno === 50001) {
                    this.$message({
                        message:'验证码输入错误,请填写正确的验证码',
                        type: 'warning'
                    });
                    return false;
                } else {
                    this.$message({
                        message:'参数有误',
                        type: 'warning'
                    });
                    return false;
                }
              } else {
                  this.$message.error('服务器请求错误');
                  return false;
              }
            });
          } else {
            this.$message({
              message:'信息有误，请仔细检查',
              type: 'warning'
            });
            return false;
          }
        });
    },
    resetForm: function(formName) {
      this.$refs[formName].resetFields();
    },
    cancelForm: function() {
      this.$router.push({name: 'Home'});
    }
  }
};
</script>

<style scoped>
.register-container {
  background-color: honeydew;
}
.register-header {
  text-align: center;
  font-size: 60px;
  margin: 50px 0px 0px 0px;
  color: black;
}
.register-footer {
  text-align: center;
}
.register-main {
  border: thin solid black;
  margin: 50px 400px;
  background-color: white;
}
.register-btn {
  display: flex;
  justify-content: center;
}
.el-form-item {
  width: 90%;  
}
.register-code-input {
  width: 63%;
}
.register-code-btn {
  display: inline;
}
</style>
