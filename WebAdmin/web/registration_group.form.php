<tr>
  <td class="N_title" width="180">群組名稱：</td><td class="N_title" colspan="7">
    <input type="text" name="name" size="60" value="<?php echo $info['name']?$info['name']:''?>">
  </td>
</tr>
<tr>
  <td class="N_title">排序：</td><td class="N_title" colspan="7">
  <?php $count =getCount('_web_news_category')?>
    <input type="text" name="sort" size="4" value="<?=$count+100?>">
    <input type="hidden" name="is_show" value="1" />
  </td>
</tr>
</tr>
    <tr>
      <td class="N_title">是否啟用：</td><td class="N_title" colspan="7">
        <label><input type="radio" name="is_show" value="0">否</label>&nbsp;&nbsp;
        <label><input type="radio" name="is_show" value="1" checked>是</label>
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
