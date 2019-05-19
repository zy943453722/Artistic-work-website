<template>
  <div>
    <div style="text-align:center">
      <template v-if="relation === 0">
        <p>
          <router-link
            :to="{name: 'UserWorks', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >主页</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserAbout', params:{id: this.$route.params.id}}"
            style="color:red;font-size:20px"
          >简介</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserILike', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >我赞过的</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserLikeme', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >赞过我的</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserComments', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >评论我的</router-link>
        </p>
      </template>
      <template v-else>
        <p>
          <router-link
            :to="{name: 'UserWorks', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >主页</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserAbout', params:{id: this.$route.params.id}}"
            style="color:red;font-size:20px"
          >简介</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserILike', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >他赞过的</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserLikeme', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >赞过他的</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserComments', params:{id: this.$route.params.id}}"
            style="color:black;font-size:20px"
          >评论他的</router-link>
        </p>
      </template>
    </div>
    <mu-divider shallow-inset style="margin: 20px 300px;width:700px"></mu-divider>
    <div>
        <template v-if="introduction">
            <p style="text-align:center">{{introduction}}</p>
        </template>
        <template v-else-if="relation !== 0">
            <p style="text-align:center">这家伙很懒，还没有填写简介~~</p>
        </template>
        <template v-else>
          <p style="text-align:center">快去添加简介吧，好让大家认识你啊~~</p>
        </template>
    </div>
  </div>
</template>

<script>
export default {
  name: "UserAbout",
  props: ["relation","introduction"]
};
</script>

<style scoped>
</style>
