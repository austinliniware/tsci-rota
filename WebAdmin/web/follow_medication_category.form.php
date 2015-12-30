<tr>
  <td class="N_title">分類名稱：</td><td class="N_title" colspan="7">
    <input type="text" name="name" size="60">
  </td>
</tr>
<tr>
  <td class="N_title">上層分類：</td><td class="N_title" colspan="7">
    <select name="parent_id">
    <option value="0">第一層</option>
    <?php 
    $parent_category = $webdb->getList("select * from _web_follow_medication_category where parent_id=0 order by sort");
    foreach($parent_category as $rs)
    {
    ?>
    <option value="<?php echo $rs["id"];?>"><?php echo $rs["name"];?></option>
    <?php 
    }
    ?>
    </select>
  </td>
</tr>
<tr>
  <td class="N_title">排序：</td><td class="N_title" colspan="7">
    <input type="text" name="sort" size="4" value="100">
  </td>
</tr>
<tr>
  <td class="N_title">是否啟用：</td><td class="N_title" colspan="7">
    <label><input type="radio" name="is_show" value="1" checked>是</label>&nbsp;&nbsp;
    <label><input type="radio" name="is_show" value="0">否</label>
  </td>
</tr>

<script>
function checkForm(form){
	var msg='';
	if(msg){
		alert(msg);
		return false;
	}else{
	    return true;
	}
}
</script>
