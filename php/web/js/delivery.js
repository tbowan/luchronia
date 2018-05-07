

function delivery_del(button) {
    var td = button.parentNode ;
    var tr = td.parentNode ;
    tr.parentNode.removeChild(tr) ;
}

function delivery_change(input, key) {
    
    var td = input.parentNode ;
    var tr = td.parentNode ;
    var idx = tr.id ;
    
    var cost_u = document.getElementById("delivery-prestige-" + idx) ;
    var cost_t = document.getElementById("delivery-cost-" + idx) ;
    
    var old_c = parseFloat(cost_t.innerHTML) ;
    cost_t.innerHTML = parseFloat(cost_u.innerHTML) * input.value ;
    var new_c = parseFloat(cost_t.innerHTML) ;
    
    delivery_subtotal(new_c - old_c, key) ;
    
}

function delivery_subtotal(dc, key) {
    var subtotal  = document.getElementById("delivery-subtotal-" + key) ;
    var precision = document.getElementById("delivery-precision-" + key) ;
    var total     = document.getElementById("delivery-total-" + key) ;
    
    subtotal.innerHTML = parseFloat(subtotal.innerHTML) + dc ;
    total.innerHTML = parseFloat(subtotal.innerHTML) * (1.0 + parseFloat(precision.value) / 100) ; 
}

function delivery_nextId(table) {
    var max = 0 ;
    var rows = table.childNodes ;
    for (var i=0; i < rows.length ; i++) {
        var r = rows[i] ;
        if (max < r.id) {
            max = r.id ;
        }
    }
    return max + 1 ;
}

function delivery_add_ajax(key, item) {
    var table = document.getElementById(key + "-table") ;
    var row = table.insertRow(-1) ;
    var idx = delivery_nextId(table) ;
    row.id = idx ;
    
    row.innerHTML = "<td>"
            + "<img src=\"" + item.img + "\" class=\"icone\" />" + item.name
            + "<input type=\"hidden\" name=\"" + key + "[items][" + idx + "][item]\" value=\"" + item.id + "\" />"
            + "</td>" ;
    row.innerHTML += "<td>"
                    + "<input"
                        + " type=\"text\""
                        + " name=\"" + key + "[items][" + idx + "][qtty]\""
                        + " value=\"0\""
                        + " onchange=\"delivery_change(this, '" + key + "');\""
                        + " onkeyup=\"this.onchange();\""
                        + " onpaste=\"this.onchange();\""
                        + " oninput=\"this.onchange();\""
                        + " style=\"width:4em;min-width:0;\""
                        + " />"
                    + "</td>" ;
    row.innerHTML += "<td>"
                + "<span id=\"delivery-prestige-" + idx + "\">" + item.prestige + "</span>"
                + "</td>" ;
    row.innerHTML += "<td>"
                + "<span id=\"delivery-cost-" + idx + "\">" + 0 + "</span>"
                + "</td>" ;
    row.innerHTML += "<td>"
            + "<input type=\"button\" value=\"-\" onclick=\"delivery_del(this);\" style=\"min-width:2em;\" />"
            + "</td>" ;
    
}

function delivery_add(key) {
    var select = document.getElementById(key + "-item") ;
    
    var target = "/Ajax/Item?id=" + select.value ;
    
    var xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            delivery_add_ajax(key, eval('(' + xmlhttp.responseText + ')')) ;
        }
    }
    xmlhttp.open("GET", target, true);
    xmlhttp.send();
}