            <?
			// print_r($data);
			if ($customerInfo['group_id']==2 || $data['signature']>0) {
			if ($customerInfo['group_id']==2 && ($data['signature']==$_SESSION['customer_id'] || !$data['signature'])) $img=$customerInfo['imgurl'];
			else if ($data['signature']>0) $img=$webdb->getValue("select imgurl from _web_registration where id=".$data['signature'],'imgurl');
			?>
            <div class="doctorsSignoffBox">
			  <div class="leftBox">
				<label><input type="checkbox" value="1" <?php echo $data['signature']>0?'checked disabled':'';?> name="signature">
				Investigator’s Signature:</label><span><img src="<?php echo ($img?$img:'images/signatureImage.jpg');?>" height="30" /></span></div>
			  <div class="rightBox"> Date:
				<div class="dateBox">
				  <label class="no_01"><input type="text" name="date_added"<?php echo $data['signature']>0?' readonly':' class="datePicker"';?> value="<?php echo $data['date_added'];?>"></label>
				</div>
			  </div>
			  <?if ($customerInfo['group_id']==2 && $data['signature']==$_SESSION['customer_id']) {?>
				<div class="baseBtn" style="float:left;padding:5px 0 0 50px;">
				  <input class="cancelBtn" type="button" id="cancel_btn" value="" />
				</div>
			  <?}?>
			  <div class="clearfloat"></div>
			</div>
			<?}?>
