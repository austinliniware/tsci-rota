<?php
if($_POST){
	group_perm::update_permission($_POST);
	$altmsg='權限修改成功';
}else{
	if(empty($_GET['admin_id'])){
		echo "<script>alert('沒有操作權限');location.href='index.php'</script>";
		exit;
	}
}

$admin=new admin();
$admin->permCheck=false;
$admin->setLimit(0,1000);
$admin=$admin->getArray();

$permission=permission::getList();
$group_perm=new group_perm();
$group_perm->pageReNum=10000;
$tmpary=$group_perm->getAdminPerm($_GET['admin_id']);
$perm=array();
foreach($tmpary as $ary){
	$perm[$ary['perm_id']]=$ary;
}
?>
<form id="editForm" method="post" onsubmit="return checkForm(this)">
 <h1 class="title"><span>權限管理</span></h1>
 <div class="pidding_5">
  <div class="search">
				<span>
					<select name="admin_id" onchange="window.location.href='<?=urlkill('admin_id')?>&admin_id='+this.value">
						<?foreach($admin as $gp){?>
						<option <?if($_GET['admin_id']==$gp['id']) echo 'selected'?> value="<?=$gp['id']?>"><?=$gp['real_name']?></option>
						<?}?>
					</select>
					<input class="sub2" id="submitBtn" type="submit" value="修改">
				</span>
  </div>
<table style="margin-top:10px;"  cellspacing="0" cellpadding="0" class="tab" width="98%">
	<tr>
		<th align="left"><input type="checkbox" id="menu_selectAll" value="1" onclick="power_selectAll('menu_selectAll','menu_item_')" >選單</th>
		<th align="left"><input type="checkbox" id="list_selectAll" value="1" onclick="power_selectAll('list_selectAll','list_')">列表</th>
		<th align="left"><input type="checkbox" id="add_selectAll" value="1" onclick="power_selectAll('add_selectAll','add_')" >新增</th>
		<th align="left"><input type="checkbox" id="edit_selectAll" value="1" onclick="power_selectAll('edit_selectAll','edit_')" >修改</th>
		<th align="left"><input type="checkbox" id="delete_selectAll" value="1" onclick="power_selectAll('delete_selectAll','delete_')" >刪除</th>
	</tr>
	<?php 
	$class = new section();
	foreach (resetArray(0,$class->getList()) as $rs){
	?>
	<tr onMouseOver="this.bgColor='#00CCFF'" onMouseOut="this.bgColor='#FFFFFF'">
		<td><input type="checkbox" name="perm_id[]" pid="<?php echo $rs['parent_id']?>" id="menu_item_<?php echo $rs["id"];?>_END" value="<?php echo $rs["id"];?>" onclick="power_selectAll('menu_item_<?php echo $rs["id"];?>_END','_item_<?php echo $rs["id"];?>_END')" <?php if($perm[$rs['id']]['perm_id']) echo 'checked'?> ><?php echo $rs["indent"]."|- ".$rs["name"];?></td>
		<td><?php if($rs["Slist"]=="0"){ echo '&nbsp;';}else{?><input type="checkbox" onclick="menu_item_list_select(<?php echo $rs["id"];?>,'list')" id="list_item_<?php echo $rs["id"];?>_END" name="perm[<?php echo $rs["id"];?>][s_tag]" value="1" <?php if($rs["Slist"]=="0") echo 'disabled="disabled"';?> <?php if($perm[$rs['id']]['s_tag']==1) echo 'checked'?> >列表<?php }?></td>
		<td><?php if($rs["Sadd"]=="0"){ echo '&nbsp;';}else{?><input type="checkbox" onclick="menu_item_list_select(<?php echo $rs["id"];?>,'add')" id="add_item_<?php echo $rs["id"];?>_END" name="perm[<?php echo $rs["id"];?>][a_tag]" value="1" <?php if($perm[$rs['id']]['a_tag']==1) echo 'checked'?>>新增<?php }?></td>
		<td><?php if($rs["Sedit"]=="0"){ echo '&nbsp;';}else{?><input type="checkbox" onclick="menu_item_list_select(<?php echo $rs["id"];?>,'edit')" id="edit_item_<?php echo $rs["id"];?>_END" name="perm[<?php echo $rs["id"];?>][e_tag]" value="1" <?php if($perm[$rs['id']]['e_tag']==1) echo 'checked'?>>修改<?php }?></td>
		<td><?php if($rs["Sdelete"]=="0"){ echo '&nbsp;';}else{?><input type="checkbox" onclick="menu_item_list_select(<?php echo $rs["id"];?>,'delete')" id="delete_item_<?php echo $rs["id"];?>_END" name="perm[<?php echo $rs["id"];?>][d_tag]" value="1" <?php if($perm[$rs['id']]['d_tag']==1) echo 'checked'?>>刪除<?php }?></td>
	</tr>
	<?php 
	}
	?>
