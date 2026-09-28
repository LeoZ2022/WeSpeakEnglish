$(function() {

    $(".boxt").click(function() {

        // List moving to
        var $newList = $(this);
		var $parList = $(this).parents(".tabbox");
		
        // Figure out current list via CSS class
        var curList = $parList.find(".boxt.g").attr("title");
        
 // Set outer wrapper height to height of current inner list
//    var curListHeight = $("#boxtab").height();
//      $("#boxtab").height(curListHeight);
        
        // Remove highlighting - Add to just-clicked tab
        $parList.find(".boxt").removeClass("g");
        $newList.addClass("g");
        
 var listID = $newList.attr("title");
        
        if (listID != curList) {
            
            // Fade out current list
            $parList.find("#"+curList).fadeOut(50, function() {
                // Fade in new list on callback
                $parList.find("#"+listID).fadeIn();
            });
        };
        // Don't behave like a regular link
       return false;
    });
		

});