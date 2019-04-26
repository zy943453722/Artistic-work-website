<template>
  <el-row>
    <el-col :span="18">
      <div class="grid-content header-image">
        <a href="#">
          <img
            class="header-img"
            src="../../../assets/logo.jpg"
            alt="artgallery"
            title="artgallery"
          >
        </a>
      </div>
    </el-col>
    <el-col :span="6">
      <div class="grid-content header-btu">
        <el-dropdown>
          <el-button size="medium" type="primary">模式</el-button>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item @click="handleWhite()">白天</el-dropdown-item>
            <el-dropdown-item @click="handleBlack()">夜晚</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
        <el-button size="medium" type="success">注册</el-button>
        <el-button size="medium" type="info">登录</el-button>
      </div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: "HomeHeader",
  methods: {
    handleWhite: function() {
      
    },
    handleBlack: function() {
     
    }
  }
};
</script>

<style scoped> 
.grid-content {
  height: 100px;
}
.header-img {
  width: 100px;
  height: 100px;
}
.header-btu {
  display: flex;
  align-items: center;
  justify-content: space-around;
}
</style>
