<template>
  <v-container
    fluid
    dense
    :style="evaluateContainerStyle">
    <v-row dense class="py-3">
      <v-col md="4" sm="12" cols="12" class="d-flex align-center justify-center font-weight-bold">
        <span class="text-uppercase white--text title">{{$t('evaluator.title')}}</span>
      </v-col>
      <v-col md="4" sm="12" cols="12" align="center">
        <template v-if="isEvaluatorActive">
          <v-btn @click="deactivateEvaluator" color="accent"
                 class="px-1">
            <span class="subtitle-1 grey--text text--darken-4 font-weight-bold px-5">
              {{$t('btns.activated')}}
            </span>
            <v-icon color="accent"
                    class="green darken-3 p-1px border-radius-5">mdi-check-bold</v-icon>
          </v-btn>
        </template>
        <template v-else>
          <v-btn @click="activateEvaluator" color="accent"
                 class="px-1">
            <span class="subtitle-1 grey--text text--darken-4 font-weight-bold px-5">
              {{$t('btns.activate')}}
            </span>
            <v-icon color="accent"
                    class="deep-orange p-1px border-radius-5">mdi-power-off</v-icon>
          </v-btn>
        </template>
      </v-col>
      <v-col md="4" sm="12" cols="12" align="center">
        <v-btn @click="save" :disabled="!isEvaluatorActive"
               class="px-1 mr-2">
            <span class="subtitle-1 grey--text px-5"
                  :class="isEvaluatorActive ? 'text--darken-4 font-weight-bold' : 'text--darken-3'">
              {{$t('btns.done')}}
            </span>
          <v-icon color="accent"
                  :class="isEvaluatorActive ? 'deep-orange' : 'grey'"
                  class="p-1px border-radius-5">mdi-power-off</v-icon>
        </v-btn>
        <v-btn :disabled="!isEvaluatorActive" class="px-1 py-1 mim-w-auto">
          <v-icon color="accent"
                  class="p-1px border-radius-5 red darken-4">mdi-close</v-icon>
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapGetters, mapMutations, mapActions } from 'vuex';

export default {
  name: 'EvaluatorHeader',
  computed: {
    ...mapGetters({
      mainOverlay: 'main/overlay',
      mainDialog: 'main/dialog',
      isEvaluatorActive: 'main/isEvaluatorActive',
    }),
    overlay: {
      get() {
        return this.mainOverlay;
      },
      set(value) {
        this.setter({
          prop: 'overlay',
          value,
        });
      },
    },
    dialog: {
      get() {
        return this.mainDialog;
      },
      set(value) {
        this.setter({
          prop: 'overlay',
          value,
        });
      },
    },
    evaluateContainerStyle() {
      const style = {
        background: (this.isEvaluatorActive) ? '#19487f' : '#606060',
        padding: '0',
        margin: '0',
        'border-radius': '0',
      };
      return style;
    },
  },
  methods: {
    ...mapMutations({
      setter: 'main/setter',
      activateEvaluator: 'main/activateEvaluator',
      deactivateEvaluator: 'main/deactivateEvaluator',
    }),
    ...mapActions({
      httpAllPrints: 'print/httpAllPrints',
      submitFullForm: 'print/submitFullForm',
    }),
    save() {
      this.overlay = true;
      this.submitFullForm();
    },
  },
};
</script>
