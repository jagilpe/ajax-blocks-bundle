/*
 * This file is part of AjaxBlocksBundle package.
 *
 * (c) Copyright Javier Gil Pereda <javier@gilpereda.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * jQuery plugin to reload the Ajax Blocks
 */
;
(function ($, document, window, undefined) {

  var pluginName = 'jgpAjaxBlock';
  var dataKey = 'plugin_jgpAjaxBlock';

  var Plugin = function (element, options) {
    this.element = element;
    this.options = {
      autoload: false,
      onReload: function() {}
    };

    this.init(options);
  };

  Plugin.prototype = {
    init: function (options) {
      $.extend(this.options, options);
      var $element = $(this.element);

      this.dataSrc = $element.attr('data-src');

      // Autoload the block if applies
      if(this.options.autoload) {
        this.reloadBlock();
      }
    },

    reloadBlock : function() {
      var $element = $(this.element);
      var plugin = this;

      $.ajax({
        url: this.dataSrc,
        context: this,
        method: 'GET'
      })
        .done(function(data) {
          if (typeof data !== 'undefined' && typeof data.block !== 'undefined') {
            var newBlock = data.block;

            $element.empty();
            $element.append(newBlock);

            plugin.options.onReload(plugin.element);
          }
        });
    }
  };

  $.fn[pluginName] = function (options) {
    var args = arguments;

    if (typeof options === 'undefined' || typeof options === 'object') {
      return this.each(function () {
        if (!$.data(this, dataKey)) {
          $.data(this, dataKey, new Plugin(this, options));
        }
      })
    } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
      if (Array.prototype.slice.call(args, 1).length == 0 &&
        $.inArray(options, $.fn[pluginName].getters) != -1) {
        var instance = $.data(this[0], dataKey);
        return instance[options].apply(instance, Array.prototype.slice.call(args, 1));
      } else {
        return this.each(function () {
          var instance = $.data(this, dataKey);
          if (instance instanceof Plugin && typeof instance[options] === 'function') {
            instance[options].apply(instance, Array.prototype.slice.call(args, 1));
          }
        });
      }
    }
  };

  // Load the plugin for the ajax blocks in the document
  $(document).ready(function() {
    $('[data-target="jgp-ajax-block"][data-autoload]').jgpAjaxBlock();
  });

})(jQuery, document, window);
