
var map_cities = {}   ;
var map_center = null ;
var map_proj_x ;
var map_proj_y ;
var map_proj_z ;
var map_viewport ;
var map_todraw = [];

function map_ajax_getBasicCities(ids) {

    if (ids.length == 0) {
        return ;
    }

    var target = "/Game/Map/Basic?" ;
    var params = [] ;
    
    for (var i in ids) {
        var id = ids[i] ;
        params.push("id[]=" + id) ;
    }
    target += params.join("&") ;
    
    var xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            map_ajax_gotBasicCities(eval('(' + xmlhttp.responseText + ')')) ;
        }
    }
    xmlhttp.open("GET", target, true);
    xmlhttp.send();
}

function map_ajax_gotBasicCities(cities) {
    
    // 1. add them to the table
    for (var i in cities) {
        var city = cities[i] ;
        if (map_center === null) {
            map_setCenter(city) ;
        }
        
        city.proj = map_proj2D(city) ;
        city.visible = map_isVisible(city) ;
        map_cities[city.id] = city ;
        map_todraw[city.id] = city ;
    }
    
    // 2. remain cities to get ?
    var remain = [] ;
    for (var i in cities) {
        if (cities[i].visible) {
            for (var j in cities[i].neighbours) {
                var nid = cities[i].neighbours[j] ;
                if (! (nid in map_cities)) {
                    remain.push(nid) ;
                }
            }
        }
    }
    
    // 3. draw what is drawable
    var drawn = [] ;
    for (var i in map_todraw) {
        var res = tryDraw(map_todraw[i]) ;
        if (res) {
            drawn[i] = map_todraw[i] ;
        }
    }
    
    for (var i in drawn) {
        delete map_todraw[i] ;
    }
    
    // 4. make new query
    
    if (remain.length > 0) {
        map_ajax_getBasicCities(remain) ;
    } else {
        map_ajax_getBasicDone() ;
    }
}

function tryDraw(city) {
    if (! city.visible) {
        return true ;
    } else {
        for (var j in city.neighbours) {
            var nid = city.neighbours[j] ;
            if (! (nid in map_cities)) {
                return false ;
            }
        }
        // Can draw it
        drawVoronoi(city) ;
        city.drawn = true ;
        return true ;
    }
}

function Vertex3D(x, y, z) {
    this.x = x ;
    this.y = y ;
    this.z = z ;
}

Vertex3D.prototype.normalize = function() {
    var n = Math.sqrt(
            this.x * this.x +
            this.y * this.y +
            this.z * this.z
            ) ;
    this.x /= n ;
    this.y /= n ;
    this.z /= n ;
}

Vertex3D.prototype.equals = function(v2) {
    if (v2 instanceof Vertex3D) {
        return this.x === v2.x && this.y === v2.y && this.z === v2.z ;
    } else {
        return false ;
    }
}

function vectorFromCity(city) {
    return new Vertex3D(city.x, city.y, city.z) ;
}

function vectorProduct(v1, v2) {
    return new Vertex3D(
            v1.y * v2.z - v1.z * v2.y,
            v1.z * v2.x - v1.x * v2.z,
            v1.x * v2.y - v1.y * v2.x
    ) ;
}

function scalarProduct(v1, v2) {
    return v1.x * v2.x + v1.y * v2.y + v1.z * v2.z ;
}

function map_setCenter(city) {
    map_center = city ;
    
    var c = vectorFromCity(city) ;
    var y = new Vertex3D(0.0, 1.0, 0.0) ;

    if (y.equals(c)) {
        map_proj_x = new Vertex3D(1.0, 0.0, 0.0) ;
        map_proj_y = new Vertex3D(0.0, 0.0, -1.0) ;
    } else {
        map_proj_x = vectorProduct(y, c) ;
        map_proj_x.normalize() ;
        
        map_proj_y = vectorProduct(c, map_proj_x) ;
        map_proj_y.normalize() ;
    }
    
    map_proj_z = c ;
}

function map_proj2D(city) {
    var city3d = vectorFromCity(city) ;

    var loc_x = scalarProduct(map_proj_x, city3d) ;
    var loc_y = scalarProduct(map_proj_y, city3d) ;
        
    return {
        "x" : Math.round(  100 * loc_x / map_delta),
        "y" : Math.round(- 100 * loc_y / map_delta)
    } ;
}

function map_isVisible(city) {
    
    var z = scalarProduct(vectorFromCity(map_center), vectorFromCity(city)) ;
    if (z < 0) {
        return false ;
    }
    var x = city.proj.x ;
    var y = city.proj.y ;
    return (x > -130 && x < 130 && y > -130 && y < 130) ;
}


