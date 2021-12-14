<template>
  <div>
    <template v-if="isEvaluatorActive">
      <OverlayItem v-for="(item,i) in prints"
                   :key="i"
                   :index="i"
      ></OverlayItem>
      <div @click="addPrint">
        <v-btn>+</v-btn> Ще нанесення
      </div>
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
import OverlayItem from './OverlayItem.vue';

export default {
  inject: ['theme'],
  name: 'EvaluatorBody',
  components: {
    OverlayItem,
  },
  computed: {
    ...mapGetters({
      isEvaluatorActive: 'main/isEvaluatorActive',
      prints: 'print/prints',
    }),
  },
  methods: {
    ...mapMutations([]),
    ...mapActions({
      addPrint: 'print/addPrint',
    }),
  },
};
</script>

<style scoped>

</style>
