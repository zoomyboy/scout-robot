<template>
</template>

<style lang="less">
    .cp-wrap.cp-js-nav {

    }
</style>

<script>
    export default {
        data: function() {
            return  {
                entries: require('./entry/app.js')
            };
        },
        methods: {
            goto: function(entry) {
                if (entry.route != undefined) {
                    this.$router.push({name: entry.route}); return;
                }

                if (entry.href != undefined) {
                    window.location.href = entry.href; return;
                }
            }
        },
        mounted: function() {

        }
    }
</script>
