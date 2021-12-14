<template>
  <div>
    <div v-if="centred">
      <select class="centred" v-model="selectedOption"
              v-if="optionName == undefined"
              @change="change">
        <option class="option" v-for="option in options"
                :value="option">
          {{ option }}
        </option>
      </select>
      <select class="centred" v-model="selectedOption"
              v-else
              @change="change">
        <option class="option" v-for="option in options"
                :value="option">
          {{ option[optionName] }}
        </option>
      </select>
    </div>
    <div v-else>
      <select v-model="selectedOption"
              v-if="optionName == undefined"
              @change="change">
        <option v-for="option in options"
                :value="option">
          {{ option }}
        </option>
      </select>
      <select v-model="selectedOption"
              v-else
              @change="change">
        <option v-for="option in options"
                :value="option">
          {{ option[optionName] }}
        </option>
      </select>
    </div>
  </div>

</template>

<script>
  import lodash from 'lodash';

  export default {
    props: ['options', 'optionName', 'selected', 'centred'],
    data: function () {
      return {
        selectedOption: {}
      }
    },
    computed: {
      isCentred(){
        if(this.centred !== undefined){
          return this.centred
        }else{
          return false;
        }
      }
    },
    mounted () {
      this.selectedOption = this.selected;
    },
    watch: {
      selected: function () {
        this.selectedOption = this.selected;
      }
    },
    methods: {
      change (e) {
        this.$emit('change', this.selectedOption);
      }
    }
  };
</script>

<style lang="scss" type="text/scss">
  select {
    background: transparent url('svg/icon_arrow.png') no-repeat calc(100% - 10px) 50%;
    border: 1px solid #ebebeb;
    padding: 6px;
    transform: translateY(-9px);
    min-width: 72px;
    -webkit-appearance: none;
    -moz-appearance: none;
    /*text-indent: 1px;*/
    text-overflow: '';
    position: relative;
    text-indent: 0;
    &.centred{
      padding: 6px 26px;
    }
  }
</style>
