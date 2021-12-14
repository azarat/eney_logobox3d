module.exports = {
  outputDir: '../public/dist',
  transpileDependencies: [
    'vuetify',
  ],
  filenameHashing: false,
  pluginOptions: {
    i18n: {
      locale: 'ru',
      fallbackLocale: 'ru',
      localeDir: 'locales',
      enableInSFC: false,
    },
  },
};
