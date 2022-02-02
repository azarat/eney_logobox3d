import Vue from 'vue';
import Vuetify from 'vuetify/lib';
// eslint-disable-next-line import/no-unresolved
// import '@mdi/font/css/materialdesignicons.css';

Vue.use(Vuetify);

export default new Vuetify({
  icons: {
    iconfont: 'mdi',
  },
  theme: {
    themes: {
      light: {
        primary: '#006EC7',
        secondary: '#606060',
        accent: '#ffffff',
      },
    },
  },
});
