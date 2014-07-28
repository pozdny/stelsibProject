/**
 * Created by Valentina on 02.04.14.
 */
(function($){
    var defaults = {
        placement: "top",
        container: "body",
        trigger: "hover"

    };
    var methods = {
        init:function(params) {
            return this.each(function(){
                var o = $.extend({}, defaults, params);
                var $this = $(this);
                console.log($this);
                placement = $this.data("placement")? $this.data("placement") : o.placement;
                container = $this.data("container")? $this.data("container") : o.container;
                trigger = $this.data("trigger")? $this.data("trigger") : o.trigger;
                if (trigger == 'click') {
                    $this.on('click', $.proxy($this.m_popover('show'), $this));
                } else if (trigger != 'manual') {
                    var eventIn  = trigger == 'hover' ? 'mouseenter' : 'focusin';
                    var eventOut = trigger == 'hover' ? 'mouseleave' : 'focusout';

                    $this.on(eventIn, $.proxy($this.m_popover('show'), $this))
                    $this.on(eventOut, $.proxy($this.m_popover('close'), $this))
                }



                //alert(trigger);
            });
        },
        show:function(){
            alert('3');
        }
    };

    var old = $.fn.m_popover;
    $.fn.m_popover = function(method){
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {

            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Метод "' +  method + '" не найден в плагине jQuery.m_popover' );
        }

    };
    $.fn.m_popover.noConflict = function () {
        $.fn.m_popover = old;
        return this;
    }

    //$('[rel="m_popover"]').m_popover();

})(jQuery);
