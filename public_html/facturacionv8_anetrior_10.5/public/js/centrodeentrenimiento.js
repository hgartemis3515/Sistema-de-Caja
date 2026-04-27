
$(function(){
  $(".overylay_descrip").click(function(){
    $('#overlay').addClass('active');
    $(this).addClass('active');
   
  });
  

});
function mouseOut() {
  $('#overlay').removeClass('active');
  $(this).removeClass('active');
}