var axios = require('axios');
var express = require('express')
var app = express()

var scrape = function (url, id_product, webResponse) {
    axios.get(url)
        .then(function (response) {
            let body = response.data;
            console.log(body.data.reviews);

            // var json = JSON.stringify(body.data);
            // var fs = require('fs');
            // fs.writeFile(id_product+'.json', json, 'utf8');

            webResponse.json(body.data);
        })
        .catch(function (err) {
            let message = "Ada error nih --> " + err.message;

            console.log(message);
            webResponse.json(message);
        });
};

app.get('/crawl/:id_product/:page/:item', function(request, response) {
    var item = request.params.item;
    var id_product = request.params.id_product;
    var page = request.params.page;
    link = "https://www.blibli.com/backend/product/products/"+id_product+"/reviews?page="+page+"&itemPerPage="+item

    scrape(link, id_product, response);
});

app.listen(8080, function () {
    console.log("Listening on port 8080");
});