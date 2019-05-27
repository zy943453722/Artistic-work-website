<template>
    <div></div>
</template>

<script>
export default {
    name: 'UserEmpty',
    mounted() {
        let to = this.$route.params.toPath;
        if (to) {
            this.$router.push({ name: "UserWorks",params: {id: to} });
        }
    }    
}
</script>

<style scoped>

</style>
