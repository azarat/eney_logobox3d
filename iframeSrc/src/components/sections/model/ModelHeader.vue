<template>
  <v-container
    fluid
    dense
    :style="evaluateContainerStyle">
    <v-row dense class="py-3">
      <v-col md="4" sm="12" cols="12" class="d-flex align-center justify-center font-weight-bold">
        <span class="text-uppercase white--text title">{{$t('model.title')}}</span>
      </v-col>
      <v-col md="4" sm="12" cols="12" align="center">
        <template v-if="isModelActive">
          <v-btn @click="deactivateModel" color="accent"
                 class="px-1">
            <span class="subtitle-1 grey--text text--darken-4 font-weight-bold px-5">
              {{$t('btns.activated')}}
            </span>
            <v-icon color="accent"
                    class="green darken-3 p-1px border-radius-5">mdi-check-bold</v-icon>
          </v-btn>
        </template>
        <template v-else>
          <v-btn @click="onClickActivate" color="accent"
                 class="px-1">
            <span class="subtitle-1 grey--text text--darken-4 font-weight-bold px-5">
              {{$t('btns.activate')}}
            </span>
            <v-icon color="accent"
                    class="deep-orange p-1px border-radius-5">mdi-power-off</v-icon>
          </v-btn>
        </template>
      </v-col>
      <v-col md="4" sm="12" cols="12" align="center"></v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';

export default {
  name: 'EvaluatorHeader',
  computed: {
    ...mapGetters({
      isEvaluatorActive: 'main/isEvaluatorActive',
      isModelActive: 'main/isModelActive',
    }),
    evaluateContainerStyle() {
      const style = {
        background: (this.isModelActive) ? '#19487f' : '#606060',
        padding: '0',
        margin: '0',
        'border-radius': '0',
      };
      return style;
    },
  },
  methods: {
    ...mapMutations({
      activateModel: 'main/activateModel',
      deactivateModel: 'main/deactivateModel',
      activateEvaluator: 'main/activateEvaluator',
    }),
    onClickActivate() {
      this.activateEvaluator();
      this.activateModel();
    },
  },
};
</script>

<style scoped>

</style>
