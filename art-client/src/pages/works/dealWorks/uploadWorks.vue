<template>
  <div style="text-align:center">
    <h1>发表作品</h1>
    <el-form
      label-position="right"
      label-width="90px"
      :model="ruleForm"
      :rules="rules"
      ref="ruleForm"
      status-icon
    >
      <el-form-item label="作品长度:" prop="length">
        <el-input placeholder="请输入正整数值" v-model="ruleForm.length">
          <template slot="append">cm</template>
        </el-input>
      </el-form-item>
      <el-form-item label="作品高度:" prop="height">
        <el-input placeholder="请输入正整数值" v-model="ruleForm.height">
          <template slot="append">cm</template>
        </el-input>
      </el-form-item>
      <el-form-item label="作品类别:" prop="type">
        <el-select filterable placeholder="请选择类别" style="float:left" v-model="ruleForm.type">
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="作品名:" prop="name">
        <el-input placeholder="为你的作品起个好听的名字吧~~" v-model="ruleForm.name"></el-input>
      </el-form-item>
      <el-form-item label="作品简介:" prop="introduction">
        <el-input
          type="textarea"
          placeholder="给你的作品写个简介吧~~"
          :rows="5"
          :maxlength="250"
          show-word-limit="true"
          v-model="ruleForm.introduction"
        ></el-input>
      </el-form-item>
      <el-form-item label="作品文件:">
        <el-upload
          class="works-uploader"
          :action="oss.host"
          :data="oss"
          :on-success="handleSuccess"
          :before-upload="handleBefore"
          :on-remove="handleRemove"
          :limit="1"
          :on-exceed="handleLimit"
          :file-list="fileList"
        >
          <el-button type="primary" round>点击上传</el-button>
          <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过3M</div>
        </el-upload>
      </el-form-item>
      <el-form-item label="创作时间:" prop="time">
        <el-date-picker type="year" placeholder="选择年" style="float:left" v-model="ruleForm.time"></el-date-picker>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="save('ruleForm')">发布</el-button>
        <el-button type="success" @click="cancel">取消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "UploadWorks",
  data() {
    let lengthRule = (rule, value, callback) => {
      let regExp = /^[1-9]\d*$/;
      if (regExp.test(value) === false) {
        callback(new Error("请保证正整数"));
      } else {
        callback();
      }
    };
    let nameRule = (rule, value, callback) => {
      let regExp = /^[\u4E00-\u9FA5A-Za-z0-9]{1,16}$/;
      if (regExp.test(value) === false) {
        callback(new Error("请保证1-16位字母/数字/中文组合"));
      } else {
        callback();
      }
    };
    return {
      imageUrl: "",
      fileList: [],
      ruleForm: {
        length: "",
        height: "",
        type: "",
        name: "",
        introduction: "",
        time: ""
      },
      rules: {
        length: [
          { required: true, message: "请填写作品长度", trigger: "blur" },
          { validator: lengthRule, trigger: "blur" }
        ],
        height: [
          { required: true, message: "请填写作品高度", trigger: "blur" },
          { validator: lengthRule, trigger: "blur" }
        ],
        name: [
          { required: true, message: "请填写作品名", trigger: "blur" },
          { validator: nameRule, trigger: "blur" }
        ],
        introduction: [
          { required: true, message: "请填写作品介绍", trigger: "blur" },
          {
            min: 10,
            max: 250,
            message: "长度在 10 到 250 个字符",
            trigger: "blur"
          }
        ],
        type: [
          { required: true, message: "请选择作品类别", trigger: "change" }
        ],
        time: [{ required: true, message: "请选择创作日期", trigger: "change" }]
      },
      oss: {
        key: "",
        OSSAccessKeyId: "",
        policy: "",
        Signature: "",
        host: "",
        success_action_status: 200
      },
      options: [
        {
          value: "1",
          label: "油画",
          type: "类别"
        },
        {
          value: "2",
          label: "丙烯",
          type: "类别"
        },
        {
          value: "3",
          label: "水彩",
          type: "类别"
        },
        {
          value: "4",
          label: "雕塑",
          type: "类别"
        },
        {
          value: "5",
          label: "国画",
          type: "类别"
        },
        {
          value: "6",
          label: "版画",
          type: "类别"
        },
        {
          value: "7",
          label: "复制品",
          type: "类别"
        },
        {
          value: "8",
          label: "综合材料",
          type: "类别"
        },
        {
          value: "9",
          label: "装置",
          type: "类别"
        },
        {
          value: "10",
          label: "摄影",
          type: "类别"
        },
        {
          value: "11",
          label: "素描",
          type: "类别"
        },
        {
          value: "12",
          label: "数码绘画",
          type: "类别"
        },
        {
          value: "13",
          label: "漆画",
          type: "类别"
        },
        {
          value: "14",
          label: "新媒体",
          type: "类别"
        },
        {
          value: "15",
          label: "壁画",
          type: "类别"
        },
        {
          value: "16",
          label: "插画",
          type: "类别"
        },
        {
          value: "17",
          label: "其他",
          type: "类别"
        }
      ]
    };
  },
  mounted() {
    this.getOssSign();
  },
  methods: {
    handleSuccess() {
      this.imageUrl = this.oss.host + "/" + this.oss.key;
    },
    save(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (this.fileList.length === 0) {
            this.$message({
              message: "请选择您要上传的作品",
              type: "warning"
            });
            return false;
          } else {
            let timestamp = new Date(Date.parse(this.ruleForm.time));
            axios({
              method: "post",
              url: "/api/works/add",
              headers: {
                Authorization: "Bearer " + localStorage.accessToken,
                "Content-Type": "application/json"
              },
              data: {
                length: this.ruleForm.length,
                height: this.ruleForm.height,
                type: this.ruleForm.type,
                name: this.ruleForm.name,
                introduction: this.ruleForm.introduction,
                instance: this.imageUrl,
                makeAt: timestamp.getFullYear(),
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
                    message: "作品发布成功",
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
          }
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
    },
    handleBefore(file) {
      const isJPG = file.type === "image/jpeg";
      const isPNG = file.type === "image/png";
      const isLt2M = file.size / 1024 / 1024 < 3;

      if (!isJPG && !isPNG) {
        this.$message.error("上传头像图片只能是 JPG 格式或 PNG 格式!");
        return false;
      }
      if (!isLt2M) {
        this.$message.error("上传头像图片大小不能超过 3MB!");
        return false;
      }
      this.fileList[0] = file.name;
    },
    handleLimit() {
      this.$message({
        message: "一次最多只能传一件作品,请删除后重新选择",
        type: "warning"
      });
    },
    handleRemove() {
      this.fileList = [];
    },
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
    getOssSign() {
      axios({
        method: "get",
        url: "/api/users/getUploadSign",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken
        },
        params: {
          status: 1
        }
      }).then(res => {
        if (res.status === 200) {
          if (res.data.errno === 10000) {
            this.oss.key = res.data.data.dir + this.$uuid.v1();
            this.oss.policy = res.data.data.policy;
            this.oss.Signature = res.data.data.signature;
            this.oss.host = res.data.data.host;
            this.oss.OSSAccessKeyId = res.data.data.accessid;
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
.works-uploader {
  float: left;
}
</style>
