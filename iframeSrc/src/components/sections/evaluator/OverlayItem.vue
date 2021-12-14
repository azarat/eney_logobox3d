<template>
  <div>
    <div class="header px-3">
      <v-row dense>
        <v-col cols="12" md="4">
          <v-row dense>
            <v-col cols="2" md="1">
              <v-chip color="primary" label class="subtitle-1 font-weight-bold">
                {{index+1}}
              </v-chip>
            </v-col>
            <v-col cols="10" md="10">
              <div class="d-flex pl-2 align-center">
                <span class="text-uppercase px-4 font-weight-medium">{{$t('quantity')}}</span>
                <v-text-field class="d-flex c-input"
                              dense
                              background-color="accent"
                              outlined
                              v-model="quantity"></v-text-field>
              </div>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" md="4">
          <div class="d-flex pl-2 align-center justify-center height-100ps">
            <span class="text-uppercase px-4 font-weight-medium">{{$t('price_without_sale')}}</span>
            <v-text-field class="d-flex c-input"
                          dense
                          background-color="accent"
                          outlined
                          disabled
                          v-model="totalPrice"></v-text-field>
          </div>
        </v-col>
        <v-col cols="12" md="4">
          <div class="d-flex pl-2 align-center justify-center height-100ps">
            <span class="text-uppercase px-4 font-weight-medium">{{$t('price_with_sale')}}</span>
            <v-text-field class="d-flex c-input"
                          dense
                          background-color="accent"
                          outlined
                          disabled
                          v-model="totalPriceWithDiscount"></v-text-field>
          </div>
        </v-col>
      </v-row>
    </div>
    <div class="content evaluator py-2">
      <div class="d-flex pl-2 align-center justify-center">
        <span class="text-uppercase pr-5 font-weight-medium">{{ $t('technology') }}</span>
        <v-chip-group
          v-model="selectedTypeIndex"
          column
          mandatory
        >
          <v-chip
            v-for="(type, index) in types"
            label
            large
            :key="index"
            class="px-1"
          >
            <v-img
              :src="type.image"
              max-width="50"
              max-height="50"
            >
            </v-img>
            {{ getName(type) }}
          </v-chip>
        </v-chip-group>
      </div>
      <div class="d-flex pl-2 align-center justify-center">
        <span class="text-uppercase pr-5 font-weight-medium">{{ $t('area') }}</span>
        <v-chip-group
          v-model="selectedAreaIndex"
          column
          mandatory
        >
          <v-chip
            v-for="(area, index) in filteredAreas"
            :key="index"
            label
          >
            <span class="font-weight-bold">{{ getName(area) }}</span>
          </v-chip>
        </v-chip-group>
      </div>
      <div class="d-flex pl-2 align-center justify-center">
        <span class="text-uppercase pr-5 font-weight-medium">{{ $t('color') }}</span>
        <v-chip-group
          v-model="selectedColorsIndex"
          column
          mandatory
        >
          <v-chip
            v-for="(color, index) in filteredColors"
            :key="index"
            label
          >
            {{color}}
          </v-chip>
        </v-chip-group>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';

export default {
  name: 'OverlayItem',
  props: ['index'],
  data() {
    return {
      selectedTypeIndex: 0,
      selectedAreaIndex: 0,
      selectedColorsIndex: 0,
    };
  },
  computed: {
    ...mapGetters({
      discount: 'main/discount',
      lang: 'main/lang',
      areas: 'print/areas',
      types: 'print/types',
      quantityOfItems: 'print/quantityOfItems',
    }),
    quantity: {
      get() {
        return this.quantityOfItems;
      },
      set(value) {
        this.setter({
          prop: 'quantityOfItems',
          value,
        });
      },
    },
    filteredAreas() {
      const codeOfSelectedType = this.types[this.selectedTypeIndex].code;
      const result = this.areas.filter(item => item.code.indexOf(codeOfSelectedType) !== -1);
      // eslint-disable-next-line vue/no-side-effects-in-computed-properties
      this.selectedAreaIndex = 0;
      return result;
    },
    filteredColors() {
      const colors = [];
      const maxColors = this.filteredAreas[this.selectedAreaIndex].max_colors;
      for (let i = 0; i < maxColors; i += 1) {
        colors.push((i + 1));
      }
      if (colors.length < this.selectedColorsIndex) {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.selectedColorsIndex = 0;
      }

      return colors;
    },
    totalPrice() {
      const selectedArea = this.filteredAreas[this.selectedAreaIndex];
      const preparePrice = parseFloat(selectedArea.prepare_price);
      const kx = parseFloat(selectedArea.kx);
      // eslint-disable-next-line radix
      const n = parseInt(this.selectedColorsIndex + 1);
      // eslint-disable-next-line radix
      const tirag = parseInt(this.quantity);
      const printPrice = parseFloat(selectedArea.print_price);
      const kz = parseFloat(selectedArea.kz);
      const stickingPrice = parseFloat(selectedArea.sticking_price);
      const roasingPrice = parseFloat(selectedArea.roasting_price);

      const onePrinting = (preparePrice * (1 + kx * (n - 1)) / tirag
        + printPrice * (1 + kz * (n - 1)) + stickingPrice + roasingPrice).toFixed(2);
      return parseFloat(onePrinting * tirag).toFixed(2);
    },
    totalPriceWithDiscount() {
      const discountSum = parseFloat(this.discount) * this.totalPrice / 100;
      return parseFloat(this.totalPrice - discountSum).toFixed(2);
    },
  },
  mounted() {
    this.setTypeOfPrint({
      index: this.index,
      value: this.types[0],
    });
    this.setAreaOfPrint({
      index: this.index,
      value: this.areas[0],
    });
  },
  methods: {
    ...mapActions({

    }),
    ...mapMutations({
      setter: 'print/setter',
      setTypeOfPrint: 'print/setTypeOfPrint',
      setAreaOfPrint: 'print/setAreaOfPrint',
      setColorsOfPrint: 'print/setColorsOfPrint',
    }),
    getName(item) {
      let currentLang = this.lang;
      if (currentLang === 'uk') {
        currentLang = 'ua';
      }
      return item.localization[currentLang].name;
    },
  },
  watch: {
    selectedTypeIndex(indexType, oldIndexType) {
      if (indexType !== oldIndexType) {
        this.setTypeOfPrint({
          index: this.index,
          value: this.types[indexType],
        });
      }
    },
    selectedAreaIndex(indexArea) {
      this.setAreaOfPrint({
        index: this.index,
        value: this.filteredAreas[indexArea],
      });
    },
    selectedColorsIndex(indexColor) {
      this.setColorsOfPrint({
        index: this.index,
        value: indexColor + 1,
      });
    },
  },
};
</script>

<style scoped>
.header {
  background: #eee;
}
.v-slide-item--active {
  background: rgb(25, 72, 127);
  color: #fff !important;
}
</style>
