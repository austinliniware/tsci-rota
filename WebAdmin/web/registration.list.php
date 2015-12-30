  <div class="search">
	  <table>
	  	<tr>
	  		<td width="100" align="center"><a href="index.php?type=<?=$_GET['type']?>&do=info&cn=<?=$className?>">新增資料</a></td>
	  		<td>
	  			<form action="index.php" method="get" style="margin:0px;padding:0px;">
				  <input type="hidden" name="type" value="<?=$_GET['type']?>" >
				  <input type="hidden" name="ntype" value="<?=$_GET['ntype']?>" >
				  <input type="hidden" name="do" value="<?=$_GET['do']?>" >
				  <input type="hidden" name="cn" value="<?=$_GET['cn']?>" >
				  群組：
				  <select name="group_id">
					<option value="">請選擇</option>
					<?php 
					foreach (getList("_web_registration_group",'is_show=1 order by sort') as $vo){
					?>
					<option value="<?php echo $vo['id']?>"<?=$_GET['group_id']==$vo['id']?" selected":"";?>><?php echo $vo['name']?></option>
					<?php }?>
				  </select>
				  醫院：
				  <select name="hospital_id">
					<option value="">請選擇</option>
					<?php 
					foreach (getList("_web_hospital",'is_show=1 order by sort') as $vo){
					?>
					<option value="<?php echo $vo['id']?>"<?=$_GET['hospital_id']==$vo['id']?" selected":"";?>><?php echo $vo['name']?></option>
					<?php }?>
				  </select>
				  關鍵字：<input type="text" name="keywords" value="<?=$_GET['keywords']?>" >
				  <input type="submit" style="width:45px;height:22px;" name="submit" value="搜索" >
				  </form>
	  		</td>
	  		<td><!-- <a href="export.php?cn=<?=$className?>" style="margin-left:50px;">匯出</a> --></td>
	  	</tr>
	  </table>
  </div>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" class="T_title" width="80">人員名稱</th>
      <th scope="col" class="N_title" width="100">人員群組</th>
      <th scope="col" class="T_title" width="100">醫院</th>
      <th scope="col" class="N_title">E-Mail</th>
      <th scope="col" class="N_title">聯絡電話</th>
	  <th scope="col" class="N_title" width="80"><label>是否啟用</label></th>
      <th scope="col" width="70" style="text-align: center;">操作</th>
    </tr>
    <?foreach($list as $val){?>
    <tr class="Ls2">
      <td class="N_title"><?php echo $val["name"];?></td>
      <td class="N_title"><?php echo getCustomerGroupName($val['group_id']);?></td>
      <td class="N_title"><?php echo getHospitalName($val['hospital_id']);?></td>
      <td class="N_title"><?php echo $val["email"];?></td>
      <td class="N_title"><?php echo $val["tel"].($val["mobile"]?"; ".$val["mobile"]:"");?></td>
      <!--td class="N_title"><?php echo date("Y-m-d H:i:s",strtotime($val["add_time"]));?></td-->
	  <td class="N_title"><input type="checkbox" value="1" name="is_show" product_id="<?php echo $val["id"];?>" <?php if($val["is_show"]==1)echo "checked";?>></td>
      <td class="E_bd">
      	<a href="index.php?type=<?=$_GET['type']?>&do=info&cn=<?=$className?>&id=<?=$val['id']?>">查看</a> | 
      	<a href="javascript:;" onclick="delFun('<?=$className?>','<?=$val['id']?>')">刪除</a>
      </td>
    </tr>
    <?}?>
  </table>
  <script>
$(document).ready(function(){
	/*
	*是否啟用全選
	*/
	$("input[name=is_all_show][type=checkbox]").click(function(){
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
		if(op){
			$("input[name=is_show][type=checkbox]").attr("checked",$(this).attr("checked"));
			var is_show=0;
			if($(this).attr("checked"))is_show=1;
			$("input[name=is_show][type=checkbox]").each(function(){
				
				var id=$(this).attr("product_id");
				$.ajax({
				   type: "POST",
				   url: "command.php?action=edit&type=<?php echo $_GET['cn']?>&id="+id,
				   data: "is_show="+is_show,
				   success: function(msg){}
				}); 
			});
		}
	});
	
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
