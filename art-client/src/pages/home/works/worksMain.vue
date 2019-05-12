<template>
  <mu-container>
    <mu-breadcrumbs class="home-works-nav" divider="|" v-if="accessToken">
      <mu-breadcrumbs-item
        class="home-works-nav-item"
        v-for="item in pinItems"
        :key="item.text"
        :disabled="item.disabled"
        :to="item.to"
        :class="item.class"
      >{{item.text}}</mu-breadcrumbs-item>
    </mu-breadcrumbs>
    <mu-breadcrumbs class="home-works-nav" divider="—" v-if="!accessToken">
      <mu-breadcrumbs-item
        class="home-works-nav-item"
        v-for="item in items"
        :key="item.text"
        :disabled="item.disabled"
        :to="item.to"
        :class="item.class"
      >{{item.text}}</mu-breadcrumbs-item>
    </mu-breadcrumbs>
    <div>
      <el-row>
        所有作品&nbsp;&gt;&nbsp;
        <el-tag
          :key="tag"
          v-for="tag in dynamicTags"
          closable
          :disable-transitions="false"
          @close="handleClose(tag)"
        >{{tag}}</el-tag>
      </el-row>
      <mu-divider shallow-inset class="works-divider"></mu-divider>
      <el-row>
        <el-col :span="4" class="works-select-type">
          <el-select v-model="value" filterable placeholder="全部类别" @change="handleTypeChange">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            ></el-option>
          </el-select>
        </el-col>
        <el-col :span="4" class="works-select-length">
          <el-select
            v-model="lengthValue"
            filterable
            placeholder="全部尺寸"
            @change="handleLengthChange"
          >
            <el-option
              v-for="item in lengthOptions"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            ></el-option>
          </el-select>
        </el-col>
        <el-col :span="4" class="works-select-time">
          <el-select v-model="timeValue" filterable placeholder="全部年代" @change="handleTimeChange">
            <el-option
              v-for="item in timeOptions"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            ></el-option>
          </el-select>
        </el-col>
        <el-col :span="10">
          <el-input placeholder="可通过艺术家/作品名查询" v-model="search" class="input-with-select">
            <el-select
              v-model="selectValue"
              slot="prepend"
              placeholder="选项"
              class="works-select-select"
            >
              <el-option
                v-for="item in selectOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              ></el-option>
            </el-select>
            <el-button slot="append" icon="el-icon-search" @click="searchWorks"></el-button>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <el-row :gutter="20" v-if="Object.keys(works).length !== 0">
      <div class="home-works" v-for="work in works" :key="work.id">
        <el-col :span="8">
          <div>
            <router-link :to="{name: 'Art',params:{id: work.id}}">{{work.name}}</router-link>
            <template v-if="work.hasOwnProperty('relation')">
              <el-button @click="pinHandleLikeRelation(work.pin,work.id)" v-if="accessToken">
                <span class="iconfont">&#xe621;</span>
                &nbsp;{{work.likes}}
              </el-button>
            </template>
            <template v-else>
              <el-button @click="pinHandleLike(work.id,work.pin)" v-if="accessToken">
                <span class="iconfont">&#xe620;</span>&nbsp;赞一下
              </el-button>
              <el-button @click="handleLike" v-if="!accessToken">
                <span class="iconfont">&#xe620;</span>&nbsp;赞一下
              </el-button>
            </template>
            <p>
              作者:
              <router-link
                :to="{name: 'UserWorks',params:{id: work.website.slice(26)}}"
              >{{work.nickname}}</router-link>
            </p>
            <p>已被{{work.likes}}人点赞</p>
          </div>
        </el-col>
      </div>
    </el-row>
    <el-row v-else>
      <p style="text-align:center">找不到对应的筛选条件的作品~~</p>
    </el-row>
    <el-pagination
      style="text-align:center;margin:50px 0px 100px 0px"
      background
      layout="prev, pager, next, jumper"
      :page-size="9"
      :total="worksCount"
      :current-page="currentPage"
      hide-on-single-page="true"
      @current-change="handleCurrentChange"
      @prev-click="handlePrevChange"
      @next-click="handleNextChange"
    ></el-pagination>
  </mu-container>
</template>

<script>
import axios from "axios";

