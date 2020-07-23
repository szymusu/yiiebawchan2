$('.auto-grow').onchange = function (e) {
    auto_grow(e);
}
$('.auto-grow').onload = function (e) {
    auto_grow(e);
}

var auto_grow = function(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}