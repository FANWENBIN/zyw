/**
 * Created by admin on 2015/9/23.
 */

define(function (require, exports, module) {
  var nd = require("unit/nd.base");
  require("unit/jq.$touch");
  require("unit/jq.$pageTo");

  $(function () {
    var scope = {};
    var page = {
      init: function () {
        $(".back").touchend(function () {
          window.history.go(-1);
        });

        $(".menu").touchend(function () {
          $(".popUpWin").show()
        })

      }
    }

    page.init()

  })
});