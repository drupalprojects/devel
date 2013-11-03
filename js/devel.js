(function ($) {

"use strict";

// Explain link in query log.
Drupal.behaviors.devel_explain = {
  attach: function() {
    $('a.dev-explain').click(function () {
      var qid = $(this).attr("qid");
      var cell = $('#devel-query-' + qid);
      $('.dev-explain', cell).load(drupalSettings.basePath + 'devel/explain/' + drupalSettings.devel.request_id + '/' + qid).show();
      $('.dev-placeholders', cell).hide();
      $('.dev-arguments', cell).hide();
      return false;
    });
  }
}

// Arguments link in query log.
Drupal.behaviors.devel_arguments = {
  attach: function() {
    $('a.dev-arguments').click(function () {
      var qid = $(this).attr("qid");
      var cell = $('#devel-query-' + qid);
      $('.dev-arguments', cell).load(drupalSettings.basePath + 'devel/arguments/' + drupalSettings.devel.request_id + '/' + qid).show();
      $('.dev-placeholders', cell).hide();
      $('.dev-explain', cell).hide();
      return false;
    });
  }
}

// Placeholders link in query log.
Drupal.behaviors.devel_placeholders = {
  attach: function() {
    $('a.dev-placeholders').click(function () {
      var qid = $(this).attr("qid");
      var cell = $('#devel-query-' + qid);
      $('.dev-explain', cell).hide();
      $('.dev-arguments', cell).hide();
      $('.dev-placeholders', cell).show();
      return false;
    });
  }
}

})(jQuery);
