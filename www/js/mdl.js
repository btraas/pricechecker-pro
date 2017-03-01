var MDL = {};
$(document).ready(function()
{
	//$('.mdl-layout').on('mdl-componentupgraded', function(e) {
 //       if ($(e.target).hasClass('mdl-layout')) {

			var snackbar = $('.mdl-snackbar');

			if(snackbar.length != 1) {
				alert('invalid number of snackbars...: '+snackbarlength);
				return;
			}

			MDL.snackbar = snackbar[0].MaterialSnackbar;
			MDL.snackbar.data = {};
			MDL.snackbar.show = function() { return MDL.snackbar.showSnackbar(this.data) };
		
			MDL.error = function(options)
			{ 
				var data = { timeout: 5000  };
				$.each(options, function(key, val)
				{
					data[key] = val;
				});

				return MDL.snackbar.showSnackbar(data);
			}
//		}
//	});

});

