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
            style="color:black;font-size:20px"
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
            style="color:red;font-size:20px"
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
            style="color:black;font-size:20px"
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
            style="color:red;font-size:20px"
          >评论他的</router-link>
        </p>
      </template>
    </div>
    <mu-divider shallow-inset style="margin: 20px 300px;width:700px"></mu-divider>
    <template v-if="Object.keys(commentsForm).length !== 0">
      <div
        v-for="comment in commentsForm"
        :key="comment.id"
        style="text-align:center;margin: 10px 300px;height:100px"
      >
        <router-link
          :to="{name: 'UserWorks',params:{id: comment.website.slice(26)}}"
          style="float:left"
        >
          <mu-avatar size="70" style="margin-right:10px">
            <img :src="comment.avator+'?x-oss-process=image/resize,m_lfit,h_100,w_100'">
          </mu-avatar>
        </router-link>
        <div style="float:left;margin: 0px 20px">
          <p v-if="comment.to_pin">
            <router-link
              :to="{name: 'UserWorks',params:{id: comment.website.slice(26)}}"
            >{{comment.nickname}}</router-link>
            &nbsp;&nbsp;{{handleTime(comment.create_at)}}&nbsp;&nbsp;回复了评论
          </p>
          <p v-else>
            <router-link
              :to="{name: 'UserWorks',params:{id: comment.website.slice(26)}}"
            >{{comment.nickname}}</router-link>
            &nbsp;&nbsp;{{handleTime(comment.create_at)}}&nbsp;&nbsp;评论了作品&lt;&lt;
            <router-link :to="{name: 'Art', params:{id: comment.works_id}}">{{comment.name}}</router-link>&gt;&gt;
          </p>
        </div>
        <div>
          <span class="iconfont" style="float:left">&#xe63c;</span>
          <p v-if="comment.to_pin" style="float:left">
            回复@
            {{userRecord.nickname}}
            :&nbsp;{{comment.content}}
          </p>
          <p v-else style="float:left">{{comment.content}}</p>
          <span class="iconfont" style="float:left">&#xe63a;</span>
        </div>
      </div>
    </template>
    <template v-else-if="relation !== 0">
      <p>还没有人评论他哦~~</p>
    </template>
    <template v-else>
      <p>还没有人评论您哦~~</p>
    </template>
  </div>
</template>

<script>
export default {
  name: "UserComments",
  props: ["relation", "commentsForm", "userRecord"],
  methods: {
    handleTime(time) {
      let date = new Date(time*1000);
      let Y = date.getFullYear() + "年";
      let M =
        (date.getMonth() + 1 < 10
          ? "0" + (date.getMonth() + 1)
          : date.getMonth() + 1) + "月";
      let D = date.getDate() + "日";
      let h = date.getHours() + ":";
      let m = date.getMinutes() + ":";
      let s = date.getSeconds();
      return Y + M + D + h + m + s;
    }
  }
};
</script>

<style scoped>
</style>
