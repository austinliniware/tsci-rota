<tr>
  <td class="N_title" width="180">醫院編號：</td><td class="N_title" colspan="7">
  	<input type="text" name="site_no" size="60" value="">
  </td>
</tr>
<tr>
  <td class="N_title" width="180">醫院名稱：</td><td class="N_title" colspan="7">
    <input type="text" name="name" size="60" value="">
  </td>
</tr>
<tr>
  <td class="N_title" width="180">計畫主持人：</td><td class="N_title" colspan="7">
    <input type="text" name="project_leader" size="60" value="">
  </td>
</tr>
<tr>
  <td class="N_title">排序：</td><td class="N_title" colspan="7">
  <?php $count =getCount('_web_'.$_GET['cn'])?>
    <input type="text" name="sort" size="4" value="<?=$count+100?>">
  </td>
</tr>
<tr>
	<td class="N_title">是否啟用：</td><td class="N_title" colspan="7">
		<label><input type="radio" name="is_show" value="0">否</label>&nbsp;&nbsp;
		<label><input type="radio" name="is_show" value="1" checked>是</label>
	</td>
</tr>
