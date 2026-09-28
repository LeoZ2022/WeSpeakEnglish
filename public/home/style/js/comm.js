$(document).on('click', '.hambico', function () {

});


$(window).scroll(function() {
if(($('body').scrollTop()+$('html').scrollTop())>0){
    $('nav').addClass("wBG");
}else {
    $('nav').removeClass("wBG");
}
})

//手机菜单
$(document).on('click', '.hanbugrmenu', function () {

    $(".hanbugrmenu").addClass("hamenuclose")
    $("nav").removeClass("wBG").addClass("expend");
    $("nav").animate({height:"100%"},200);
    $("body").css("overflow","hidden");

/*    document.addEventListener('touchmove', function (event) {
        event.preventDefault();
    }, {
        passive: false
    });*/

});

$(document).on('click', '.hamenuclose', function () {
    $("nav").animate(
        {height:"78px"},200,function (){ $("nav").removeClass("expend");
        $(".hanbugrmenu").removeClass("hamenuclose");
            if(($('body').scrollTop()+$('html').scrollTop())>0){
                $('nav').addClass("wBG");
            }else {
                $('nav').removeClass("wBG");
            }
        }
        );
    $("body").css("overflow","auto");

/*    document.addEventListener('touchstart', function (e) {
        e.returnValue = true;
        return true;
    }, false);*/

});


$(document).on('click', '.navLink a', function () {
    $("nav").animate(
        {height:"78px"},200,function (){ $("nav").removeClass("expend");
        $(".hanbugrmenu").removeClass("hamenuclose");
            if(($('body').scrollTop()+$('html').scrollTop())>0){
                $('nav').addClass("wBG");
            }else {
                $('nav').removeClass("wBG");
            }
        }
        );
    $("body").css("overflow","auto");

/*    document.addEventListener('touchstart', function (e) {
        e.returnValue = true;
        return true;
    }, false);*/

});






$(document).on('mouseenter', '.logeduser', function () {
$(this).addClass("click");
});


$(":not('.logeduser')").on(
    'click',function (){
        $(".logeduser").removeClass("click");
    }
)
$(document).on('click', '.closex', function () {
    $("popbg").hide();
    $(".poptouch").hide();
});
$(document).on('click', '.getintouch-icon,.mgetintouch', function () {
    $("popbg").show();
    $(".poptouch").show();
});

function WeeksBetw(date1, date2) {
//这里的date1,date2都是Date对象
    var d1 = new Date(date1);
    var d2 = new Date(date2);
    var dt1 = d1.getTime();
    var dt2 = d2.getTime();
    return Math.ceil(Math.abs(dt2 - dt1) / 1000 / 60 / 60 / 24 / 7.0) +1;
}

function DaysBetw(date1, date2) {
//这里的date1,date2都是Date对象
    var d1 = new Date(date1);
    var d2 = new Date(date2);
    var dt1 = d1.getTime();
    var dt2 = d2.getTime();
    return Math.abs(dt2 - dt1) / 1000 / 60 / 60 / 24;
}