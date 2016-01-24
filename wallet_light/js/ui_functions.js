function bitcoinControl(e) {
    var domvalue = $(e).attr('id');
    var r=false;
      switch (domvalue) {  
          case "bitcoin_restart":
              r = confirm("Are you sure you want to restart bitcoin?");
              break;
          case "update-software":
              r = confirm("Are you sure you want to update the bitcoin software?");
              break;
          case "device_shutdown":
              r = confirm("Are you sure you want to shutdown the device?"); 	
              break;
       }
       if (r == false) {
           return;
       }
       var request = $.ajax({
           url: "php/jquery-ajax.php",
           type: "GET",
           dataType: "html",
           data: {
               id: domvalue
           },
       });

       request.done(function(msg) {
           $("#device_status").html(msg);
       });

       request.fail(function(jqXHR, textStatus) {
           alert( "Request failed: " + textStatus );
       });
};

// function sleep(milliseconds) {
// 	alert("Made It");
  //   var start = new Date().getTime();    		
	// $("#bitcoin_status").html("Made it");
// 	for (var i = 0; i < 1e7; i++) {
//        if ((new Date().getTime() - start) > milliseconds) {
//              break;
//        }
//     }
//  }	

// $.fn.sleep = function(time, func) {
//      this.each(function() {
 //        setTimeout(func, time);	
//      });
//      return this;
// };
