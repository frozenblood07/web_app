jQuery(document).ready(function() {

  if($(".touchspin").length) {
      $(".touchspin").TouchSpin({
          min: 1,
          max: available
      });

      $(".touchspin").on("change", function(event) {
          jQuery("#total").html(jQuery("#price").html() * event.target.value);
      });
  }

  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
      sURLVariables = sPageURL.split("&"),
      sParameterName,
      i;

    for (i = 0; i < sURLVariables.length; i++) {
      sParameterName = sURLVariables[i].split("=");
      if (sParameterName[0] === sParam) {
        return sParameterName[1] === undefined ? true : sParameterName[1];
      }
    }
  };

  $("#checkout").click(function() {
      var pathname = window.location.pathname.split("/");
      var showId = pathname[pathname.length-1];
    var url = "/checkout/"+showId;
    var data = {
      quantity: $("#quantity").val(),
      date: getUrlParameter("date")
    };

    $.ajax({
      type: "POST",
      url: url,
      data: data,
      success: function(response) {
        if(response.status) {
          alert(response.outputParams.data.msg);
        } else {
          alert(response.errorMsg);
        }
      },
        error: function() {
          alert("Something went wrong.");
        }
    });
  });
});
