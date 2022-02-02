(function($){
  "use strict"

  $(document).ready(function(){

    $('.accordion__heading--type').click(function(){
      $(this).toggleClass('active');
      $(this).find('.accordion__chevron--type').toggleClass('active');
      $(this).closest('table').find('.accordion__body--type').toggleClass('active');
      $(this).closest('table').find('.accordion__body--type').stop().toggle();
    });

    $('.accordion__heading--div').click(function(){
      $(this).toggleClass('active');
      $(this).find('.accordion__chevron--div').toggleClass('active');
      $(this).find('.accordion__body--div').toggleClass('active');
      $(this).closest('.accordion--div').find('.accordion__body--div').stop().toggle();
    });

    // Ready
  });
})(window.jQuery)
