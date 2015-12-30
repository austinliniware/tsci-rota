function myFunOfMouseOver(iId)
{
	var iIdNow="ID"+iId+"a";
	var nodeObject=document.getElementById(iIdNow);
	if(nodeObject) 
	{
		var infoNow=nodeObject.getAttribute("myDemo");
		var infodisplay=document.getElementById("infodisplay"); 
		infodisplay.innerHTML="<font color='#999999'>"+infoNow+"<font>";   
	}
	return false;
}
function expandRoot(sImagePathPrefix,rootID)
{
	var roootElement=document.getElementById(rootID);
	if(roootElement) 
	{
		if(roootElement.className=="Outline")
		{
			var targetId=roootElement.id+"d";
			var targetElement=document.getElementById(targetId);
			if(targetElement.style.display=="none")
			{
				targetElement.style.display="";
				roootElement.src=sImagePathPrefix+"style/tree/ofolder.gif";
			}
			else
			{
				targetElement.style.display="none";
				roootElement.src=sImagePathPrefix+"style/tree/folder.gif";
			}
		}
	}
}

function OnClickOutline(imgRootPath,iId)
{
	var targetId,srcElement,targetElement;
	srcElement=document.getElementById("ID"+iId);
	if(srcElement.className=="Outline"){
		targetId=srcElement.id+"d";
		targetElement=document.getElementById(targetId);
		if(targetElement.style.display=="none"){
			targetElement.style.display="";
			srcElement.src=imgRootPath+"style/tree/ofolder.gif";
		}else{
			targetElement.style.display="none";
			srcElement.src=imgRootPath+"style/tree/folder.gif";
		}
	}
}
function tree_menu_setNow(url,id){
	var obj=$('#'+id).find('a[href='+url+']');
	obj.css('color','#FFFFFF');
	obj=obj.parent();
	obj.css('background','#0069D2');
	if(obj.html()){
		while(obj.parent().attr('id')!=id){
			obj=obj.parent();
			obj.prevAll('div:first').find('img:last').attr('src','style/tree/ofolder.gif');
			obj.show();
		}
	}
}

function delete_menu(id){
	if(confirm('確定要刪除嗎?')){
		$.ajax({
		   type: "POST",
		   url: "delete.php",
		   data: "id="+id,
		   success: function(msg){
			window.location.href=window.location.href;
		   }
		}); 
	}
}