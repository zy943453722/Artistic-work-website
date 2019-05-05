<template>
  <el-dialog
    title="意见反馈"
    :visible="this.$store.state.dialogFormVisible"
    center
    @open="loomDialog"
    @close="hiddenDialog"
    width="400px"
  >
    <el-form 
      label-position="right" 
      :model="ruleForm" 
      :rules="rules" 
      ref="ruleForm" 
      status-icon>
      <el-form-item label="昵称:" prop="nickname">
        <el-input placeholder="1-16位中文/字母/数字组合" v-model="ruleForm.nickname"></el-input>
      </el-form-item>
      <el-form-item label="邮箱:" prop="email">
        <el-input type="email" placeholder="请输入正确的邮箱地址" v-model="ruleForm.email"></el-input>
      </el-form-item>
      <el-form-item label="QQ:" prop="qq">
        <el-input placeholder="请输入正确的qq号" v-model="ruleForm.qq"></el-input>
      </el-form-item>
      <el-form-item label="反馈内容:" prop="text">
        <el-input type="textarea" placeholder="反馈内容(不超过500个字)" v-model="ruleForm.text"></el-input>
      </el-form-item>
      <div class="feedback_btn">
        <el-button type="primary" class="feedback-btn-commit" @click="commit('ruleForm')">提交</el-button>
        <el-button type="info" class="feedback-btn-reset" @click="reset('ruleForm')">重置</el-button>
        <el-button type="success" class="feedback-btn-cancel" @click="cancel">取消</el-button>
      </div>
    </el-form>
  </el-dialog>
</template>

<script>
import axios from 'axios';

export default {
  name: "FeedBack",
  data: function() {
    let nicknameRule = (rule, value, callback) => {
      let regExp = /^[\u4E00-\u9FA5A-Za-z0-9]{1,16}$/;
      if (regExp.test(value) === false) {
        callback(new Error("请保证1-16位字母/数字/中文组合"));
      } else {
        callback();
      }
    };
    let emailRule = (rule, value, callback) => {
      let regExp = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
      if (regExp.test(value) === false) {
        callback(new Error("请输入正确的邮箱地址"));
      } else {
        callback();
      }
    };
    let qqRule = (rule, value, callback) => {
      let regExp = /[1-9][0-9]{4,}/;
      if (regExp.test(value) === false) {
        callback(new Error("请输入正确的qq号"));
      } else {
        callback();
      }
    };
    return {
      ruleForm: {
        nickname: "",
        email: "",
        qq: "",
        text: ""
      },
      rules: {
        nickname: [
          { required: true, message: "取个好听的昵称吧", trigger: "blur" },
          { validator: nicknameRule, trigger: "blur" }
        ],
        email: [
          { required: true, message: "请输入邮箱", trigger: "blur" },
          { validator: emailRule, trigger: "blur" }
        ],
        qq: [
          { required: true, message: "请输入qq号", trigger: "blur" },
          { validator: qqRule, trigger: "blur" }
        ],
        text: [
          { required: true, message: "请输入反馈内容", trigger: "blur" },
          {
            min: 10,
            max: 500,
            message: "请保持最小10个字符最大500个字符",
            trigger: "blur"
          }
        ]
      }
    };
  },
  methods: {
    loomDialog: function() {
      this.$store.commit("handleDialog", true);
    },
    hiddenDialog: function() {
      this.$store.commit("handleDialog", false);
    },
    cancel: function() {
      this.$store.commit("handleDialog", false);
    },
    commit: function(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          axios({
            method: "post",
            url: "/api/users/feedback",
            headers: {
              "Content-Type": "application/json"
            },
            data: {
              nickname: this.ruleForm.nickname,
              email: this.ruleForm.email
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
                    message: "邮件发送成功，请及时在1-3日内查看邮箱，我们会对反馈做出回复",
                    type: "success"
                  });
                  this.$refs[formName].resetFields();
                  this.$store.commit("handleDialog", false);
              } else {
                this.$message({
                    message: "参数错误",
                    type: "warning"
                  });
                this.$refs[formName].resetFields();
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
    reset: function(formName) {
      this.$refs[formName].resetFields();
    }
  }
};
</script>

<style scoped>
.feedback_btn {
  display: flex;
  justify-content: center;
}
</style>
