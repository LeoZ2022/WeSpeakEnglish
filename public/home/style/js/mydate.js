window.onload = function() {
//TODO begin 处理登陆用户时区
    //获得登陆用户时区与GMT时区的差值
    var exp = new Date();
    var gmtHours = -(exp.getTimezoneOffset()/60);
    console.log(gmtHours);
    setCookie('customer_timezone',gmtHours,1);
    //判断是否为夏令时
    // date = exp.format('yyyy-MM-dd HH:mm:ss');
    if(inDaylightTime(exp)){
        setCookie('inDaylightTime',1,1);
    }
}
//设置Cookie
function setCookie(c_name,value,expiredays){
    var exdate=new Date()
    exdate.setDate(exdate.getDate()+expiredays)
    document.cookie=c_name+ "=" +escape(value)+
        ((expiredays==null) ? "" : "; expires="+exdate.toGMTString())
}
//判断时间是东半球还是西半球
function isEastEarthTime(newDate)
{
    var dj= newDate.getGMTOffset(false);
    if (dj.indexOf("-") == -1){
        return true;
    } else {
        return false;
    }
}

//是否是夏令时
function inDaylightTime(date){
    var start = new Date(date.getTime());
    start.setMonth(0);
    start.setDate(1);
    start.setHours(0);
    start.setMinutes(0);
    start.setSeconds(0);
    var middle = new Date(start.getTime());
    middle.setMonth(6);
    // 如果年始和年中时差相同，则认为此国家没有夏令时
    if ((middle.getTimezoneOffset() - start.getTimezoneOffset()) == 0)
    {
        return false;
    }
    var margin = 0;
    if (this.isEastEarthTime(date)) {
        margin = middle.getTimezoneOffset();
    } else {
        margin = start.getTimezoneOffset();
    }
    if (date.getTimezoneOffset() == margin) {
        return true;
    }
    return false;
}
//DONE end