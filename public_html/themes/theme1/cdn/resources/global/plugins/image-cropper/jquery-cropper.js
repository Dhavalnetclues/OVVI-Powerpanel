/*!
 * jQuery Cropper v1.0.1
 * https://fengyuanchen.github.io/jquery-cropper
 *
 * Copyright 2018-present Chen Fengyuan
 * Released under the MIT license
 *
 * Date: 2019-10-19T08:48:33.062Z
 */

(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? factory(require('jquery'), require('cropperjs')) :
    typeof define === 'function' && define.amd ? define(['jquery', 'cropperjs'], factory) :
      (global = global || self, factory(global.jQuery, global.Cropper));
}(this, function ($, Cropper) {
  'use strict';

  $ = $ && $.hasOwnProperty('default') ? $['default'] : $;
  Cropper = Cropper && Cropper.hasOwnProperty('default') ? Cropper['default'] : Cropper;

  if ($ && $.fn && Cropper) {
    var AnotherCropper = $.fn.cropper;
    var NAMESPACE = 'cropper';

    $.fn.cropper = function jQueryCropper(option) {
      for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
        args[_key - 1] = arguments[_key];
      }

      var result;
      this.each(function (i, element) {
        var $element = $(element);
        var isDestroy = option === 'destroy';
        var cropper = $element.data(NAMESPACE);

        if (!cropper) {
          if (isDestroy) {
            return;
          }

          var options = $.extend({}, $element.data(), $.isPlainObject(option) && option);
          cropper = new Cropper(element, options);
          $element.data(NAMESPACE, cropper);
        }

        if (typeof option === 'string') {
          var fn = cropper[option];

          if ($.isFunction(fn)) {
            result = fn.apply(cropper, args);

            if (result === cropper) {
              result = undefined;
            }

            if (isDestroy) {
              $element.removeData(NAMESPACE);
            }
          }
        }
      });
      return result !== undefined ? result : this;
    };

    $.fn.cropper.Constructor = Cropper;
    $.fn.cropper.setDefaults = Cropper.setDefaults;

    $.fn.cropper.noConflict = function noConflict() {
      $.fn.cropper = AnotherCropper;
      return this;
    };
  }

}));


