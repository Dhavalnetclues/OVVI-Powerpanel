var frames = SpriteSpin.sourceArray('assets/images/NoteImage/home_note_{frame}.png', { 
    frame: [1, 26], 
    digits: 3 
});
var spin = $('.spritespin');
var slide = $('.slider');

spin.spritespin({
    source: frames,
    width: 500,
    sense: -1,
    height: 213,
    animate: false,
    onLoad: function(){
        slide.slider({
            min: 0,
            max: frames.length - 1,
            slide: function(e, ui){
                var api = spin.spritespin('api');
                api.stopAnimation();
                api.updateFrame(ui.value);
            }
        })
    },
    onFrame: function(e, data){
        slide.slider('value', data.frame);
    }
	
});