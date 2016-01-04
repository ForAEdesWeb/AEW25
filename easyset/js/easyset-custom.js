jQuery(document).ready(function(){
      jQuery("#liveDemo").click(function(){
          var iframe = jQuery(".lazyIframe");
          var url = iframe.attr("data-url");
      
          iframe.attr("src", url);
      });
  });