<template>
  <div>
    <span class="btn-expand iconfont" @click="handleClick" v-if="isLeft">&#xe72f;</span>
    <span class="btn-shrink iconfont" @click="handleClick" v-if="isRight">&#xe600;</span>
    <mu-drawer :open.sync="open" :docked="docked" class="sider-drawer">
      <mu-list>
        <mu-list-item button @click="handleHome">
          <mu-list-item-title style="color:#ffffff">
            <span class="iconfont">&#xe61b;</span>&nbsp;&nbsp;首页
          </mu-list-item-title>
        </mu-list-item>
        <mu-divider shallow-inset style="background-color: rgba(255, 255, 255, 0.3);"></mu-divider>
        <mu-list-item button @click="handleRegister">
          <mu-list-item-title style="color:#ffffff">
            <span class="iconfont">&#xe71c;</span>&nbsp;&nbsp;注册
          </mu-list-item-title>
        </mu-list-item>
        <mu-divider shallow-inset style="background-color: rgba(255, 255, 255, 0.3);"></mu-divider>
        <mu-list-item button @click="handleLogin">
          <mu-list-item-title style="color:#ffffff">
            <span class="iconfont">&#xe653;</span>&nbsp;&nbsp;登录
          </mu-list-item-title>
        </mu-list-item>
        <mu-divider shallow-inset style="background-color: rgba(255, 255, 255, 0.3);"></mu-divider>
        <mu-list-item button @click="handleFeedback">
          <mu-list-item-title style="color:#ffffff">
            <span class="iconfont">&#xe625;</span>&nbsp;&nbsp;意见反馈
          </mu-list-item-title>
        </mu-list-item>
        <mu-divider shallow-inset style="background-color: rgba(255, 255, 255, 0.3);"></mu-divider>
        <mu-list-item button @click="handleAbout">
          <mu-list-item-title style="color:#ffffff">
            <span class="iconfont">&#xe645;</span>&nbsp;&nbsp;关于我们
          </mu-list-item-title>
        </mu-list-item>
      </mu-list>
    </mu-drawer>
  </div>
</template>

<script>
export default {
  name: "SiderBar",
  data: function() {
    return {
      docked: true,
      open: false,
      isLeft: true,
      isRight: false
    };
  },
  methods: {
    handleClick: function() {
      this.open = !this.open;
      this.isLeft = !this.isLeft;
      this.isRight = !this.isRight;
    },
    handleFeedback: function() {
      this.$store.commit("handleDialog", true);
    },
    handleLogin() {
      this.$router.push({ name: "Login" });
    },
    handleRegister() {
      this.$router.push({ name: "Register" });
    },
    handleAbout() {
      this.$router.push({ name: "About" });
    },
    handleHome() {
      this.$router.push({ name: "Home" });
    }
  }
};
</script>

<style scoped>
.sider-drawer {
  background: #2c2b2a;
}
.btn-expand {
  cursor: pointer;
  position: relative;
  left: 50px;
  font-size: 25px;
}
.btn-shrink {
  cursor: pointer;
  position: relative;
  left: 256px;
  font-size: 25px;
}
</style>
