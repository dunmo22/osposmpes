var assert = require('assert');

var ospos = function() {

    var server = "http://localhost";

    return {

        url : function(suffix) {
            return server + suffix;
        }
        ,
    login : function(browser, done) {
        return browser.get(this.url("/index.php"))
        .elementByName('username').type("admin").getValue()
        .then(function (value) {
            assert.equal(value, "admin");
        })
        .elementByName('password').type("pointofsale").getValue()
        .then(function (value) {
            assert.ok(value, "pointofsale");
        })
        .elementByName('loginButton').click()
        .elementById('home_module_list').then(function (value) {
            assert.ok(value, "Login failed!!")
        })
        .then(done, done);

    },

    create_item : function(browser, item)
    {
        return browser.get(this.url("/index.php/items")).elementByCssSelector("a[title='New Item']", 5000).click()
        .waitForElementByName("name", 10000).type(item.name).elementById("category").type(item.category)
        .elementById('cost_price', 2000).clear().type(item.cost_price).elementById("unit_price", 2000).type(item.unit_price)
        .elementById('tax_name_1', 2000).type('VAT').elementById("tax_percent_name_1", 2000).type("21")
        .elementById('receiving_quantity', 2000).type(item.receiving_quantity || 1)
        .elementById("1_quantity", 2000).type("1").elementById("reorder_level", 2000).type("0").elementById("submit", 2000).click()
        .waitForElementByXPath("//table/tbody/tr[td/text()='anItem']/td[3]").text().then(function (value) {
            assert.equal(value, "anItem", "item " + item.name + " could not be created!!");
        });
    }

    }
};

module.exports = ospos();
