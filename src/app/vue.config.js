module.exports = {
  transpileDependencies: ["vuetify"],
  devServer: {
    proxy: {
      "/api.php": {
        target: "http://localhost:8000/api.php",
        ws: true,
        changeOrigin: true,
      },
    },
  },
};
