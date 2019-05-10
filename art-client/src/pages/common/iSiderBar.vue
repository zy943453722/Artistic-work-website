<template>
  <div>
    <span class="btn-expand iconfont" @click="handleClick" v-if="isLeft">&#xe72f;</span>
    <span class="btn-shrink iconfont" @click="handleClick" v-if="isRight">&#xe600;</span>
    <mu-drawer :open.sync="open" :docked="docked">
      <mu-list>
        <mu-list-item button @click="handleHome">
          <mu-list-item-title>首页</mu-list-item-title>
        </mu-list-item>
        <mu-list-item button @click="handleUser">
          <mu-list-item-title>个人主页</mu-list-item-title>
        </mu-list-item>
        <mu-list-item button @click="handleConnection">
          <mu-list-item-title>联系人</mu-list-item-title>
        </mu-list-item>
        <mu-list-item button @click="handleSetting">
          <mu-list-item-title>我的设置</mu-list-item-title>
        </mu-list-item>
        <mu-list-item button @click="handleLogout">
          <mu-list-item-title>登出</mu-list-item-title>
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
    name: 'iSiderBar',
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
    handleFeedback () {
      this.$store.commit('handleDialog', true);
    },
    handleHome () {
      this.$router.push({name: "Home"});
    },
    handleLogout () {
      this.$router.push({name: "Logout"});
    },
    handleUser () {
      this.$router.push({name: "UserWorks",params: {id: localStorage.id}});
    },
    handleSetting () {
      this.$router.push({name: "Setting"});
    },
    handleAbout () {
      this.$router.push({name: "About"});
    },
    handleConnection () {
      this.$router.push({name: "Connection"});
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
