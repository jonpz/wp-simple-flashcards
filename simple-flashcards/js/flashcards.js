jQuery(document).ready(function($){
  if($('body.tax-deck').length){
    $('.slider-container').slick({
      infinite:false,
    });
    $('.flashcard-flipper').click(function(){
      var el = $(this);
      el.css('display','none');
      el.closest('.flashcard-content').toggleClass('over');
      setTimeout(function(){
        el.css('display','block');
      },550);
    });
    $('.slider-container').on('beforeChange',function(e,s,c,n){
      $($('.flashcard-slide')[c]).find('.flashcard-content').removeClass('over');
    });
  }
});
