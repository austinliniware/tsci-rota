//提交表單
function subForm(fid){
	$('#'+fid).submit();
}
//把表單變成可以提交用的params,不支持文件
function formToParam(form){
	var params={};
	$('#'+form+' input').each(function (){
		if($(this).attr('name')){
			params[$(this).attr('name')]=$(this).val();
		}
	});
	$('#'+form+' select').each(function (){
		if($(this).attr('name')){
			params[$(this).attr('name')]=$(this).val();
		}
	});
	$('#'+form+' textarea').each(function (){
		if($(this).attr('name')){
			params[$(this).attr('name')]=$(this).val();
		}
	});
	$('#'+form+' button').each(function (){
		if($(this).attr('name')){
			params[$(this).attr('name')]=$(this).val();
		}
	});
	return params;
}
///////////////////////////////////後台部分////////////////////
/*
 * 根據值獲得select的Text
 */
function selectText(obj){
	var val=obj.val();
	var str='';
	obj.find('option').each(function (){
		if($(this).attr('value')==val) str=$(this).html();
	})
	return str;
}
/*
 * 設定修改表單的數據
 */
function editFunEmpty(obj){
	var fieldName='';
	$(':input').each(function (){
		fieldName=$(this).attr('name');
		if(fieldName && obj[fieldName] && !$(this).val()){
			switch($(this).attr('type')){
				case 'checkbox'	:
								if(obj[fieldName]>0)
									$(this).attr('checked','checked');
								break;
				case 'file'		:
								break;
				case 'radio':
								$("input[@name="+fieldName+"][@value="+obj[fieldName]+"]").attr("checked",true);
								break;
				default			:
								$(this).val(obj[fieldName]);
			}
		}
	})
}
function editFun(obj){
	var fieldName='';
	$(':input').each(function (){
		fieldName=$(this).attr('name');
		if(fieldName && obj[fieldName]){
			switch($(this).attr('type')){
				case 'checkbox'	:
								if(obj[fieldName]>0)
									$(this).attr('checked','checked');
								break;
				case 'file'		:
								break;
				case 'radio':
								$("input[@name="+fieldName+"][@value="+obj[fieldName]+"]").attr("checked",true);
								break;
				default			:
								$(this).val(obj[fieldName]);
			}
		}
	})
}
/*
 * 跳轉到某個頁面
 */
function go(url){
	window.location.href=url;
}
