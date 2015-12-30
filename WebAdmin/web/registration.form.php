    <tr>
      <td class="N_title">人員群組：</td><td class="N_title" colspan="7">
        <select name="group_id">
        <option value="">請選擇</option>
        <?php 
        foreach (getList("_web_registration_group",'is_show=1 order by sort') as $vo){
        ?>
        <option value="<?php echo $vo['id']?>"><?php echo $vo['name']?></option>
        <?php }?>
        </select>
      </td>
    </tr>
    <tr>
      <td class="N_title">所屬醫院：</td><td class="N_title" colspan="7">
        <select name="hospital_id">
        <option value="">請選擇</option>
        <?php 
        foreach (getList("_web_hospital",'is_show=1 order by sort') as $vo){
        ?>
        <option value="<?php echo $vo['id']?>"><?php echo $vo['name']?></option>
        <?php }?>
        </select>
      </td>
    </tr>
	<tr>
      <td class="N_title">人員姓名：</td><td class="N_title" colspan="7">
        <input type="text" name="name" value="<?php echo $info["name"];?>" />
        <input type="hidden" name="ntype" value="1" />
      </td>
    </tr>
    <tr>
      <td class="N_title">性別：</td><td class="N_title" colspan="7">
      <label><input type="radio" name="gender" value="1" <?php echo $info["gender"]==1?'checked':'';?> /> 男 </label>
      <label><input type="radio" name="gender" value="0" <?php echo $info["gender"]=='0'?'checked':'';?> /> 女 </label>
      </td>
    </tr>
    <tr>
      <td class="N_title">登入E-Mail：</td><td class="N_title" colspan="7">
      	<input type="text" name="email" value="<?php echo $info["email"];?>" />
      </td>
    </tr>
	<?php unset($info['password']);?>
    <tr>
      <td class="N_title">密碼：</td><td class="N_title" colspan="7">
      <input type="password" name="password" value="" />(需修改時才填入)
      </td>
    </tr>
    <tr>
      <td class="N_title">聯絡電話：</td><td class="N_title" colspan="7">
      <input type="tel" name="tel" value="" />
      </td>
    </tr>
    <tr>
      <td class="N_title">聯絡手機：</td><td class="N_title" colspan="7">
      <input type="mobile" name="mobile" value="" />
      </td>
    </tr>
    <tr>
      <td class="N_title">簽名圖：</td><td class="N_title" colspan="7">
      <input type="file" name="signature" >160px × 30px或160px × 30px的倍數
    <?php 
	  if($info["imgurl"]){
		echo '<div id="showimg_1"><img src="'.$rooturl.$info["imgurl"]
		.'" height="30"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>';
	  }
	?>
      </td>
    </tr>
    <tr>
		<td class="N_title">是否啟用：</td><td class="N_title" colspan="7">
			<label><input type="radio" name="is_show" value="0">否</label>&nbsp;&nbsp;
			<label><input type="radio" name="is_show" value="1" checked>是</label>
		</td>
	</tr>
    <tr>
      <td class="N_title">註冊時間：</td><td class="N_title" colspan="7">
        <?php echo $info["add_time"];?>&nbsp;
      </td>
    </tr>