$(function () {
  'use strict';

  var console = window.console || { log: function () { } };
  var URL = window.URL || window.webkitURL;
  var $image = $('#image');
  var $dataX = $('#dataX');
  var $dataY = $('#dataY');
  var $dataHeight = $('#dataHeight');
  var $dataWidth = $('#dataWidth');
  var $dataRotate = $('#dataRotate');
  var $dataScaleX = $('#dataScaleX');
  var $dataScaleY = $('#dataScaleY');
  var options = {
    aspectRatio: 1 / 1,
    preview: '.img-preview',
    crop: function (e) {
      $dataX.val(Math.round(e.detail.x));
      $dataY.val(Math.round(e.detail.y));
      $dataHeight.val(Math.round(e.detail.height));
      $dataWidth.val(Math.round(e.detail.width));
      $dataRotate.val(e.detail.rotate);
      $dataScaleX.val(e.detail.scaleX);
      $dataScaleY.val(e.detail.scaleY);
    }
  };
  var originalImageURL = $image.attr('src');

  var uploadedImageType = $image.data('extension');
  var uploadedImageURL;

  // Tooltip
  //$('[data-bs-toggle="tooltip"]').tooltip();

  // Cropper
  $image.on({
    ready: function (e) {
      //console.log(e.type);
    },
    cropstart: function (e) {
      //console.log(e.type, e.detail.action);
    },
    cropmove: function (e) {
      //console.log(e.type, e.detail.action);
    },
    cropend: function (e) {
      //console.log(e.type, e.detail.action);
    },
    crop: function (e) {
      //console.log(e.type);
    },
    zoom: function (e) {
      //console.log(e.type, e.detail.ratio);
    }
  }).cropper(options);

  // Buttons
  if (!$.isFunction(document.createElement('canvas').getContext)) {
    $('button[data-method="getCroppedCanvas"]').prop('disabled', true);
  }

  if (typeof document.createElement('cropper').style.transition === 'undefined') {
    $('button[data-method="rotate"]').prop('disabled', true);
    $('button[data-method="scale"]').prop('disabled', true);
  }

  // Download
  // if (typeof $download[0].download === 'undefined') {
  //   $download.addClass('disabled');
  // }

  // Options
  $('.docs-toggles').on('change', 'input', function () 
  {
    var $this = $(this);
    var name = $this.attr('name');
    var type = $this.prop('type');
    var cropBoxData;
    var canvasData;

    if (!$image.data('cropper')) {
      return;
    }

    if (type === 'checkbox') {
      options[name] = $this.prop('checked');
      cropBoxData = $image.cropper('getCropBoxData');
      canvasData = $image.cropper('getCanvasData');

      options.ready = function () {
        $image.cropper('setCropBoxData', cropBoxData);
        $image.cropper('setCanvasData', canvasData);
      };
    } else if (type === 'radio') {
      options[name] = $this.val();
    }

    $image.cropper('destroy').cropper(options);

  });

  // Methods
  $('.docs-buttons').on('click', '[data-method]', function () {
    var $this = $(this);
    var data = $this.data();
    var cropper = $image.data('cropper');
    var cropped;
    var $target;
    var result;

    if ($this.prop('disabled') || $this.hasClass('disabled')) {
      return;
    }

    if (cropper && data.method) {
      data = $.extend({}, data); // Clone a new one

      if (typeof data.target !== 'undefined') {
        $target = $(data.target);

        if (typeof data.option === 'undefined') {
          try {
            data.option = JSON.parse($target.val());
          } catch (e) {
            console.log(e.message);
          }
        }
      }

      cropped = cropper.cropped;

      switch (data.method) {
        case 'rotate':
          if (cropped && options.viewMode > 0) {
            $image.cropper('clear');
          }

          break;

        case 'getCroppedCanvas':

          if (uploadedImageType === 'jpeg' || uploadedImageType === "jpg") {
            if (!data.option) {
              data.option = {};
            }
            data.option.fillColor = '#fff';
          }
          break;
      }

      result = $image.cropper(data.method, data.option, data.secondOption);

      switch (data.method) {
        case 'rotate':
          if (cropped && options.viewMode > 0) {
            $image.cropper('crop');
          }

          break;

        case 'scaleX':
        case 'scaleY':
          $(this).data('option', -data.option);
          break;

        case 'getCroppedCanvas':
          if (result) {
            // Bootstrap's Modal
            $('#getCroppedCanvasModal').modal().find('.cropped_image').html('<img src=' + result.toDataURL(uploadedImageType) + ' />');

            //download.download = uploadedImageName;
            //$download.attr('href', result.toDataURL(uploadedImageType));

            $('#save_as_new').on('click', function () {
              var image_id = $(this).data('id');
               var image_folderid = $(this).data('folderid');
              var image = result.toDataURL(uploadedImageType);
              MediaManager.saveCroppedImage(image, image_id, false,image_folderid);

            });

            $('#save_and_overwrite').on('click', function () {
              var image_id = $(this).data('id');
               var image_folderid = $(this).data('folderid');
              var image = result.toDataURL(uploadedImageType);
              MediaManager.saveCroppedImage(image, image_id, true,image_folderid);
            });
          }

          break;

        case 'destroy':
          if (uploadedImageURL) {
            URL.revokeObjectURL(uploadedImageURL);
            uploadedImageURL = '';
            $image.attr('src', originalImageURL);
          }
          break;
      }


      if ($.isPlainObject(result) && $target) {
        try {
          $target.val(JSON.stringify(result));

        } catch (e) {
          console.log(e.message);
        }
      }

    }
  });

  // Keyboard
  $(document.body).on('keydown', function (e) {
    if (e.target !== this || !$image.data('cropper') || this.scrollTop > 300) {
      return;
    }

    switch (e.which) {
      case 37:
        e.preventDefault();
        $image.cropper('move', -1, 0);
        break;

      case 38:
        e.preventDefault();
        $image.cropper('move', 0, -1);
        break;

      case 39:
        e.preventDefault();
        $image.cropper('move', 1, 0);
        break;

      case 40:
        e.preventDefault();
        $image.cropper('move', 0, 1);
        break;
    }
  });


});

