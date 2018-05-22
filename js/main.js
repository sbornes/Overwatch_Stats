
$(document).ready(function (){

	$("#search-txt").keyup(function(event){
	    if(event.keyCode == 13){
	        $("#search-btn").click();
	    }
	});

});

$(document).ready(function (){
  	/*$("#search-btn").click(function() {
    	getPlayerInfo("");
	});

	$("#update-btn").click(function() {
    	getPlayerInfo($("#update-btn").val());
	});*/

	//https://stackoverflow.com/questions/26475445/prevent-ajax-call-from-firing-twice
	$('#search-btn').on('click', function(){getPlayerInfo("");});
	$('#update-btn').on('click', function(){getPlayerInfo($("#update-btn").val());});
});

function getPlayerInfo(update) {
		
	var battletag = update;
	var dataPoints = [];

	if(battletag === "") {
		battletag = $('#search-txt').val();
	}

	if ($.trim(battletag) === "") {
        // If its value is empty
        $('#formAlert').html("<strong>Warning!</strong> Enter a battletag including account number.").hide().removeClass("hide").slideDown(400); 
    } else {
    	battletag = battletag.replace("#", "-");

    	$( ".content" ).show();

	    $('html, body').animate({
	        scrollTop: $(".content").offset().top
	    }, 1000);

	    $('#fade').fadeIn("slow");
	    $('#return-to-top').fadeOut("slow");
	    $('.spinner').show();  // show the loading message.
        $("#formAlert").slideUp(400, function () {    // Hide the Alert (if visible)
        		var result1;
        		var result2;
        		var result3;
        		var result4;
				$.when(
				    $.ajax({ // First Request
				        url: 'getStats.php', 
				        type: 'post',      
				        data: { "battletag": battletag, "platform": "pc", "region": "/us"},     
				        cache: false,
				        dataType: "json",
				        success: function(returnhtml){     
				                result1 = returnhtml;  
				                //console.log(result1);                
				        }           
				    }),

				    $.ajax({ // Second Request
				        url: 'getHeroStats.php', 
				        type: 'post',      
				        data: { "battletag": battletag, "platform": "pc", "region": "/us"},     
				        cache: false,
				        success: function(returnhtml){   
				                result2 = returnhtml;                  
				        }           
				    }),

				    $.ajax({ // Third Request
				        url: 'getHeroPlaytime.php', 
				        type: 'post',      
				        data: { "battletag": battletag, "platform": "pc", "region": "/us"},     
				        cache: false,
				        success: function(returnhtml){     
				                result3 = returnhtml;                  
				        }           
				    })

				    

				).then(function() {
    				$('#fade').fadeOut("slow");
			        $('#return-to-top').fadeIn("slow");
			        $('.spinner').hide();


			        if(result1['stats']['competitive']['response']['status'] == "success") {
			        	var tz = jstz.determine(); // Determines the time zone of the browser client
						var timezone = tz.name(); //'Asia/Kolhata' for Indian Time.

			        	$('.response1').load('stats.php', { "battletag": battletag, "tz": timezone, "first_time": result1['stats']['competitive']['response']['first_time'] });
	    				$('.response2').html(result2);
	    				$('.response3').html(result3);
	    				//$('.response4').html(result4);
	    				$('.response4').show();

	    				$.ajax({ // First Request
					        url: 'getCompetitiveChart.php', 
					        type: 'post',      
					        data: { "battletag": battletag, "platform": "pc", "region": "/us"},     
					        cache: false,
					        dataType: "json",
					        success: function(returnhtml){     
					                result4 = returnhtml;  

					                $.each(result4, function(key, value){
					                	var d = "";
					                	if(value.rank_diff > 0)
					                		d = "<span style='color: green;'>+" + parseInt(value.rank_diff) + "</span>";
					                	else if(value.rank_diff < 0)
					                		d = "<span style='color: red;'>" + parseInt(value.rank_diff) + "</span>";
					                	else
					                		d = "<span style='color: grey;'>+" + parseInt(value.rank_diff) + "</span>";

								        dataPoints.push({x: new Date(value.rank_date), y: parseInt(value.rank), w: parseInt(value.wins), l: parseInt(value.losses), t: parseInt(value.ties), d: d});
								        console.log(value.rank_date);
								        console.log(parseInt(value.rank));
								    });
								    

								    var chart = new CanvasJS.Chart("chartContainer",{
								    	backgroundColor: "#333",
					
								        axisY: {
									        interval: 250,
									        includeZero: false,
									        labelFontColor: "white",
									        gridThickness: 1,
											gridColor: "grey"
									    },
									    axisX: { 
									    	interval: 2,
											intervalType: "day", 
											valueFormatString: "DD-MMM", 
											labelAngle: -45,
											labelFontColor: "white",
											gridThickness: 1,
											gridColor: "grey"
										}, 
								        animationEnabled: true,
								        toolTip: {
								        	content: "{x} <br><br> <span style='\"'color: rgb(157, 108, 217);'\"'>Skill Rating</span>: {y} ({d}) <br> <span style='\"'color: green;'\"'>Wins</span>: {w} <br> <span style='\"'color: red;'\"'>Losses</span>: {l} <br> <span style='\"'color: grey;'\"'>Ties</span>: {t}"
								        },
								        data: [{
								        type: "spline",
								            dataPoints : dataPoints,
								        }]
								    });
								    chart.render();      
								}          
					                   
					    })
	    				

			        } else {
			        	if(result1['stats']['competitive']['response']['message'] == 'Battlenet ID does not exist.'){
				        	$('html, body').animate({
						        scrollTop: $(".background").offset().top
						    }, 1000);

							$('#return-to-top').fadeOut("slow");
					        $('.spinner').hide();
					        $('#formAlert').html("<strong>Warning!</strong> Battlenet ID does not exist.").hide().removeClass("hide").slideDown(400); 
					        setTimeout(function(){
							  $('.content').hide();
							}, 2000);
					    } else if(result1['stats']['competitive']['response']['message'] == "We don't have any data for this account in this mode yet.") {
					    	$('html, body').animate({
						        scrollTop: $(".background").offset().top
						    }, 1000);

							$('#return-to-top').fadeOut("slow");
					        $('.spinner').hide();
					        $('#formAlert').html("<strong>Warning!</strong> We don't have any data for this account in this mode yet.").hide().removeClass("hide").slideDown(400); 
					        setTimeout(function(){
							  $('.content').hide();
							}, 2000);

					    } else if(result1['stats']['competitive']['response']['message'] == "This account does not have any competitive data.") {
					    	$('html, body').animate({
						        scrollTop: $(".background").offset().top
						    }, 1000);

							$('#return-to-top').fadeOut("slow");
					        $('.spinner').hide();
					        $('#formAlert').html("<strong>Warning!</strong> This account does not have any competitive data.").hide().removeClass("hide").slideDown(400); 
					        setTimeout(function(){
							  $('.content').hide();
							}, 2000);
					        
					    }
			        }
				});

            /*$.ajax({
		      url: 'test.php',
		      type: 'post',
		      data: { "battletag": battletag, "platform": "pc", "region": "/us"},
		      success: function(response) { 
		        

		        if(response == 'Battlenet ID does not exist.'){
		        	$('html, body').animate({
				        scrollTop: $(".background").offset().top
				    }, 1000);

					$('#return-to-top').fadeOut("slow");
			        $('.spinner').hide();
			        $('#formAlert').html("<strong>Warning!</strong> Battlenet ID does not exist.").hide().removeClass("hide").slideDown(400); 
			        setTimeout(function(){
					  $('.content').hide();
					}, 2000);

			    } else if(response == "We don't have any data for this account in this mode yet.") {
			    	$('html, body').animate({
				        scrollTop: $(".background").offset().top
				    }, 1000);

					$('#return-to-top').fadeOut("slow");
			        $('.spinner').hide();
			        $('#formAlert').html("<strong>Warning!</strong> We don't have any data for this account in this mode yet.").hide().removeClass("hide").slideDown(400); 
			        setTimeout(function(){
					  $('.content').hide();
					}, 2000);
			        
			    } else {
			    	
					$('.response').html(response);
			        $('#fade').fadeOut("slow");
			        $('#return-to-top').fadeIn("slow");
			        $('.spinner').hide();
			    }
		      },
		  }); */

        });
    }
}

 // ===== Scroll to Top ==== 
$(window).scroll(function() {
    if ($(this).scrollTop() >= 50 && $.active == 0) {        // If page is scrolled more than 50px
        $('#return-to-top').fadeIn(200);    // Fade in the arrow
    } else {
        $('#return-to-top').fadeOut(200);   // Else fade out the arrow
    }
});
$('#return-to-top').click(function() {      // When arrow is clicked
    $('body,html').animate({
        scrollTop : 0                       // Scroll to top of body
    }, 500);
});