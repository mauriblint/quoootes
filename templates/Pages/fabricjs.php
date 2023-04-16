<script src='https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js'></script>
<link href='https://fonts.googleapis.com/css?family=Oswald|Lobster|Fontdiner+Swanky|Crafty+Girls|Pacifico|Satisfy|Gloria+Hallelujah|Bangers|Audiowide|Sacramento' rel='stylesheet' type='text/css'>
<span class="txtFont" style="font-family:Oswald">Oswald</span>
<span class="txtFont" style="font-family:Lobster">Lobster</span>

<div class="box">
  <textarea id='input' oninput='onSelectionChanged()' rows="5"></textarea >

  <canvas id="c"></canvas>
</div>

<style>
#c {
  border: 1px solid red;
  top: 22px;
  left: 0px;
  height: 100%;
  width: 99%;
}

.box {
  float: left;
  margin: 1em;
}

.after-box {
  clear: left;
}
</style>
<script>
var cWidth = 700;
var cHeight = 450;
var fontSize = 10;
var text_w = Math.round(cWidth*0.6);
var text_h = Math.round(cHeight*0.6);
var txt = 'This gonna be almost maxed out in height, help me to increase it more! just asdasd asdas'
var text;
var input = document.getElementById('input')
fabric.TextboxWithPadding = fabric.util.createClass(fabric.Textbox, {
  type: 'textboxwithpadding',

  toObject: function() {
    return fabric.util.object.extend(this.callSuper('toObject'), {
      backgroundColor: this.get('backgroundColor'),
      padding: this.get('padding'),
    });
  },

  _renderBackground: function(ctx) {
    if (!this.backgroundColor) {
      return;
    }
    var dim = this._getNonTransformedDimensions();
    ctx.fillStyle = this.backgroundColor;

    ctx.fillRect(
      -dim.x / 2 - this.padding,
      -dim.y / 2 - this.padding,
      dim.x + this.padding * 2,
      dim.y + this.padding * 2
    );
    // if there is background color no other shadows
    // should be casted
    this._removeShadow(ctx);
  }
});


var $ = function(id) {
  return document.getElementById(id)
};


var canvas = this.__canvas = new fabric.Canvas('c');
canvas.setWidth(cWidth);
canvas.setHeight(cHeight);

var ctx = canvas.getContext("2d")

canvas.backgroundColor = 'red';
canvas.setBackgroundImage('https://images.pexels.com/photos/14306688/pexels-photo-14306688.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2', function() {
resizeBgImgage();
}, { crossOrigin: 'Anonymous' });
resizeBgImgage = function () {
    let img = canvas.backgroundImage;
    img.originX = 'left';
    img.originY = 'top';
    img.scaleX = canvas.getWidth() / img.width;
    img.scaleY = canvas.getHeight() / img.height;
    canvas.renderAll();
};
// canvas.setBackgroundImage('https://images.pexels.com/photos/14306688/pexels-photo-14306688.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2', canvas.renderAll.bind(canvas), {
//     // Optionally add an opacity lvl to the image
//     backgroundImageOpacity: 0.1,
//     // should the image be resized to fit the container?
//     backgroundImageStretch: false,
//     scaleX:1,
//         scaleY:1,
//         top: canvas.getCenter().top,
//         left: canvas.getCenter().left,
//         originX: 'center',
//         originY: 'center'
// });
setTimeout(Addtext, 200);
// Addtext()

function Addtext() {
  text = new fabric.Textbox(txt, {
    top: canvas.getHeight() / 2,
    left: cWidth / 2,
    fontFamily: 'Oswald',
    fill: 'red',
    fontSize: 20,
    textAlign: 'center',
    splitByGrapheme: false,
    width: text_w,
    padding: 20,
    // stroke: 'red',
    // strokeWidth: 1,
    // lockMovementX: true,
    // lockMovementY: true,
    // lockScalingX: true,
    // lockScalingY: true,
    centeredScaling: true,
    originX: 'center',
    textBackgroundColor: 'white',
    // backgroundColor: 'black',
  });
  canvas.add(text);
  text.center();
  input.value = text.text
  onSelectionChanged()
}

function onSelectionChanged() {
  text.text = input.value
  canvas.renderAll()
  input.onkeyup = function() {
    var key = event.keyCode || event.charCode;
    if (key == 8) {
      //backspace pressed
      text.set("fontSize", fontSize);
      canvas.renderAll()
      adjustFontSize()
    }
  };
  adjustFontSize()
}

function adjustFontSize() {
  let current = text.fontSize
  if (text.height > cHeight) {
    text.set("fontSize", current - 1);
    canvas.renderAll()
    adjustFontSize()
    return
  } else {
    if (current < fontSize) {
      if (cHeight - text.height < (current * text.lineHeight) * 1.5) {
        return
      } else {
        text.set("fontSize", fontSize);
        canvas.renderAll()
        adjustFontSize()
        return
      }
    }
    return
  }
}
</script>