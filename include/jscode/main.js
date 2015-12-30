
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
function btnTrimLeft(x) {//去除左邊的空白
while(x.substring(0,1)==" ") {
x = x.substring(1,x.length);
}
return x;
}
function btnTrimRight(x) {//去除右邊的空白
while(x.substring(x.length-1,x.length)==" ") {
x = x.substring(0,x.length-1);
}
return x;
}
function btnTrimAll(x) {//去除二邊的空白
x = btnTrimLeft(x);
x = btnTrimRight(x);
return x;
}

//check num
function checkNum(a) {
if (a.length>0) {
  var tmp=0;
  for (i=0;i<a.length;i++) {
    c=a.charAt(i);
    if ("0123456789".indexOf(c,0) < 0 ) {
      tmp+=1;
      return false;
    }
  }
  if (a=="0") {
    a="1";
    return true;
  }
  else return true;
}
else return false;
}
function isnum(num){
	//res=/^\d+$/;
	res=/^(\d+)-(\d+)$/;
	var re = new RegExp(res);
	return !(num.match(re) == null); 
}
//check email
function isEmail(str){ 
	res = /^[0-9a-zA-Z_\-\.]+@[0-9a-zA-Z_\-]+(\.[0-9a-zA-Z_\-]+)*$/; 
	var re = new RegExp(res); 
	return !(str.match(re) == null); 
}

var bName = navigator.appName;
nc = (bName == "Netscape") ? true : false;
ie = (bName == "Microsoft Internet Explorer") ? true : false;
function Check_num() {
	if(nc) document.onkeypress = keyDown;
	else if ((event.keyCode < 48 || event.keyCode > 57) && event.keyCode != 13 && event.keyCode != 46) event.returnValue = false;
}
function keyDown(e){
	var nkey=e.which;
	if (nkey >= 48 && nkey <= 57 || nkey==46 || nkey==13 || nkey==8 || nkey==0) return true;
	else return false;
}
function Cls_event(){
	document.onkeypress = "";
}
function trim(str) {
res="";
for(var i=0; i< str.length; i++) {
if (str.charAt(i) != " " && str.charAt(i) != "　") {
res +=str.charAt(i);
}
}
return res;
}//trim()