function map_ajax_getBasicDone() {
    return ;
    // Draw the voronoï diagram
    for (var i in map_cities) {
        var c = map_cities[i] ;
        if (c.visible && ! c.drawn) {
            console.log(" - havent be drawn : " + i) ;
            drawVoronoi(c) ;
        }
    }

}

function drawVoronoi(city) {
    
    var aid = city.neighbours[city.neighbours.length - 1] ;
    var bid = city.neighbours[0] ;
    
    var p = avg3(city.proj, map_cities[aid].proj, map_cities[bid].proj) ;
    var path = "M" + p.x + " " + p.y + " " ;
    
    for (var i = 1; i < city.neighbours.length; i++) {
        aid = city.neighbours[i - 1] ;
        bid = city.neighbours[i] ;
        p = avg3(city.proj, map_cities[aid].proj, map_cities[bid].proj) ;
        path += "L" + p.x + " " + p.y + " " ;
    }
    path += "Z" ;
    
    var svg_path = document.createElementNS("http://www.w3.org/2000/svg", 'path');
    svg_path.setAttribute("d", path);
    svg_path.setAttribute("class", "map_city_path");
    svg_path.setAttribute("onclick", "map_clicked(" + city.id + ");") ;
    svg_path.setAttribute("id", "map_city_path_" +city.id ) ;
    var grey = 256 - city.albedo ;
    svg_path.setAttribute("fill", "rgb(" + grey + ", " + grey + ", " + grey + ")") ;
    map_viewport.appendChild(svg_path) ;
    
    map_city_icone1(city) ;
    map_city_icone2(city) ;
    map_city_icone3(city) ;
    
}

function map_city_icone1(city) {
    if (city.has_townhall === 1) {
        var has_townhall = document.createElementNS("http://www.w3.org/2000/svg", 'use');
        has_townhall.setAttribute("x", city.proj.x - 5);
        has_townhall.setAttribute("y", city.proj.y+2);
        has_townhall.setAttributeNS('http://www.w3.org/1999/xlink', "xlink:href", "#has_townhall") ;
        has_townhall.setAttribute("onclick", "map_clicked(" + city.id + ");") ;
        map_viewport.appendChild(has_townhall) ;
    } else if (city.has_ruin === 1) {
        var has_townhall = document.createElementNS("http://www.w3.org/2000/svg", 'use');
        has_townhall.setAttribute("x", city.proj.x - 2);
        has_townhall.setAttribute("y", city.proj.y+2);
        has_townhall.setAttributeNS('http://www.w3.org/1999/xlink', "xlink:href", "#has_ruin") ;
        has_townhall.setAttribute("onclick", "map_clicked(" + city.id + ");") ;
        map_viewport.appendChild(has_townhall) ;
    }
}

function map_city_icone2(city) {
    if (city.has_friend === 1) {
        var has_friend = document.createElementNS("http://www.w3.org/2000/svg", 'use');
        has_friend.setAttribute("x", city.proj.x + 5);
        has_friend.setAttribute("y", city.proj.y+2);
        has_friend.setAttributeNS('http://www.w3.org/1999/xlink', "xlink:href", "#has_friend") ;
        has_friend.setAttribute("onclick", "map_clicked(" + city.id + ");") ;
        map_viewport.appendChild(has_friend) ;
    }
}

function map_city_icone3(city) {
    if (city.id === map_center.id) {
        var has_townhall = document.createElementNS("http://www.w3.org/2000/svg", 'use');
        has_townhall.setAttribute("x", map_center.proj.x);
        has_townhall.setAttribute("y", map_center.proj.y-5);
        has_townhall.setAttributeNS('http://www.w3.org/1999/xlink', "xlink:href", "#here") ;
        has_townhall.setAttribute("onclick", "map_clicked(" + map_center.id + ");") ;
        map_viewport.appendChild(has_townhall) ;
    }
}

function avg3(p1, p2, p3) {
    return {
        "x" : Math.round((p1.x + p2.x + p3.x) / 3.0),
        "y" : Math.round((p1.y + p2.y + p3.y) / 3.0)
    } ;
}

function map_clicked(id) {
    
    // Just for test
    var target = "/Game/Map/Information?id=" + id ;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var card = document.getElementById("map-information") ;
            console.log(card) ;
            card.innerHTML  = xmlhttp.responseText ;
            console.log(xmlhttp.responseText) ;
        }
    }
    xmlhttp.open("GET", target, true);
    xmlhttp.send();

    
}

function map_loaded(id, size) {
    map_viewport = document.getElementById("map-viewport") ;
    map_delta = 5 * 0.4 * Math.PI / size ;
    var cities = [id] ;
    map_ajax_getBasicCities(cities) ;
}
