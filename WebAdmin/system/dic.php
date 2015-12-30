 <h1 class="title"><span>字典管理</span></h1>
 <div class="pidding_5">
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" width="10%" class="T_title">字典名</th>
      <th scope="col">值</th>
    </tr>
    <?
    $class=new dic();
    $class->permCheck=false;
    $list=$class->getArray();
    $pageCtrl=$class->getPageInfoHtml();
    foreach($list as $val){
    	$class=new dic_item();
    	$class->setDic($val['id']);
    	$dicitem=$class->getArray();
    ?>
    <tr>
      <td class="N_title"><?=$val['name']?></td>
      <td>
      <?foreach($dicitem as $dt){?>
      	<input <?if($dt['sys_tag']){?>disabled="disabled"<?}?> class="none" <?if($val['sys_tag']){?>nodel="yes"<?}?> action="edit" post="dic_item" id="<?=$dt['id']?>" size="6" name="name" value="<?=$dt['name']?>">
      <?}?>
      <?if(!$val['sys_tag']){?><input action="add" post="dic_item" params="dicid:<?=$val['id']?>" size="6" name="name" value=""><?}?>
      </td>
    </tr>
    <?}?>
  </table>
  <div class="news-viewpage"><?=$pageCtrl?></div>
  </div>
<!-- 
  <input style="display:none;" class="none" action="edit" post="dic_item" id="copy" size="6" name="name" value="">
 -->
<script>
$(document).ready(function (){
	$('input[action=edit]').mouseover(function (){
		$(this).attr('class','');
	})
	$('input[action=edit]').mouseout(function (){
		$(this).attr('class','none');
	})
	$('input[post]').focus(function (){
		$(this).attr('oldval',$(this).val());
	})
	$('input[post]').blur(function (){
		if($(this).attr('oldval')!=$(this).val()){
			postCmd($(this),'ajaxOver');
			$(this).attr('oldval',$(this).val());
		}
	})
	$('input[post]').keyup(function (key){
		if(key.keyCode==13) $(this).blur();
		if(key.keyCode==46 && $(this).attr('action')=='edit' && !$(this).attr('nodel')){
			delFun('dic_item',$(this).attr('id'),'delOver');
			$(this).remove();
		}
	})
})
function ajaxOver(str){
	eval('var json='+str);
	if(json.ok=='yes' && json.id>0){
		alert('添加成功,請繼續添加或者刷新頁面查看結果');
	}
}
function delOver(str){
	
}
</script>