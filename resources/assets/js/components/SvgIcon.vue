<template>
  <div class="svg-icon" v-html="require(`./iframe/svg/${icon}.svg`)"></div>
</template>

<script>
  export default {
    props: ['icon']
  }
</script>

<style lang="scss" type="text/scss">
</style>
