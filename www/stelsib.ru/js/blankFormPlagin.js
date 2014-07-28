/**
 * Created by Valentina on 01.04.14.
 */


+function($){
    'use strict';
    var Blank = function (element, options) {
        this.options   = options;
        this.$element  = $(element);

    }

    Blank.DEFAULTS = {
        backdrop: true,
        keyboard: true,
        show: true,
        h:$(document).height(),
        width:$(window).width()
    }
    Blank.prototype.show = function () {
        var that = this;
        var overlay;
        $('<link rel="stylesheet" href="/css/order.css" type="text/css" />').appendTo('head');
        that.backdrop();

        $("<div>").attr("id","block-blank").insertAfter(that.$element);

        overlay = $('.blank-overlay');
        overlay.on('click', function(){
            that.hide(this);
        });


    }

    Blank.prototype.backdrop = function () {

        $("<div>").attr("class","blank-overlay").css({height:this.options.h + "px", width:this.options.width + "px"}).insertAfter(this.$element);


    }
    Blank.prototype.hide = function (elem) {
        elem.remove();
    }

    // MODAL PLUGIN DEFINITION
    // =======================

    var old = $.fn.blankform

    $.fn.blankform = function (option) {
        return this.each(function () {
            var $this   = $(this)
            var data = null;
            var options = $.extend({}, Blank.DEFAULTS, $this.data(), typeof option == 'object' && option)

            if (!data){
                $this.data('modal', (data = new Blank(this, options)))
            }
            if (typeof option == 'string'){
                data[option]();
            }
            else if (options.show) {
                data.show()
            }
        })
    }

    $.fn.blankform.Constructor = Blank


    // MODAL NO CONFLICT
    // =================

    $.fn.blankform.noConflict = function () {
        $.fn.blankform = old
        return this
    }

    $("#order_online").on("click", function(e){
        $(this).blankform('show');
    });

}(jQuery);
