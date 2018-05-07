

function toggleClicked(id) {
    var hdr = document.getElementById(id) ;
    if (hdr.className === "clicked") {
        hdr.className = "" ;
    } else {
        hdr.className = "clicked" ;
    }
}

function initTinyMCE() {
    
    var htmlareas = document.getElementsByClassName('htmlarea');
    
    if (htmlareas.length > 0) {
        tinymce.init({
            relative_urls : false, 
            selector:'textarea.htmlarea',
            theme: "modern",
            plugins:["link","table","image"],
            entity_encoding : "raw",
            language : "fr_FR"
        });
    }
}

window.addEventListener("load", main) ;

function main() {
    initTinyMCE() ;
}


        