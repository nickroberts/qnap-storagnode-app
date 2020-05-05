const fs = require('fs-extra');
const { resolve } = require('path');

(async () => {
  try {
    await fs.emptyDir(resolve(__dirname, '../../../shared/web.new/css'));
    await fs.emptyDir(resolve(__dirname, '../../../shared/web.new/img'));
    await fs.emptyDir(resolve(__dirname, '../../../shared/web.new/js'));
    await fs.copy(resolve(__dirname, '../dist/'), resolve(__dirname, '../../../shared/web.new/'));
  } catch (e) {
    console.error(e);
  }
})();
