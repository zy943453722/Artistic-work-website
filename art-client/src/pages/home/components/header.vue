<template>
  <el-row>
    <el-col :span="18">
      <div class="grid-content header-image">
        <router-link :to="{name: 'Home'}">
          <img
            class="header-img"
            src="../../../assets/logo.jpg"
            alt="artgallery"
            title="artgallery"
          >
        </router-link>
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
        <el-button size="medium" type="success" v-if="!accessToken" @click="handleRegister">注册</el-button>
        <el-button size="medium" type="info" v-if="!accessToken"  @click="handleLogin">登录</el-button>
        <el-button size="medium" type="success" v-if="accessToken"  @click="handleSetting">设置</el-button>
        <el-button size="medium" type="info" v-if="accessToken"  @click="handleLogout">登出</el-button>
      </div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: "HomeHeader",
  data () {
    return {
      accessToken: localStorage.hasOwnProperty("accessToken"),
    }
  },
  methods: {
    //handleWhite: function() {},
    //handleBlack: function() {},
    handleRegister () {
      this.$router.push({name: "Register"});
    },
    handleLogin () {
      this.$router.push({name: "Login"});
    },
    handleSetting () {
      this.$router.push({name: "BasicSetting"});
    },
    handleLogout () {
      this.$router.push({name: "Logout"});
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
  font-family:"Microsoft YaHei";
  display: flex;
  align-items: center;
  justify-content: space-around;
}
</style>
