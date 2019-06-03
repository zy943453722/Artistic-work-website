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
        <mu-list-item button @click="handleUser">
          <mu-list-item-title style="color:#ffffff">
            <span class="iconfont">&#xe624;</span>&nbsp;&nbsp;个人主页
          </mu-list-item-title>
        </mu-list-item>
        <mu-divider shallow-inset style="background-color: rgba(255, 255, 255, 0.3);"></mu-divider>
        <mu-list-item button @click="handleConnection">
          <mu-list-item-title style="color:#ffffff">
            <span class="iconfont">&#xe61f;</span>&nbsp;&nbsp;联系人
          </mu-list-item-title>
        </mu-list-item>
        <mu-divider shallow-inset style="background-color: rgba(255, 255, 255, 0.3);"></mu-divider>
        <mu-list-item button @click="handleSetting">
          <mu-list-item-title style="color:#ffffff">
            <span class="iconfont">&#xe622;</span>&nbsp;&nbsp;我的设置
          </mu-list-item-title>
        </mu-list-item>
        <mu-divider shallow-inset style="background-color: rgba(255, 255, 255, 0.3);"></mu-divider>
        <mu-list-item button @click="handleLogout">
          <mu-list-item-title style="color:#ffffff">
            <span class="iconfont">&#xe846;</span>&nbsp;&nbsp;登出
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
  name: "iSiderBar",
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
    handleFeedback() {
      this.$store.commit("handleDialog", true);
    },
    handleHome() {
      this.$router.push({ name: "Home" });
    },
    handleLogout() {
      this.$router.push({ name: "Logout" });
    },
    handleUser() {
      this.$router.push({
        name: "UserEmpty",
        params: { toPath: localStorage.id }
      });
    },
    handleSetting() {
      this.$router.push({ name: "BasicSetting" });
    },
    handleAbout() {
      this.$router.push({ name: "About" });
    },
    handleConnection() {
      this.$router.push({ name: "Follow" });
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
