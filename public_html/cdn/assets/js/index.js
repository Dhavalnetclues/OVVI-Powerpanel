$(function() {
    // Owl Carousel
    var owl = $(".home-client .owl-carousel");
    owl.owlCarousel({
      items: 4,
      margin: 30,
      loop: true,
      dots: true,
      autoplay: true,
      center:true,
      responsiveClass: true,
      responsive: {
          0: {
              items: 1,
          },
          480: {
              items: 1,
          },
          992: {
              items: 2,
          },
          1441: {
              items: 4,           
          }
        }
      
    });
  });
  