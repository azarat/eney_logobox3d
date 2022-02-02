<template>
  <div class="overlay-item mb-8">
    <v-col cols="3" class="header py-4">
        <div class="d-flex align-center">
          <v-chip color="primary" label class="subtitle-1 font-weight-bold overlay-item--label">
            Нанесення #{{index+1}}
          </v-chip>
        </div>
        <v-col cols="12 mt-7">
          <div class="">
            <div class="text-uppercase pr-4">{{$t('quantity')}}:</div>
            <v-text-field class="d-flex c-input"
                          dense
                          background-color="accent"
                          outlined
                          v-model="quantity"></v-text-field>
          </div>
        </v-col>
        <v-col cols="12">
          <div class="height-100ps">
            <div class="text-uppercase pr-4">{{$t('price_without_sale')}}:</div>
            <v-text-field class="d-flex c-input"
                          dense
                          background-color="accent"
                          outlined
                          disabled
                          v-model="totalPrice"></v-text-field>
          </div>
        </v-col>
        <v-col cols="12">
          <div class="height-100ps">
            <div class="text-uppercase pr-4">{{$t('price_with_sale')}}</div>
            <v-text-field class="d-flex c-input"
                          dense
                          background-color="accent"
                          outlined
                          disabled
                          v-model="totalPriceWithDiscount"></v-text-field>
          </div>
        </v-col>
    </v-col>
    <v-col cols="9" class="content evaluator">
      <v-col cols="12">
        <div class="text-uppercase">{{ $t('technology') }}:</div>
        <div class="d-flex align-center">
          <v-chip-group
            v-model="selectedTypeIndex"
            column
            mandatory
            class="v-chip-medium-tpl"
          >
            <v-chip
              v-for="(type, index) in types"
              label
              :key="index"
              class="px-0"
            >
              <v-img
                :src="type.image"
                max-height="40"
                contain
                class="mr-3"
              >
              </v-img>
              {{ getName(type) }}
            </v-chip>
          </v-chip-group>
        </div>
      </v-col>
      <v-col cols="12">
        <div class="text-uppercase">ТИП:</div>
        <div class="d-flex align-center">
          <v-chip-group
            v-model="selectedTypeTechIndex"
            column
            mandatory
            class="v-chip-medium-tpl"
          >
            <v-chip
              v-for="(type, index) in filteredTypesTech"
              :key="index"
              label
              outlined
              class="justify-center"
            >
              {{ getName(type) }}
            </v-chip>
          </v-chip-group>
        </div>
      </v-col>
      <v-col cols="12">
        <div class="text-uppercase">{{ $t('area') }}:</div>
        <div class="d-flex align-center">
          <v-chip-group
            v-model="selectedAreaIndex"
            column
            mandatory
            class="v-chip-medium-tpl"
          >
            <v-chip
              v-for="(area, index) in filteredAreas"
              :key="index"
              label
              outlined
              class="justify-center"
            >
              {{ getName(area) }}
            </v-chip>
          </v-chip-group>
        </div>
      </v-col>
      <v-col cols="12">
        <div class="text-uppercase">{{ $t('color') }}:</div>
        <div class="d-flex align-center">
          <v-chip-group
            v-model="selectedColorsIndex"
            column
            mandatory
          >
            <v-chip
              v-for="(color, index) in filteredColors"
              :key="index"
              outlined
              label
              class="v-chip-squared-tpl justify-center"
            >
              {{color}}
            </v-chip>
          </v-chip-group>
        </div>
      </v-col>
    </v-col>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';

export default {
  name: 'OverlayItem',
  props: ['index'],
  data() {
    return {
      selectedTypeTechIndex: 0,
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
      typesTech: 'print/typesTech',
      types: 'print/types',
      quantityOfItems: 'print/quantityOfItems',
      printsCost: 'print/printsCost',
      printsCostDiscount: 'print/printsCostDiscount',
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
    filteredTypesTech() {
      const codeOfSelectedType = this.types[this.selectedTypeIndex].code;
      const result = this.typesTech.filter(item => item.code.indexOf(codeOfSelectedType) !== -1);
      // eslint-disable-next-line vue/no-side-effects-in-computed-properties
      this.selectedTypeTechIndex = 0;
      return result;
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

      const totalPriceVal = parseFloat(onePrinting * tirag).toFixed(2);

      const printsCostArray = JSON.parse(JSON.stringify(this.printsCost));

      const printsCostArraySumBefore = Object.values(printsCostArray)
        .reduce((total, price) => parseFloat(total) + parseFloat(price));

      printsCostArray[this.index] = totalPriceVal;

      const printsCostArraySumAfter = Object.values(printsCostArray)
        .reduce((total, price) => parseFloat(total) + parseFloat(price));

      if (this.printsCost.length !== printsCostArray.length
        || printsCostArraySumBefore !== printsCostArraySumAfter) {
        this.setPrintsCost(printsCostArray);
      }

      return totalPriceVal;
    },
    totalPriceWithDiscount() {
      const discountSum = parseFloat(this.discount) * this.totalPrice / 100;

      const totalPriceDiscountVal = parseFloat(this.totalPrice - discountSum).toFixed(2);

      const printsCostDiscountArray = JSON.parse(JSON.stringify(this.printsCostDiscount));

      const printsCostDiscountArraySumBefore = Object.values(printsCostDiscountArray)
        .reduce((total, price) => parseFloat(total) + parseFloat(price));

      printsCostDiscountArray[this.index] = totalPriceDiscountVal;

      const printsCostDiscountArraySumAfter = Object.values(printsCostDiscountArray)
        .reduce((total, price) => parseFloat(total) + parseFloat(price));

      if (this.printsCostDiscount.length !== printsCostDiscountArray.length
        || printsCostDiscountArraySumBefore !== printsCostDiscountArraySumAfter) {
        this.setPrintsCostDiscount(printsCostDiscountArray);
      }

      return totalPriceDiscountVal;
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
    this.setTypeTechOfPrint({
      index: this.index,
      value: this.typesTech[0],
    });
  },
  methods: {
    ...mapActions({

    }),
    ...mapMutations({
      setter: 'print/setter',
      setTypeOfPrint: 'print/setTypeOfPrint',
      setAreaOfPrint: 'print/setAreaOfPrint',
      setTypeTechOfPrint: 'print/setTypeTechOfPrint',
      setColorsOfPrint: 'print/setColorsOfPrint',
      setPrintsCost: 'print/setPrintsCost',
      setPrintsCostDiscount: 'print/setPrintsCostDiscount',
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
    selectedTypeTechIndex(indexTypeTech) {
      this.setTypeTechOfPrint({
        index: this.index,
        value: this.filteredTypesTech[indexTypeTech],
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
  border-right: 1px solid #D2D6D9;
}
.overlay-item {
  display: flex;
  border: 1px solid #D2D6D9;
  border-radius: 4px;
}
.v-chip-medium-tpl .v-chip--label {
  width: 200px;
  height: 46px;
  font-size: 15px;
  font-weight: 500;
}
.overlay-item--label {
  border-top-left-radius: 0 !important;
  border-bottom-left-radius: 0 !important;
  margin-left: -12px;
}
.v-chip.v-chip--outlined.v-chip.v-chip--active,
.v-chip--active,
.v-slide-item--active {
  background: #006EC7 !important;
  color: #fff !important;
}
.text-center {
  text-align: center;
}
.v-chip-squared-tpl {
  height: 46px !important;
  width: 46px;
}
</style>
