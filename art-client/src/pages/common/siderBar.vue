<template>
  <div>
    <span class="btn-expand iconfont" @click="handleClick" v-if="isLeft">&#xe72f;</span>
    <span class="btn-shrink iconfont" @click="handleClick" v-if="isRight">&#xe600;</span>
    <mu-drawer :open.sync="open" :docked="docked">
      <mu-list>
        <mu-list-item button @click="handleHome">
          <mu-list-item-title>首页</mu-list-item-title>
        </mu-list-item>
        <mu-list-item button @click="handleRegister">
          <mu-list-item-title>注册</mu-list-item-title>
        </mu-list-item>
        <mu-list-item button @click="handleLogin">
          <mu-list-item-title>登录</mu-list-item-title>
        </mu-list-item>
        <mu-list-item button @click="handleFeedback">
          <mu-list-item-title>意见反馈</mu-list-item-title>
        </mu-list-item>
        <mu-list-item button @click="handleAbout">
          <mu-list-item-title>关于我们</mu-list-item-title>
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
      this.$store.commit('handleDialog', true);
    },
    handleLogin () {
      this.$router.push({name: "Login"});
    },
    handleRegister () {
      this.$router.push({name: "Register"});
    },
    handleAbout () {
      this.$router.push({name: "About"});
    },
    handleHome () {
      this.$router.push({name: "Home"});
    }
  }
};
</script>

<style scoped>
.btn-expand {
  position: relative;
  left: 50px;
  font-size: 25px;
}
.btn-shrink {
  position: relative;
  left: 256px;
  font-size: 25px;
}
</style>
