$(document).ready(function(){
    var url = document.location.href;
    var qs = url.substring(url.indexOf('?') + 1).split('&');
    for(var i = 0, result = {}; i < qs.length; i++){
        qs[i] = qs[i].split('=');
        result[qs[i][0]] = decodeURIComponent(qs[i][1]);
    }
    $("."+result.page).addClass("mm-active");
    $("."+result.subpage).addClass("mm-active");
    if(result.page==undefined){
        $(".dashboard").addClass("mm-active");
    }
});