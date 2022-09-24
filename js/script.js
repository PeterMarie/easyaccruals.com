$("#showMenu").click(function(){
    $("#mobileNav").show().animate({left: '60%'},400);
    //$("#mobileNav").animate({left: '60%'},400);
    document.getElementById("pageCover").setAttribute('onclick', 'hideMenu()');
    document.getElementById("pageCover").setAttribute('style', 'background-color: unset;');
    $("#pageCover").show();
});

function hideMenu(){
    $("#mobileNav").animate({left: '100%'},{duration: 400, complete: function(){$(this).hide();}});
    document.getElementById("pageCover").setAttribute('onclick', 'cancel_activity()');
    $("#pageCover").hide();
   // $("#mobileNav").hide();
    document.getElementById("pageCover").setAttribute('style', 'background-color: rgba(0,0,0,0.5);');
}
function cancel_activity(){
    $('#pageCover').hide();
    $('.confirm-box').hide();
}
