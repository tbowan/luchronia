
function feed_constant(span, value) {
    
    // console.info(value) ;
    span.innerHTML = value ;
    
    if (value < 750) {
        span.className = "feed_below" ;
    } else if (value <= 1000) {
        span.className = "feed_good" ;
    } else {
        span.className = "feed_high" ;
    }
    // console.info(span.className) ;
}


function feed_change() {
    
    // console.info("feed_change") ;
    
    var inputs = document.getElementsByClassName("feed_input") ;
    
    var i ;
    var key ;
    var sum_e = parseFloat(document.getElementById("base-energy").innerHTML) ;
    var sum_h = parseFloat(document.getElementById("base-hydration").innerHTML);
    
    for (i=0; i < inputs.length; i++) {
        
        key = inputs[i].name ;
        
        sum_e += parseFloat(document.getElementById(key + "-energy").innerHTML) * inputs[i].value ;
        sum_h += parseFloat(document.getElementById(key + "-hydration").innerHTML) * inputs[i].value ;
    }
    
    feed_constant(document.getElementById("total-energy"), sum_e) ;
    feed_constant(document.getElementById("total-hydration"), sum_h) ;
    
}

function feed_decrement(form, key) {
    var input = form[key] ;
    input.value = Math.max(0, input.value - 1) ;
    feed_change() ;
}

function feed_increment(form, key) {
    var input = form[key] ;
    var remain = Math.floor(parseFloat(document.getElementById(key + "-remain").innerHTML)) ;
    input.value = Math.min(remain, parseInt(input.value) + 1);
    feed_change() ;
}

function feed_auto_min(form, key) {
    var input = form[key] ;
    var already = parseInt(input.value) ;
    var remain = Math.floor(parseFloat(document.getElementById(key + "-remain").innerHTML)) ;
    
    var rate = parseFloat(document.getElementById(key + "-hydration").innerHTML);
    var actu = parseFloat(document.getElementById("total-hydration").innerHTML);
    
    if (rate === 0) {
        rate = parseFloat(document.getElementById(key + "-energy").innerHTML);
        actu = parseFloat(document.getElementById("total-energy").innerHTML);
    }
    
    var min = Math.ceil((750 - actu) / rate) ;
    var max = Math.floor((1000 - actu) / rate) ;
    
    var value = Math.min(remain, already + Math.min(min, max)) ;
    
    input.value = value ;
    feed_change();
}

function feed_auto_max(form, key) {
    var input = form[key] ;
    var already = parseInt(input.value) ;
    var remain = Math.floor(parseFloat(document.getElementById(key + "-remain").innerHTML)) ;
    
    var rate = parseFloat(document.getElementById(key + "-hydration").innerHTML);
    var actu = parseFloat(document.getElementById("total-hydration").innerHTML);
    
    if (rate === 0) {
        rate = parseFloat(document.getElementById(key + "-energy").innerHTML);
        actu = parseFloat(document.getElementById("total-energy").innerHTML);
    }
    
    var value = Math.min(remain, already + Math.floor((1000 - actu) / rate)) ;
    
    input.value = value ;
    feed_change();
}

function feed_reset(form, key) {
    var input = form[key] ;
    input.value = 0 ;
    feed_change() ;
}