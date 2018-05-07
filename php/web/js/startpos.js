
function startpos_city_click(circle) {
    var select = document.getElementById("startpos_select") ;
    var id = circle.id.replace("startpos_city_", "") ;
    
    select.value = id ;
    
    startpos_change(id) ;
}

function startpos_select_change(select) {
    startpos_change(select.value) ;
}

function startpos_change(id) {
    
    var circle = document.getElementById("startpos_city_" + id) ;
    
    var x = circle.getAttribute("cx") ;
    var y = circle.getAttribute("cy") ;
    
    var abs = document.getElementById("startpos_abs") ;
    abs.setAttribute("x1", x) ;
    abs.setAttribute("x2", x) ;
    
    var ord = document.getElementById("startpos_ord") ;
    ord.setAttribute("y1", y) ;
    ord.setAttribute("y2", y) ;

}