export default {
  name: "WorksMain",
  props: ["works", "worksCount"],
  data() {
    return {
      accessToken: localStorage.hasOwnProperty("accessToken"),
      currentPage: 1,
      value: "",
      typeOld: "",
      lengthOld: "",
      timeOld: "",
      lengthValue: "",
      timeValue: "",
      selectValue: "",
      search: "",
      dynamicTags: [],
      dynamicType: {},
      selectOptions: [
        {
          value: "1",
          label: "艺术家"
        },
        {
          value: "2",
          label: "作品名"
        }
      ],
      timeOptions: [
        {
          value: "1",
          label: "2019",
          type: "年代",
          makeAtStart: 2019
        },
        {
          value: "2",
          label: "2018",
          type: "年代",
          makeAtStart: 2018
        },
        {
          value: "3",
          label: "2017",
          type: "年代",
          makeAtStart: 2017
        },
        {
          value: "4",
          label: "3年以内",
          type: "年代",
          makeAtStart: 2016,
          makeAtEnd: 2019
        },
        {
          value: "5",
          label: "5年以内",
          type: "年代",
          makeAtStart: 2014,
          makeAtEnd: 2019
        },
        {
          value: "6",
          label: "21世纪",
          type: "年代",
          makeAtStart: 1999,
          makeAtEnd: 2019
        },
        {
          value: "7",
          label: "90年代",
          type: "年代",
          makeAtStart: 1989,
          makeAtEnd: 2000
        },
        {
          value: "8",
          label: "80年代",
          type: "年代",
          makeAtStart: 1979,
          makeAtEnd: 1990
        },
        {
          value: "9",
          label: "更早",
          type: "年代",
          makeAtEnd: 1980
        }
      ],
      lengthOptions: [
        {
          value: "1",
          label: "0~20cm",
          type: "尺寸",
          lengthMin: 0,
          lengthMax: 21
        },
        {
          value: "2",
          label: "20~50cm",
          type: "尺寸",
          lengthMin: 20,
          lengthMax: 51
        },
        {
          value: "3",
          label: "50~80cm",
          type: "尺寸",
          lengthMin: 50,
          lengthMax: 81
        },
        {
          value: "4",
          label: "80~120cm",
          type: "尺寸",
          lengthMin: 80,
          lengthMax: 121
        },
        {
          value: "5",
          label: "120~150cm",
          type: "尺寸",
          lengthMin: 120,
          lengthMax: 151
        },
        {
          value: "6",
          label: "150~200cm",
          type: "尺寸",
          lengthMin: 150,
          lengthMax: 201
        },
        {
          value: "7",
          label: ">200cm",
          type: "尺寸",
          lengthMin: 200
        }
      ],
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
      ],
      items: [
        {
          text: "艺术家",
          disabled: false,
          to: { name: "Home" },
          class: "home-nav-item-artist"
        },
        {
          text: "作品",
          disabled: false,
          to: { name: "Works" },
          class: "home-nav-item-works"
        }
      ],
      pinItems: [
        {
          text: "首页",
          disabled: false,
          to: { name: "Home" },
          class: "home-nav-item-artist"
        },
        {
          text: "所有作品",
          disabled: false,
          to: { name: "Works" },
          class: "home-nav-item-works"
        }
      ]
    };
  },
  methods: {
    handleClose(tag) {
      let index = this.dynamicTags.indexOf(tag);
      this.dynamicTags.splice(index, 1);
      let ele = tag.split(":");
      switch (ele[0]) {
        case "类别":
          this.value = "";
          delete this.dynamicType.type;
          break;
        case "尺寸":
          this.lengthValue = "";
          delete this.dynamicType.lengthMin;
          delete this.dynamicType.lengthMax;
          break;
        case "年代":
          this.timeValue = "";
          delete this.dynamicType.makeAtStart;
          delete this.dynamicType.makeAtEnd;
          break;
        default:
          break;
      }
      this.$emit("changeTag", this.dynamicType);
    },
    handleTypeChange(data) {
      let obj = {};
      obj = this.options.find(item => {
        return item.value === data;
      });
      const label = obj.type + ":" + obj.label;
      if (this.typeOld === "") {
        this.dynamicTags.push(label);
        this.dynamicType.type = data;
      } else {
        let index = this.dynamicTags.indexOf(this.typeOld);
        this.dynamicTags[index] = label;
        this.dynamicType.type = data;
      }
      this.typeOld = label;
      this.$emit("changeTag", this.dynamicType);
    },
    handleLengthChange(data) {
      let obj = {};
      obj = this.lengthOptions.find(item => {
        return item.value === data;
      });
      const label = obj.type + ":" + obj.label;
      let exist = obj.hasOwnProperty("lengthMax");
      if (this.lengthOld === "") {
        this.dynamicTags.push(label);
      } else {
        let index = this.dynamicTags.indexOf(this.lengthOld);
        this.dynamicTags[index] = label;
      }
      if (exist) this.dynamicType.lengthMax = obj.lengthMax;
      else delete this.dynamicType.lengthMax;
      this.dynamicType.lengthMin = obj.lengthMin;
      this.lengthOld = label;
      this.$emit("changeTag", this.dynamicType);
    },
    handleTimeChange(data) {
      let obj = {};
      obj = this.timeOptions.find(item => {
        return item.value === data;
      });
      const label = obj.type + ":" + obj.label;
      let startExist = obj.hasOwnProperty("makeAtStart");
      let endExist = obj.hasOwnProperty("makeAtEnd");
      if (this.timeOld === "") {
        this.dynamicTags.push(label);
      } else {
        let index = this.dynamicTags.indexOf(this.timeOld);
        this.dynamicTags[index] = label;
      }
      if (startExist && endExist) {
        this.dynamicType.makeAtStart = obj.makeAtStart;
        this.dynamicType.makeAtEnd = obj.makeAtEnd;
      } else if (startExist) {
        this.dynamicType.makeAtStart = obj.makeAtStart;
        delete this.dynamicType.makeAtEnd;
      } else {
        this.dynamicType.makeAtEnd = obj.makeAtEnd;
        delete this.dynamicType.makeAtStart;
      }
      this.timeOld = label;
      this.$emit("changeTag", this.dynamicType);
    },
    searchWorks() {
      if (this.selectValue === "" || this.search === "") {
        this.$message({
          message: "请选择完整再搜索",
          type: "warning"
        });
        return false;
      }
      if (this.selectValue === "1") {
        this.dynamicType.nickname = this.search;
      } else {
        this.dynamicType.name = this.search;
      }
      this.$emit("changeTag", this.dynamicType);
    },
    handleLike() {
      this.$router.push({ name: "Login" });
    },
    pinHandleLike(id,pin) {
      axios({
        method: "post",
        url: "/api/works/addLikes",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken,
          "Content-Type": "application/json"
        },
        data: {
          worksId: id,
          pin: btoa(pin)
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
            location.reload();
          } else if (res.data.errno === 40005) {
            this.refreshHandle();
          } else {
            this.$message({
              message: "参数有误",
              type: "warning"
            });
            return false;
          }
        } else if (res === 401) {
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
    pinHandleLikeRelation(pin,id) {
        this.$confirm("确定不再喜欢了?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.handleCancelLike(pin, id);
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消操作"
          });
        });
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
            this.$router.push({ name: "Works" });
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
    handleCancelLike(pin, id) {
      axios({
        method: "delete",
        url: "/api/works/deleteLikes",
        headers: {
          Authorization: "Bearer " + localStorage.accessToken,
          "Content-Type": "application/json"
        },
        data: {
          worksId: id,
          pin: btoa(pin)
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
            location.reload();
          } else if (res.data.errno === 40005) {
            this.refreshHandle();
          } else {
            this.$message({
              message: "参数有误",
              type: "warning"
            });
            return false;
          }
        } else if (res === 401) {
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
    handleCurrentChange(currentPage) {
      this.currentPage = currentPage;
      this.dynamicType.pageNumber = this.currentPage;
      this.$emit("changeTag", this.dynamicType);
    },
    handlePrevChange(prevPage) {
      this.currentPage = prevPage;
    },
    handleNextChange(nextPage) {
      this.currentPage = nextPage;
    }
  }
};
</script>

<style scoped>
.home-works-nav {
  width: 500px;
  margin: 0 auto;
}
.home-works-nav-item {
  font-size: 20px;
}
.home-nav-item-artist {
  color: black;
}
.home-nav-item-works {
  color: red;
}
.works-divider {
  margin: 10px 0px;
}
.works-select-type {
  margin: 0px 20px 30px 0px;
}
.works-select-length {
  margin: 0px 20px 30px 0px;
}
.works-select-time {
  margin: 0px 20px 30px 0px;
}
.input-with-select .el-input-group__prepend {
  background-color: #fff;
}
.works-select-select {
  width: 100px;
}
.el-tag {
  margin-right: 10px;
}
</style>
