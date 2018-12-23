var page = require('webpage').create();
page.open('http://www.blibli.com/backend/product/products/pr--PRI-014226-00/reviews?page=1', function(status) {
  console.log("Status: " + status);
  phantom.exit();
});