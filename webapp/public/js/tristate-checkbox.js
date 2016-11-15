;(function($, window, document, undefined) {
  "use strict";
  var pluginName = "triStateCheckbox";
  var defaults = {
    label: ".indicator"
  };

  function Plugin(element, options) {
    this.element = element;
    this.settings = $.extend({}, defaults, options);
    this._defaults = defaults;
    this._name = pluginName;
    this.element.indeterminate = true;
    this.init();
  }
  $.extend(Plugin.prototype, {
    init: function() {
      var thisInstance = this;
      if(!$(this.element).prop('disabled')){
        $(".state").on("click", function() {
          thisInstance.toggleCheckboxState($(this).data("state"));
        });
      }
    },
    getCheckboxState: function() {
      var state;
      var $element = $(this.element);
      if ($element.prop("indeterminate") === true) {
        state = "neutral";
      } else {
        if ($element.prop("checked") === true) {
          state = "on";
        } else if ($element.prop("checked") === false) {
          state = "off";
        }
      }
      return state;
    },
    numbersToState: function(number) {
      var state = {
        0: "off",
        1: "on",
        "-": "neutral"
      };
      return state[number];
    },
    toggleCheckboxState: function(state) {
      this.setCheckboxState(state);
    },
    setChangeHook: function(evt, fn) {
      var $target = $('.tristate-switch').find("span.state");
      if (typeof evt === "undefined" || typeof fn !== "function") {
        return;
      }
      $target.bind(evt, fn);
    },
    setCheckboxState: function(state) {
      var thisInstance = this;
      var $element = $(this.element);
      var previousState = this.getCheckboxState();
      if (typeof state === "number" || typeof state === "undefined") {
        if (typeof state === "undefined") {
          state = "-";
        }
        state = thisInstance.numbersToState(state);
      }
      if (state === previousState) {
        return;
      } else {
        if (state === "on") {
          $element.prop("indeterminate", false);
          $element.prop("checked", true);
        } else if (state === "off") {
          $element.prop("indeterminate", false);
          $element.prop("checked", false);
        } else if (state === "neutral") {
          $element.prop("checked", false);
          $element.prop("indeterminate", true);
        }
      }
    }
  });
  $.fn[pluginName] = function(options) {
    return this.each(function() {
      if (!$.data(this, "plugin_" + pluginName)) {
        $.data(this, "plugin_" + pluginName, new Plugin(this, options));
      }
    });
  };
})(jQuery, window, document);