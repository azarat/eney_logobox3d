<template>
  <div>
    <template v-if="isModelActive">
      <iframe :src="modelFrame"
              width="100%"
              height="560"
              frameborder="0"></iframe>
    </template>
    <template v-else>
      <v-sheet
      >
        <v-skeleton-loader
          class="mx-auto"
          type="card"
        ></v-skeleton-loader>
      </v-sheet>
    </template>
  </div>
</template>

<script>
import { mapGetters, mapMutations, mapActions } from 'vuex';

export default {
  inject: ['theme'],
  name: 'ModelBody',
  components: {},
  computed: {
    ...mapGetters({
      isModelActive: 'main/isModelActive',
    }),
    modelFrame() {
      return 'https://giftcollection.info/api/template/preview/20?token=qweqwe&lang=ua';
    },
  },
  methods: {
    ...mapMutations({}),
    ...mapActions({}),
  },
};
</script>

<style scoped>

</style>
