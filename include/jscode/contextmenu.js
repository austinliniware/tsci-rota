document.oncontextmenu=function(e){return   false;}
window.onload = function(){
 document.body.onmouseup = function (){document.selection.empty();};
 document.body.oncontextmenu = function () {return false;};
 document.body.onselectstart = function () {return false;};
 document.body.onselect = function () {document.selection.empty()};
}