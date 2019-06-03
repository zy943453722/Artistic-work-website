<template>
    <el-container class="about-container">
        <el-header class="about-header">关于Artgallery</el-header>
        <el-main class="about-main">
            <p>感谢您使用Artgallery。我们致力于给您提供舒适的服务。</p>
            <p>在如今的社会大背景下，人们越来越关注自己的精神文化生活，
                对于越来越丰富的艺术形式，如果仅靠原始的线下作品展的
                方式和交流的方式已经很难深入人心，更别提艺术的推广了。
                因此，利用越来越发达的网络技术将线下的展览和交流移植到网络上
                无疑是一条行之有效的途径。在线上，来自五湖四海的人们，
                包括喜爱艺术作品的、了解艺术作品的、想了解的甚至仅仅是随
                意浏览的都能在此处聚集，共同欣赏并且发表自己的评论和意见。
                虽然这可能并不是最佳的推广艺术作品的途径，但是可以为推广提供强有力的支持，
                而转移到线上之后，还可以节省大量的人力物力，减少线下活动带来的不必要损耗，
                从而提升效率。另外，近年来，成型的艺术作品网站并不多，无法满足人们的需求，
                也需要通过不断的设计开发新的网站来拓宽这个市场，从而满足人们的需要。</p>
            <p>分析国内外艺术作品网站的设计理念和选材，根据他们的优点，抽象出自己网站的设计模板，
                之后再运用合适的技术和开发工具对网站进行开发实现。本网站包含两个大系统，一
                个是用户管理系统，另一个是艺术作品展示系统，同时管理员管理系统后期有时间可以具体实现。
                用户管理系统应当包含用户的登陆、注册、注销等基本功能模块，而艺术作品展示系统就包含
                个人作品管理、交流管理、操作作品管理等基本功能模块。而后期实现的管理员管理系统可以包含
                审核管理、提权管理、角色管理等功能模块。同时，力求本网站有良好的UI和UE，符合大众人群的审美</p>
            <p>在国外，艺术作品网站出现已经比较多了。相对较大的诸如Pinterest、Arrested Motion、
                Artsy、Huffpost Arts News等网站已经比较完备，拥有的用户数也已经达到百万级别，
                可以说规模是比较大了。但相较于国外如此完备的艺术作品网站，国内就逊色很多，
                仅有几家比较像样的艺术作品网站，例如Hi艺术、Artand、墙报等。大量的艺术作品爱好
                者还停留在不断的参加线下作品展等来认识艺术，走进艺术。然而，传统的方式已经无法满足
                人们日益增长的物质文化生活，因为它已经不是时代潮流且不能形成很好的推广效果。
                当今的发展趋势已经越来越趋近网络化、服务化，因此转移到线上是很有必要的。</p>        
        </el-main>
        <el-footer class="about-footer">
            ©2019 <router-link :to="{name: 'Home'}">artgallery.com</router-link>
        </el-footer>
    </el-container>
</template>

<script>
export default {
    name: 'About'
}
</script>

<style scoped>
.about-container {
    background-color:honeydew;
}
.about-header {
    font-family: 'wenyiheiti';
    text-align: center;
    font-size: 60px;
    margin: 50px 0px 0px 0px;
    color: black;
}
.about-main {
    border: thin solid black;
    margin: 100px 300px;
    background-color: white;
}
.about-footer {
    text-align: center;
}
p {
    font-family: 'Microsoft YaHei';
    font-size: 15px;
}
</style>
