/** 
 *------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 *------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github 
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org 
 *------------------------------------------------------------------------------
 */

;(function($) {
  $(document).ready(function() {
      $(".head-cart .total_products").click(function(e){
        $(".vmCartList").removeClass('hide').addClass('show');
        $("#page-mask").removeClass('hide').addClass('show');
      });

      $("#hideCart").click(function(e) {
        $(".vmCartList").removeClass('show').addClass('hide');
        $("#page-mask").removeClass('show').addClass('hide');
      });

      $("#page-mask").click(function(e) {
        $(".vmCartList").removeClass('show').addClass('hide');
        $("#page-mask").removeClass('show').addClass('hide');
      });

      // VM product list effect
      $('.t3-section-1 .vmproduct.no-padding.productdetails').slick({
          infinite: false,
          speed: 300,
          slidesToShow: 6,
          slidesToScroll: 6,
          responsive: [
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 4,
              slidesToScroll: 4,
            }
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
          // You can unslick at a given breakpoint now by adding:
          // settings: "unslick"
          // instead of a settings object
        ]
      });

      var body = $('body');
      if (!$('.icon-drag', body).size()) {
          body.append('<div class="icon-drag">Drag</div>');
      }
      $('.slick-slide').mousemove(function(e) {
          if ($('.icon-drag', body).size()) {
              $('.icon-drag', body).css({
                  'top': e.pageY + 10,
                  'left': e.pageX + 10,
                  'display': 'block'
              });
          }
      })
      .mouseout(function(e) {
          $('.icon-drag', body).hide();
      })
      .mousedown(function(e) {
          $('.icon-drag', body).remove();
      });
    });

})(jQuery);

(function($){   
  $(document).ready(function(){                        
        
    ////////////////////////////////
    // equalheight for col
    ////////////////////////////////
    var ehArray = ehArray2 = [],
      i = 0;
    
    $('.t3-footnav').each (function(){
      var $ehc = $(this);
      if ($ehc.has ('.t3-footnav')) {
        ehArray2[ehArray2.length] = $ehc;
      } else {
        ehArray[ehArray.length] = $ehc;
      }
    });
    
    for (i = ehArray2.length -1; i >= 0; i--) {
      ehArray[ehArray.length] = ehArray2[i];
    }
     
    var equalHeight = function() {
      for (i = 0; i < ehArray.length; i++) {
        var $cols = ehArray[i].children().filter('.col-xs-12'),        
          maxHeight = 0,
          equalChildHeight = ehArray[i].hasClass('equal-height-child');
      
      // reset min-height  
        if (equalChildHeight) {
          $cols.each(function(){$(this).children().first().css('min-height', 0)});
        } else {
          $cols.css('min-height', 0);
        }
        $cols.each (function() {
          maxHeight = Math.max(maxHeight, equalChildHeight ? $(this).children().first().innerHeight() : $(this).innerHeight());
        });
        if (equalChildHeight) {
          $cols.each(function(){$(this).children().first().css('min-height', maxHeight)});
        } else {
          $cols.css('min-height', maxHeight);
        }
      }
      // store current size
      $('.t3-footnav > .col-xs-12').each (function(){
        var $col = $(this);
        $col.data('old-width', $col.width()).data('old-height', $col.innerHeight());
      });
    };
    
    equalHeight();
    
    // monitor col width and fire equalHeight
    setInterval(function() {
      $('.t3-footnav > .col-xs-12').each(function(){
        var $col = $(this);
        if (($col.data('old-width') && $col.data('old-width') != $col.width()) ||
            ($col.data('old-height') && $col.data('old-height') != $col.innerHeight())) {
          equalHeight();
          // break each loop
          return false;
        }
      });
    }, 500);
  });

})(jQuery);

(function ($) {
    $(document).ready(function () {
        reStyle();
        $(window).on('resize', reStyle);
    });

    function reStyle() {
        var windowHeight = $(window).height();

        if (windowHeight >= 768) {
            $('.slideshow-content').css({'padding-top': 120 + 'px'});
            $('.block-title').css({'font-size': 55 + 'px'});
        }
        else {
            $('.slideshow-content').css({'padding-top': 45 + 'px'});
            $('.block-title').css({'font-size': 45 + 'px'});
        }
    }
})
(jQuery);

