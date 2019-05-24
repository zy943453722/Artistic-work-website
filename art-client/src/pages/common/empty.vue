<template>
    <div></div>
</template>

<script>
export default {
    name: 'Empty',
    mounted() {
        let to = this.$route.params.toPath;
        if (to) {
            this.$router.push({ name: "Art",params: {id: to} });
        }
    }    
}
</script>

<style scoped>

</style>
