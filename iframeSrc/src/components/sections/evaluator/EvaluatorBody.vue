<template>
  <div>
    <template v-if="isEvaluatorActive">
      <OverlayItem v-for="(item,i) in prints"
                   :key="i"
                   :index="i"
      ></OverlayItem>
      <div class="footer px-6 py-1">
        <div @click="addPrint">
          <v-chip color="primary" label class="px-1 mr-2 py-1 height-auto">
            <v-icon small>mdi-plus</v-icon>
          </v-chip>
          <span class="font-weight-medium">{{$t('btns.add_overlay')}}</span>
        </div>
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
    ...mapMutations({
      clearPrints: 'print/clearPrints',
    }),
    ...mapActions({
      addPrint: 'print/addPrint',
    }),
  },
  watch: {
    isEvaluatorActive(val, oldVal) {
      if (val === true && oldVal === false) {
        this.addPrint();
      } else if (val === false && oldVal === true) {
        this.clearPrints();
      }
    },
  },
};
</script>

<style scoped>
  .footer {
    background: #eee;
    margin-bottom: 2px;
    border-bottom: 2px solid rgb(25, 72, 127);
  }
  .v-card {
    box-shadow: none;
  }
</style>
