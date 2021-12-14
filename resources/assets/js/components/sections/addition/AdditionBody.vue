<template>
  <div>
    <template v-if="isAdditionActive">
      <v-container fluid>
        <v-row>
            <v-col md="6" sm="6" cols="12">
              <v-radio-group v-model="additionalFilesType">
                <v-radio
                  label="Загрузить файл"
                  value="file"
                ></v-radio>
              </v-radio-group>
              <v-file-input
                v-model="fileData"
                counter
                dense
                label="Файл"
                placeholder="Выберите файл"
                prepend-icon="mdi-paperclip"
                outlined
                :show-size="1000"
                :disabled="additionalFilesType != 'file'"
              >
                <template v-slot:selection="{ index, text }">
                  <v-chip
                    dark
                    label
                    small
                  >
                    {{ text }}
                  </v-chip>
                </template>
              </v-file-input>
            </v-col>
            <v-col md="6" sm="6" cols="12">
              <v-radio-group v-model="additionalFilesType">
                <v-radio
                  label="Ссылка на файл"
                  value="link"
                ></v-radio>
              </v-radio-group>
              <v-text-field
                outlined
                dense
                :disabled="additionalFilesType != 'link'"
                v-model="linkUrl"></v-text-field>
            </v-col>
        </v-row>
      </v-container>
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
  name: 'AdditionBody',
  components: {},
  data() {
    return {};
  },
  computed: {
    ...mapGetters({
      isAdditionActive: 'main/isAdditionActive',
      getterAdditionalFilesType: 'print/additionalFilesType',
      getterLinkUrl: 'print/linkUrl',
      getterFileData: 'print/fileData',
    }),
    additionalFilesType: {
      get() {
        return this.getterAdditionalFilesType;
      },
      set(val) {
        this.mutatorAdditionalFilesType(val);
      },
    },
    linkUrl: {
      get() {
        return this.getterLinkUrl;
      },
      set(val) {
        this.mutatorLinkUrl(val);
      },
    },
    fileData: {
      get() {
        return this.getterFileData;
      },
      set(val) {
        this.mutatorFileData(val);
      },
    },
  },
  methods: {
    ...mapMutations({
      mutatorAdditionalFilesType: 'print/setAdditionalFilesType',
      mutatorLinkUrl: 'print/setLinkUrl',
      mutatorFileData: 'print/setFileData',
    }),
    ...mapActions({}),
  },
};
</script>

<style scoped>

</style>
