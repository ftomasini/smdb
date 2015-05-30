$(document).ready(function(){

//Superfish menu
$("ul.sf-menu").supersubs().superfish(
{
            delay:       1000,                            // one second delay on mouseout
            animation:   {opacity:'show'},  // fade-in and slide-down animation
            speed:       'normal',                          // faster animation speed
            autoArrows:  false,                           // disable generation of arrow mark-up
            dropShadows: false                            // disable drop shadows
        }
);

//Toggle functions
$(".hide-excerpt").click(function (event) {
	event.preventDefault();
      $(this).parents(".excerpt").hide("normal");
    });

 $(".view-excerpt").toggle(
                    function(){
      			 $(this).parents(".headline").next(".excerpt").hide("normal");
                    }, function() {
      			 $(this).parents(".headline").next(".excerpt").show("normal");
                    });

 $("#toggle-all").toggle(
                    function(){
                         $(".excerpt").hide('slow');
			 $("#toggle").attr("class","show-all");
                    }, function() {
                         $(".excerpt").show('slow');
			 $("#toggle").attr("class","hide-all");
                    });
 });
