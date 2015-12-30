/**
 * @author lzyy <healdream@gmail.com>
 * @copyright 2007 lzyy
 * @license GPL
 * @version 1.0.0
 */
(function($){
	$.fn.multiSelect = function(options){
		
		var opts = $.extend({},$.fn.multiSelect.defaults,options);	
		
		return this.each(function(){
		
			$this = $(this);
			$.fn.multiSelect.createSelectDiv($this.attr("id"),opts);
		
	});
	}
	
	//创建div
	$.fn.multiSelect.createSelectDiv = function(select_id,opts){
		
		//固定select的宽，以免被撑大
		$("#"+select_id).width($("#"+select_id).width());
		
		//取得select的jquery对象
		var $obj=$("#"+select_id);
		//取得该select的坐标对象
		var offset = $obj.offset();
		//如果是IE的话将该div往上移一个像素
		if($.browser.msie){
			offset.top -=1;
		}
//		var objW=$obj.css('width');
//		var objM=$obj.css('margin');
		var objW=18;
		var objM=18;
		var objTop=offset.top;
		offset.top+=22;
		//开始构建div
		var div_str = '<div id="'+select_id+'_Div" style="margin:'+objM+';background:#FFFFFF none repeat scroll 0 0;border:1px solid #b1cbe3;float:left;padding-left:0;text-align:left;width:145px">'
		div_str += '<input id="'+select_id+'_Input" value="" size="16" readonly="1" type="text" style="-x-system-font:none;border-style:none;border-width:0;color:#666666;font-family:Tahoma;font-size:12px;font-size-adjust:none;font-stretch:normal;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;margin:0;padding:2px;background:#FFFFFF none repeat scroll 0 0;display:block;float:left;margin:0;" />';
		div_str += '<span style="background:#FFFFFF none repeat scroll 0 0;display:block;float:right;margin:0;"><img id="'+select_id+'_Img" src="/images/s_bt.gif" border="0" /></span>';
		div_str += '</div>';
		div_str += '<div id="'+select_id+'_Drop" style="display: none;width:'+(opts.width+20)+'px;top:'+offset.top+'px;left:'+offset.left+'px;background:#FFFFFF none repeat scroll 0 0;border:1px solid #666666;font-family:tahoma;opacity:0.95;padding:1px;position:absolute;z-index:10000;">';
		if(opts.iframe && $.browser.msie) div_str += '<iframe src="javascript:false" style="display:none;position:absolute; visibility:inherit;border:0px; top:0px; left:-1px; width:'+(opts.width+20)+'; height:100%; z-index:-1;-moz-opacity:0; filter=\'progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)\';"></iframe>';
		div_str += $.fn.multiSelect.createTrTd(select_id);
		div_str += '<div class="border:1px solid #FFFFFF;cursor:default;line-height:25px;padding:3px;font-size: 12px;"><img id="'+select_id+'_ok" src="/images/ok.gif" width="29" height="16" hspace="3"/></div>';
		div_str += '</div>';

		//添加到页面上
		$obj.after($(div_str));
		$obj.hide();
		
		//定义打开函数
		$.fn.multiSelect.opener(select_id,opts);
		//定义打开函数
		$.fn.multiSelect.mouseOverOut(select_id);
		//定义div里的checkbox点击事件
		$.fn.multiSelect.checkboxClick(select_id);
		//定义确定按钮的点击事件
		$.fn.multiSelect.okClick(select_id,opts);
	}
	
	$.fn.multiSelect.checkboxClick=function(select_id){
		//获取div的jquery对象
		$obj = $("#"+select_id+"_Drop :checkbox");
		
		//定义点击事件
		$obj.click(function(){
			//这个算法较之前的有所改进，判断起来更加方便，效率也更高
			$checked_obj = $("#"+select_id+"_Drop :checkbox:checked");
			var val='';
			var text='';
			for(var i=0;i<$checked_obj.length;i++){
				val += $checked_obj.eq(i).val()+",";
				text += $checked_obj.eq(i).attr("txt")+",";
			}
			val = val.substr(0,val.length-1);
			text = text.substr(0,text.length-1);
			$("#"+select_id+"_Input").val(text);
		});
	}
	
	//定义确定按钮的点击事件，点击后div消失
	$.fn.multiSelect.okClick=function(select_id,opts){
		$("#"+select_id+"_ok").click(function(){
			$("#"+select_id+"_Drop").hide('fast');
			if(opts.iframe && $.browser.msie){
				$("#"+select_id+"_Drop iframe").css("display","none").css("height",'0');
				$("#"+select_id+"_Drop iframe").css("display","none").css("height",'0');

			}
		});
	}
		
	//定义打开div函数
	$.fn.multiSelect.opener = function(select_id,opts){
		$("#"+select_id+"_Img").click(function(){
			$("#"+select_id+"_Drop").show('fast');
			if(opts.iframe && $.browser.msie){
				$("#"+select_id+"_Drop iframe").css("display","block").css("height",$("#"+select_id+"_Drop").height());
			}
		});
	}
	
	$.fn.multiSelect.mouseOverOut = function(select_id){
		$("#"+select_id+"_Drop [mouse]").mouseover(function (){
			$(this).css('background','#FFF2E6 none repeat scroll 0 0');
			$(this).css('border','1px solid #FF9900');
		})
		$("#"+select_id+"_Drop [mouse]").mouseout(function (){
			$(this).css('background','');
			$(this).css('border','1px solid #FFFFFF');
		})
	}
	
	//创建tr和td
	$.fn.multiSelect.createTrTd = function(select_id){
		var $child = $("#"+select_id);
		var childLength = $child.children().length;
		var trtd = "";
		for(var i=0;i<childLength;i++){
			text = $child.children().eq(i).text();
			val = $child.children().eq(i).val();
			trtd += '<div mouse="true" style="border:1px solid #FFFFFF;cursor:default;line-height:20px;padding:0px;font-size: 12px;"><input type="checkbox" name="'+select_id+'[]" value="'+val+'" txt="'+text+'" /> '+text+'</div>';
		}
		return trtd;
	}

	$.fn.multiSelect.defaults = {
		width:300,
		height:200,
		iframe:true
	}

})(jQuery);

