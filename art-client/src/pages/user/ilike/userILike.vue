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
            style="color:red;font-size:20px"
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
            style="color:black;font-size:20px"
          >简介</router-link>&nbsp;|&nbsp;
          <router-link
            :to="{name: 'UserILike', params:{id: this.$route.params.id}}"
            style="color:red;font-size:20px"
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
    <el-row :gutter="20" v-if="Object.keys(iLikeForm).length !== 0" style="margin: 20px 150px">
      <div v-for="iLike in iLikeForm" :key="iLike.works_id">
        <el-col :span="8">
          <div>
            <div style="width:300px;height:220px">
              <router-link :to="{name: 'Art',params:{id: iLike.works_id}}">
                <img
                  :src="iLike.instance + '?x-oss-process=image/resize,m_lfit,h_300,w_300'"
                  alt="图片加载失败，请稍等"
                  :title="iLike.name"
                  style="width:300px;height:200px"
                >
              </router-link>
            </div>
            <router-link :to="{name: 'Art',params:{id: iLike.works_id}}">{{iLike.name}}</router-link>
            <p>{{iLike.make_at}}年作品</p>
            <p>共计{{iLike.likes}}个赞</p>
          </div>
        </el-col>
      </div>
    </el-row>
    <el-row v-else-if="relation !== 0">
      <p style="text-align:center">该用户还没贡献过赞哦~~</p>
    </el-row>
    <el-row v-else>
        <p style="text-align:center">您还没点赞过呢，快去欣赏作品吧~~</p>
    </el-row>
  </div>
</template>

<script>
export default {
  name: "UserILike",
  props: ["iLikeForm", "relation"]
};
</script>

<style scoped>
</style>
