<template>
  <v-container
    fluid
    dense
    :style="evaluateContainerStyle">
    <v-row>
    <v-col cols="12" class="py-0">
      <!-- <v-col md="4" sm="12" cols="12"
                  class="d-flex align-center justify-center font-weight-bold">
        <span class="text-uppercase white--text title">{{$t('model.title')}}</span>
      </v-col> -->
      <v-col cols="12">
        <template v-if="isModelActive">
          <v-btn @click="deactivateModel"
                 class="btn-activate">

           <svg width="28" height="24" viewBox="0 0 28 24" class="mr-3" fill="none"
                xmlns="http://www.w3.org/2000/svg">
             <path d="M12 0C5.37266 0 0 5.37266 0 12C0 18.6273 5.37266 24 12
                      24C18.6273 24 24 18.6273 24 12C23.9926 5.37578 18.6242
                      0.0074219 12 0ZM12 23.2C5.81445 23.2 0.8 18.1855 0.8
                      12C0.8 5.81445 5.81445 0.8 12 0.8C18.1855 0.8 23.2
                      5.81445 23.2 12C23.193 18.1826 18.1826 23.193 12
                      23.2Z" fill="#333333"/>
             <path d="M27.649 0.358784C27.1804 -0.119839 26.4207 -0.119839
                     25.952 0.358784L11.5752 15.0414L6.04904 9.39771C5.58043 8.91909
                     4.82068 8.91914 4.35198 9.39771C3.88332 9.87629 3.88332 10.6522
                     4.35198 11.1308L10.7267 17.641C11.1952 18.1196 11.9555 18.1193
                     12.4238 17.641L27.649 2.09194C28.1177 1.61336 28.1176 0.837407
                     27.649 0.358784Z" fill="#17A558"/>
           </svg>

            <span class="subtitle-1">
              {{$t('btns.activated')}}
            </span>
          </v-btn>
        </template>
        <template v-else>
          <v-btn @click="onClickActivate"
                 class="btn-activate">

            <svg width="28" height="24" viewBox="0 0 28 24" class="mr-3" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
              <path d="M12 0C5.37266 0 0 5.37266 0 12C0 18.6273 5.37266 24 12
                       24C18.6273 24 24 18.6273 24 12C23.9926 5.37578 18.6242
                       0.0074219 12 0ZM12 23.2C5.81445 23.2 0.8 18.1855 0.8
                       12C0.8 5.81445 5.81445 0.8 12 0.8C18.1855 0.8 23.2
                       5.81445 23.2 12C23.193 18.1826 18.1826 23.193 12
                       23.2Z" fill="#333333"/>
            </svg>

            <span class="subtitle-1">
              {{$t('btns.activate')}}
            </span>
          </v-btn>
        </template>
      </v-col>
      <!-- <v-col md="4" sm="12" cols="12" align="center"></v-col> -->
    </v-col>
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
        // background: (this.isModelActive) ? '#19487f' : '#606060',
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
      // this.activateEvaluator();
      this.activateModel();
    },
  },
};
</script>

<style scoped>
.btn-activate::before {
  display: none;
}
.btn-activate:hover,
.btn-activate {
  box-shadow: none;
  background: none !important;
}
.subtitle-1 {
  text-transform: none;
}
</style>
