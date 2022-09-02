var tab_box_class = $(".tab");
    var content_box_class = $(".content-box");
    tab_box_class.turnBox({
      perspective: 300,
      duration: 350,
      easing: "ease-in-out"
    });
    content_box_class.turnBox({
      width: 500,
      height: 213,
      perspective: 1300,
      duration: 350,
      easing: "ease-in-out",
      type: "skip",
      axis: "y"
    });
    for(i = 0; i < tab_box_class.length; i++) {
      var num = i + 1;
      $(".tab.tab-" + num).turnBoxLink(
      {
        box: ".content-box",
        dist: num,
        events: "click"
      });
    };

    $.each($(".tabs").children(), function(key)
    {
      var tab = key + 1;

      $(".tab.tab-" + tab).find(".turnBoxButton").on("click", function()
      {
        $(".tab").not(".tab-" + tab).turnBoxAnimate();
      });
    });

    $(".tab.tab-1").turnBoxAnimate(
    {
      face: 2,
      animation: false
    });