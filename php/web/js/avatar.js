
var prefix_name = "radio-"

function avatar_item_change(radio) {
    
    var item  = radio.value ;
    var image = document.getElementById("avatar-item-img-" + item) ;
    var url   = image.src.replace("radio-", "") ;
    

    var layer = radio.className.replace("avatar-item-", "") ;
    img = document.getElementById("avatar-svg-layer-" + layer) ;
    img.setAttribute("xlink:href", url) ;
}

var avatar_fieldset = null ;

function avatar_layer_change(radio) {

    if (avatar_fieldset !== null) {
        avatar_fieldset.style.display = "none" ;
    }
    
    layer = radio.value ;
    avatar_fieldset = document.getElementById("fieldset-layer-" + layer) ;
    avatar_fieldset.style.display = "block" ;
    
}