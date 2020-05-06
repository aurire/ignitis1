(function($) {
      var fieldsToHide = '.field--name-' + drupalSettings.custom_drop.fieldtohide;
      $(fieldsToHide).hide();
      $(fieldsToHide).each(function() {
        var par = $(this).parent();
        var municip = par.find('.field--type-custom-drop-sch');
        municip.each(function() {
          var mun = $(this).find('select');
          var munPar = $(this).parent();
          var munParToHide = munPar.find(fieldsToHide);
          mun.off('change');
          mun.change(function() {
            if (!mun.val()) {
              munParToHide.hide();
            } else {
              munParToHide.show();
            }
          });
        });
      });
})(jQuery);
