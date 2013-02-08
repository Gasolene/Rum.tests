$(window).load(function(){


$(function () {
	
            $("#panel-tab").click(function (event) {
				event.stopPropagation();
                showIfNotVisible("#panel-content");
				$("#panel-tab").animate({width: '150px'}, 500);
			});
			
	
			
        });

        function showIfNotVisible(element) {
                
            if ($(element).css("width")=='150px')
            	$('body').animate({marginLeft: '0px'}, 500),
                $(element).animate({width: '0px'}, 500);
            else
                $('body').animate({marginLeft: '200px'}, 500),
                $(element).animate({width: '150px'}, 500);

        }
        
        

        
});