</table>
  </div>
</form>
<script>
/*
function setSubCheck(val,checked){
	$('input[type=checkbox][pid='+val+'][name^=perm_id]').attr('checked',checked);
	$('input[type=checkbox][pid='+val+'][name^=perm_id]').each(function (){
		setSubCheck($(this).val(),$(this).attr('checked'));
	});
}
$('input[type=checkbox][name^=perm_id]').click(function (){
	setSubCheck($(this).val(),$(this).attr('checked'));
});
*/
</script>
<script language="javascript">

function power_selectAll(select_name,input_name){
	
	$("input[id='"+select_name+"']").click( function (){
		$("input[id*='"+input_name+"']").attr("checked",this.checked); 
	}); 
	
	if(select_name.substr(0,10)=="menu_item_"){
		var allCheck=true;
		$('input[id*=menu_item_]').each(function (){
			if(!$(this).attr('checked')){
				allCheck=false;
			}
		});
		$("input[id='menu_selectAll']").attr("checked",allCheck);
		item_select("list",input_name,$("input[id='"+select_name+"']").attr('checked'));
		item_select("add",input_name,$("input[id='"+select_name+"']").attr('checked'));
		item_select("edit",input_name,$("input[id='"+select_name+"']").attr('checked'));
		item_select("delete",input_name,$("input[id='"+select_name+"']").attr('checked'));
	}
}
function menu_item_list_select(id,section){
	var menu_item_select=false;
	var list_name="list_item_"+id+"_END";
	var add_name="add_item_"+id+"_END";
	var edit_name="edit_item_"+id+"_END";
	var delete_name="delete_item_"+id+"_END";
	if($('input[id*='+list_name+']').attr('checked')) menu_item_select=true;
	if($('input[id*='+add_name+']').attr('checked')) menu_item_select=true;
	if($('input[id*='+edit_name+']').attr('checked')) menu_item_select=true;
	if($('input[id*='+delete_name+']').attr('checked')) menu_item_select=true;
	$("input[id*='menu_item_"+id+"_END']").attr("checked",menu_item_select);
	var menu_selectAll_status=$("input[id='menu_selectAll']").attr("checked");
	if(menu_selectAll_status!=menu_item_select){
		var allCheck=true;
		$('input[id*=menu_item_]').each(function (){
			if(!$(this).attr('checked')){
				allCheck=false;
			}
		});
		$("input[id='menu_selectAll']").attr("checked",allCheck);
	}
	item_action(section);
}
function item_action(section){
	var itme_allCheck=true;
	$('input[id*='+section+'_item_]').each(function (){
		if(!$(this).attr('checked')){
			itme_allCheck=false;
		}
	});
	$("input[id='"+section+"_selectAll']").attr("checked",itme_allCheck);
}
function item_select(section,end_str,current_select_status){
	var itme_allCheck=true;
	$('input[id*='+section+'_item_]').each(function (){
		if(this.id==(section+end_str)){
			if(!current_select_status){
				itme_allCheck=false;
			}
		}else{
			if(!$(this).attr('checked')){
				itme_allCheck=false;
			}
		}
	});
	$("input[id='"+section+"_selectAll']").attr("checked",itme_allCheck);
}

$(document).ready(function (){
	var allCheck=true;
	$('input[id*=menu_item_]').each(function (){
		if(!$(this).attr('checked')){
			allCheck=false;
		}
	});
	$("input[id='menu_selectAll']").attr("checked",allCheck);
	
	item_action("list");
	item_action("add");
	item_action("edit");
	item_action("delete");
});
</script>