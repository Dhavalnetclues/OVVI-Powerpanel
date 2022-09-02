if (segments[segments.length - 1] == "print") {

  window.print();

  // var divToPrint = $(".inner_pages");
  // var varTitle = $('h1').html();
  // var dataToSend = String(divToPrint.html());
  // $.ajax({
  //   headers: {
  //     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
  //   },
  //   converters: {
  //     "text json": true
  //   },
  //   async: false,
  //   url: site_url + "/print",
  //   type: "POST",
  //   dataType: "HTML",
  //   data: {
  //     content: dataToSend,
  //     title:varTitle
  //   },
  //   success: function(data) {
  //     newWin = window.open("");
  //     newWin.document.write(data);
  //     newWin.print();
  //     newWin.close();
  //   },
  //   complete: function() {}
  // });
}

// jQuery.fn.outerHTML = function() {
//   return jQuery('<div />').append(this.eq(0).clone()).html();
// };
