var last_anchor = "outside";

function display_card(anchor_name) {
    document.getElementById(last_anchor).style.display = "none";
    document.getElementById(anchor_name).style.display = "block"; 
    last_anchor = anchor_name;
}