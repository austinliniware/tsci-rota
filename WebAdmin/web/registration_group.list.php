<div class="search">
<a style="margin-left:30px;" href="index.php?type=<?=$_GET['type']?>&do=info&cn=<?=$className?>&langid=<?=$_SESSION['langid']?>">新增資料</a>
  </div>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" class="T_title">群組名稱</th>
      <th scope="col" width="50" style="text-align: center;">排序</th>
      <th scope="col" class="N_title" width="85"><label>是否啟用</label></th>
      <th scope="col" width="70" style="text-align: center;">操作</th>
    </tr>
    <?php
    foreach($list as $val){
	?>
    <tr class="Ls2">
      <td class="N_title"><?=$val['name']?></td>
      <td><input postType="<?=$_GET['cn']?>" postId="<?=$val['id']?>" name="sort" type="text" style="width:35px" value="<?=$val['sort']?>"></td>
      <td class="N_title"><input type="checkbox" value="1" name="is_show" product_id="<?php echo $val["id"];?>" <?php if($val["is_show"]==1)echo "checked";?>></td>
      <td class="E_bd">
      	<a href="index.php?type=<?=$_GET['type']?>&do=info&cn=<?=$className?>&id=<?=$val['id'];?>&langid=<?=$language_id?>">編輯</a>
		<!-- | 
      	<a href="javascript:;" onclick="delFun('<?=$className?>','<?=$val['id']?>')">刪除</a>-->
      </td>
    </tr>
    <?php
    }
    ?>
  </table>
  <script>
$(document).ready(function(){
	/*
	 *是否啟用
	*/
	$("input[name=is_show][type=checkbox]").click(function(){
		var lock=$("#checkbox_lock").attr("checked");
		var op=false;
		if(lock){
			op=true;
		}else{
			if($(this).attr("checked")){
				if(confirm("確定啟用嗎？")){
					op=true;
				}else{
					op=false;
					$(this).attr("checked",false);
				}
			}else{
				if(confirm("確定不啟用嗎？")){
					op=true;
				}else{
					op=false;
					$(this).attr("checked",true);
				}
			}
		}
		var is_show=0;
		if($(this).attr("checked"))is_show=1;
		var id=$(this).attr("product_id");
		if(op){
			$.ajax({
			   type: "POST",
			   url: "command.php?action=edit&type=<?php echo $_GET['cn']?>&id="+id,
			   data: "is_show="+is_show,
			   success: function(msg){}
			}); 
		}
		
	});
}); 
</script>
