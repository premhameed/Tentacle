$(document).ready(function(){
	
	
    // Minimize Content Box
	// ====================================
		$(".content-box-header h3").css({ "cursor":"s-resize" }); // Give the h3 in Content Box Header a different cursor
		$(".closed-box .content-box-content").hide(); // Hide the content of the header if it has the class "closed"
		$(".closed-box .content-box-tabs").hide(); // Hide the tabs in the header if it has the class "closed"
		
		$(".content-box-header h3").click( // When the h3 is clicked...
			function () {
			  $(this).parent().next().toggle(); // Toggle the Content Box
			  $(this).parent().parent().toggleClass("closed-box"); // Toggle the class "closed-box" on the content box
			  $(this).parent().find(".content-box-tabs").toggle(); // Toggle the tabs
			}
		);

	// Admin Tabs:
	// ====================================
		$('#content-tabs a').click(function (e) {
		  e.preventDefault();
		  $(this).tab('show');
		})

    //Close button:
	// ====================================
		$(".close").click(
			function () {
				$(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
					$(this).slideUp(400);
				});
				return false;
			}
		);

    // Alternating table rows:
	// ====================================
		$('tbody tr:even').addClass("even"); // Add class "alt-row" to even table rows

    // Check all checkboxes when the one in a table head is checked:
	// ====================================
		$('.check-all').click(
			function(){
				$(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));   
			}
		);

	/* Username Lookup */
	// ====================================

	
	/* Tags */
	// ====================================
	if ($('body').hasClass('tags')) {
		$('.tags').tagsInput();
	}
	
	/* Alert */
	// ====================================
	if ($('body').hasClass('alert-message')) {
		$(".alert-message").alert();
	}
	
	/* Modal Window */
	// ====================================
	if ($('body').hasClass('alert-message')) {
		$('#my-modal').modal()
	}



	/* Publish on */
  	// =============================

	$(".published-on").hide();

	$("#publish").change(function(){          
	    var value = $("#publish option:selected").val();

	    if( value == 'published-on') {
	        $(".published-on").show();
	    } else {
	        $(".published-on").hide();
	    }
	});
	
	$("a#edit_publish").click(function(){          
	    var value = $("#publish option:selected").val();

	    if( value == 'published-on') {
	        $(".published-on").show();
	    } else {
	        $(".published-on").hide();
	    }
	});
	
	$(".published-on").hide();
	$("#edit_publish").show();
 
    $('#edit_publish').click(function(){
	    $(".published-on").toggle();
	})

/*
	$('textarea.editor').wysihtml5();
*/

});