module.exports = {
  transpileDependencies: ['vuetify'],
  chainWebpack: config => {
    config.plugin('html').tap(args => {
      args[0].title = 'STORJ | Storage Node';
      return args;
    });
  },
  devServer: {
    proxy: {
      '/api.php': {
        target: 'http://localhost:8000/api.php',
        ws: true,
        changeOrigin: true
      }
    }
  },
  publicPath: process.env.BASE_URL
};
