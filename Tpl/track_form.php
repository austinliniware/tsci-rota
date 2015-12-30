<form id="form1" name="form1" method="post" action="" >
<input type="hidden" name="do" value="next" />
<input type="hidden" name="finish" value="0" />
<?if ($mfuInfo['id']>0) {?><input type="hidden" name="id" value="<?=$mfuInfo['id']?>" /><?}?>
	<div class="mainPage">
      <div class="mainPage_bg">
        <div class="mainContent">
          <div class="pageBox">
            <?php require_once(ROOT_PATH.'Tpl/patient_header.html');?>
            <div class="formSetBox">
              <h2 class="titleStyle_01"><span><?=$subtitle;?> Follow up</span></h2>
              <div class="formBox monthFollowUpBox_01">
                <div class="formSection no_01">
                  <div class="formLabel">Vital Status:</div>
                  <div class="formValue">
                    <div class="no_01">
                      <label class="no_02">
                        <input type="radio" name="vital_status" <?php echo $data['vital_status']=='Dead'?'checked':'';?> value="Dead" />
                        Dead</label>
                      (&nbsp;&nbsp;Reason:
                      <?php 
                      foreach ($re_hospitalization_reason as $k=>$rea){
                      ?>
                      <label class="no_0<?php echo $k+3;?>">
                        <input type="radio" name="dead_reason" <?php echo $data['dead_reason']==$rea?'checked':'';?> value="<?php echo $rea;?>" /> <?php echo $rea;?></label>
                      <?php }?>
                      ) </div>
                    <label class="no_06">
                      <input type="radio" name="vital_status" <?php echo $data['vital_status']=='Alive'?'checked':'';?> value="Alive" />
                      Alive&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;Please complete end of registry form&nbsp;&nbsp;)</label><br>
					<label class="no_01">
                      <input type="radio" name="vital_status" <?php echo $data['vital_status']=='Lost Follow'?'checked':'';?> value="Lost Follow" />
                      Lost Follow</label><br>
					<label class="no_01">
                      <input type="radio" name="vital_status" <?php echo $data['vital_status']=='在其他醫院看診,有資料'?'checked':'';?> value="在其他醫院看診,有資料" />
                      在其他醫院看診,有資料</label><br>
					<label class="no_01">
                      <input type="radio" name="vital_status" <?php echo $data['vital_status']=='在其他醫院看診,無資料'?'checked':'';?> value="在其他醫院看診,無資料" />
                      在其他醫院看診,無資料</label><br>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_02">
                  <div class="formLabel">Re-hospitalization:</div>
                  <div class="formValue">
                    <label class="no_01">
                      <input type="radio" name="re_hospitalization" <?php echo $data['re_hospitalization']=='0'?'checked':'';?> value="0" />
                      No</label>
                    <div class="no_02">
                      <label class="no_03">
                        <input type="radio" name="re_hospitalization" <?php echo $data['re_hospitalization']==1?'checked':'';?> value="1" />
                        Yes</label>
                      <ul>
                        <li>
                        <?php 
                        foreach ($re_hospitalization_yes as $ry){
                        ?>
                          <label>
                            <input type="radio" name="re_hospitalization_yes" <?php echo $data['re_hospitalization_yes']==$ry?'checked':'';?> value="<?php echo $ry;?>" /> <?php echo $ry;?></label>
                        <?php }?>
                        </li>
                        <li><span>Reason :</span>
                        <?php 
                        foreach ($re_hospitalization_reason as $rh){
                        ?>
                          <label><input type="radio" name="re_hospitalization_reason" <?php echo $data['re_hospitalization_reason']==$rh?'checked':'';?> value="<?php echo $rh;?>" /> <?php echo $rh;?></label>
                        <?php }?>
                        </li>
                        <li><span>Start:</span>
                          <div class="dateBox">
                      <label class="no_01"><input type="text" name="re_hospitalization_start" class="datePicker"  value="<?php echo $data['re_hospitalization_start'];?>"></label>
                      </div>
                        </li>
                        <li><span>End :</span>
                          <div class="dateBox">
                      <label class="no_01"><input type="text" name="re_hospitalization_end" class="datePicker"  value="<?php echo $data['re_hospitalization_end'];?>"></label>
                      </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_03">
                  <div class="formLabel">Repeat Revascularization:</div>
                  <div class="formValue">
                    <label class="no_01">
                      <input type="radio" name="revascularization" <?php echo $data['revascularization']=='0'?'checked':'';?> value="0" />
                      No</label>
                    <div class="no_02">
                      <label class="no_03">
                        <input type="radio" name="revascularization" <?php echo $data['revascularization']==1?'checked':'';?> value="1" />
                        Yes</label>
                      (
                      <div class="dateBox">
                      <label class="no_01"><input type="text" name="revascularization_yes" class="datePicker"  value="<?php echo $data['revascularization_yes'];?>"></label>
                      </div>
                      )</div>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_04">
                  <div class="formLabel">PCI:</div>
                  <div class="formValue">
                    <label class="no_01">
                      <input type="radio" name="PCI" <?php echo $data['PCI']=='0'?'checked':'';?> value="0" />
                      No</label>
                    <div class="no_02">
                      <label class="no_03">
                        <input type="radio" name="PCI" <?php echo $data['PCI']==1?'checked':'';?> value="1" />
                        Yes</label>
                      (
                      <div class="dateBox">
                      <label class="no_01"><input type="text" name="PCI_yes_date" class="datePicker"  value="<?php echo $data['PCI_yes_date'];?>"></label>
                      </div>
                      ,
                      <label class="no_04">
                        <input type="radio" name="PCI_yes" <?php echo $data['PCI_yes']=='planned'?'checked':'';?> value="planned" />
                        planned</label>
                      <label class="no_05">
                        <input type="radio" name="PCI_yes" <?php echo $data['PCI_yes']=='unplanned'?'checked':'';?> value="unplanned" />
                        unplanned</label>
                      ) </div>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_05">
                  <div class="formLabel">CABG:</div>
                  <div class="formValue">
                    <label class="no_01">
                      <input type="radio" name="CABG" <?php echo $data['CABG']=='0'?'checked':'';?> value="0" />
                      No</label>
                    <div class="no_02">
                      <label class="no_03">
                        <input type="radio" name="CABG" <?php echo $data['CABG']==1?'checked':'';?> value="1" />
                        Yes</label>
                      (
                      <div class="dateBox">
                      <label class="no_01"><input type="text" name="CABG_yes" class="datePicker"  value="<?php echo $data['CABG_yes'];?>"></label>
                      </div>
                      )</div>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_06">
                  <div class="formLabel">Biventricular Pacing:</div>
                  <div class="formValue">
                    <label class="no_01">
                      <input type="radio" name="pacing" <?php echo $data['pacing']=='0'?'checked':'';?> value="0" />
                      No</label>
                    <div class="no_02">
                      <label class="no_03">
                        <input type="radio" name="pacing" <?php echo $data['pacing']==1?'checked':'';?> value="1" />
                        Yes</label>
                      (
                      <div class="dateBox">
                      <label class="no_01"><input type="text" name="pacing_yes" class="datePicker"  value="<?php echo $data['pacing_yes'];?>"></label>
                      </div>
                      ) </div>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_07">
                  <div class="formLabel">ICD:</div>
                  <div class="formValue">
                    <label class="no_01">
                      <input type="radio" name="ICD" <?php echo $data['ICD']=='0'?'checked':'';?> value="0" />
                      No</label>
                    <div class="no_02">
                      <label class="no_03">
                        <input type="radio" name="ICD" <?php echo $data['ICD']=='1'?'checked':'';?>  value="1" />
                        Yes</label>
                      (
                      <div class="dateBox">
                      <label class="no_01"><input type="text" name="ICD_yes" class="datePicker"  value="<?php echo $data['ICD_yes'];?>"></label>
                      </div>
                      )</div>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_08">
                  <div class="formLabel">Hb:</div>
                  <div class="formValue">
                    <label class="no_01" style="margin-right:0px;">
                      <input type="text" name="Hb[0]" value="<?php echo $data['Hb'][0];?>" onKeyPress="Check_num();" onBlur="Cls_event();" <?php echo $data['Hb_not_done']==1?'disabled':'';?> >
                      .</label>
                      <label class="no_01"><input type="text" name="Hb[1]" value="<?php echo $data['Hb'][1]?>" onKeyPress="Check_num();" onBlur="Cls_event();" <?php echo $data['Hb_not_done']==1?'disabled':'';?>>
                      mg/dl</label>
                    <label class="no_02">
                      <input type="checkbox" name="Hb_not_done" <?php echo $data['Hb_not_done']==1?'checked':'';?> value="1" />
                      Not Done</label>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_09">
                  <div class="formLabel">Sugar AC:</div>
                  <div class="formValue">
                    <label class="no_01">
                      <input type="text" name="sugar_AC" value="<?php echo $data['sugar_AC'];?>" onKeyPress="Check_num();" onBlur="Cls_event();">
                      mg/dl</label>
                    <label class="no_02">2h PC Sugar
                      <input type="text" name="2h_PC_sugar" value="<?php echo $data['2h_PC_sugar'];?>" onKeyPress="Check_num();" onBlur="Cls_event();">
                      mg/dl</label>
                    <label class="no_03">HbA1C
                      <input type="text" name="HbA1C" value="<?php echo $data['HbA1C'];?>" onKeyPress="Check_num();" onBlur="Cls_event();">
                      %</label>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_10">
                  <div class="formLabel">BUN:</div>
                  <div class="formValue">
                    <label class="no_01">
                      <input type="text" name="BUN" value="<?php echo $data['BUN'];?>" onKeyPress="Check_num();" onBlur="Cls_event();">
                      mg/dl</label>
                    <label class="no_02" style="margin-right:0px;">Serum Creatinine
                      <input type="text" name="serum_creatinine[0]" value="<?php echo $data['serum_creatinine'][0]?>" onKeyPress="Check_num();" onBlur="Cls_event();">
                      .</label>
                      <label class="no_02"><input type="text" name="serum_creatinine[1]" value="<?php echo $data['serum_creatinine'][1]?>" onKeyPress="Check_num();" onBlur="Cls_event();">
                      mg/dl</label>
                    <label class="no_03">
                      <input type="checkbox" name="BUN_not_done" <?php echo $data['BUN_not_done']==1?'checked':'';?> value="1" />
                      Not Done</label>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_11">
                  <div class="formLabel">Total Cholesterol:</div>
                  <div class="formValue">
                    <label class="no_01">
                      <input type="text" name="cholesterol" value="<?php echo $data['cholesterol'];?>" onKeyPress="Check_num();" onBlur="Cls_event();">
                      mg/dl</label>
                    <label class="no_02">HDL
                      <input type="text" name="cholesterol_HDL" value="<?php echo $data['cholesterol_HDL']?>" onKeyPress="Check_num();" onBlur="Cls_event();">
                      mg/dl</label>
                    <label class="no_03">LDL
                      <input type="text" name="cholesterol_LDL" value="<?php echo $data['cholesterol_LDL'];?>" onKeyPress="Check_num();" onBlur="Cls_event();">
                      mg/dl</label>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_12">
                  <div class="formLabel">Triglyceride:</div>
                  <div class="formValue">
                    <label class="no_01">
                      <input type="text" name="triglyceride" value="<?php echo $data['triglyceride']?>" onKeyPress="Check_num();" onBlur="Cls_event();" <?php echo $data['triglyceride_not_done']=='1'?'disabled':'';?>>
                      mg/dl</label>
                    <label>
                      <input type="checkbox" name="triglyceride_not_done" <?php echo $data['triglyceride_not_done']=='1'?'checked':'';?> value="1" />
                      Not Done</label>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_13">
                  <div class="formLabel">Uric acid:</div>
                  <div class="formValue">
                    <label class="no_01" style="margin-right:0px;">
                      <input type="text" name="uric_acid[0]" value="<?php echo $data['uric_acid'][0];?>" onKeyPress="Check_num();" onBlur="Cls_event();" <?php echo $data['uric_acid_not_done']==1?'disabled':'';?>>
                      .</label>
                      <label class="no_01"><input type="text" name="uric_acid[1]" value="<?php echo $data['uric_acid'][1];?>" onKeyPress="Check_num();" onBlur="Cls_event();" <?php echo $data['uric_acid_not_done']==1?'disabled':'';?>>
                      mg/dl</label>
                    <label>
                      <input type="checkbox" name="uric_acid_not_done" <?php echo $data['uric_acid_not_done']==1?'checked':'';?> value="1" />
                      Not Done</label>
                  </div>
                  <div class="clearfloat"></div>
                </div>
                <div class="formSection no_14">
                  <div class="formLabel">Hypoglycemia:</div>
                  <div class="formValue">
                    <label class="no_01">
                      <input type="radio" name="hypoglycemia" <?php echo $data['hypoglycemia']=='0'?'checked':'';?> value="0" />
                      No</label>
                    <div class="no_02">
                      <label class="no_03">
                        <input type="radio" name="hypoglycemia" <?php echo $data['hypoglycemia']==1?'checked':'';?> value="1" />
                        Yes</label>
                      (
                      <label class="no_04">sugar level
                        <input type="text" name="hypoglycemia_sugar_level" value="<?php echo $data['hypoglycemia_sugar_level']?>">
                        mg/dl</label>
                      ,
                      <?php 
                      foreach ($hypoglycemia_yes as $k=>$hy){
                      ?>
                      <label class="no_0<?php echo $k+5;?>">
                        <input type="radio" name="hypoglycemia_yes" <?php echo $data['hypoglycemia_yes']==$hy?'checked':'';?> value="<?php echo $hy;?>" /> <?php echo $hy;?></label>
                        <?php }?>
                      ) </div>
                  </div>
                  <div class="clearfloat"></div>
                </div>
              </div>
            </div>
            <div class="formSetBox">
              <h2 class="titleStyle_01"><span><?=$subtitle;?> Follow up</span></h2>
              <div class="formBox monthFollowUpBox_02 medicationBox">
				<?
				$i=1;
				$medication_category = $webdb->getList("select * from _web_follow_medication_category where parent_id=0 and is_show=1 order by sort");
				foreach ($medication_category as $rs) {
				$sub_medication = $webdb->getList("select * from _web_follow_medication_category where parent_id=".$rs["id"]." and is_show=1 order by sort");
				$sub_num=count($sub_medication);
				?>
                <div class="formSection no_<?=$i<10?('0'.$i):$i;?>">
                  <div class="formLabel"><?=$i.". ".$rs['name'];?>:</div>
                  <div class="formValue">
                    <label>
                      <input type="radio" name="medication[<?=$rs["id"];?>]" <?php echo $data['medication'][$rs["id"]]=='0'?'checked':'';?> value="0" onClick="checkYes('<?=$rs["id"];?>',this.value,0);" />
                      No</label>
                    <label>
                      <input type="radio" name="medication[<?=$rs["id"];?>]" <?php echo $data['medication'][$rs["id"]]==1?"checked":'';?> value="1" onClick="checkYes('<?=$rs["id"];?>',this.value,'<?=$sub_num;?>');" />
                      Yes</label> <input type="text" name="medication_txt[<?=$rs["id"];?>]" id="medication_txt<?=$rs["id"];?>" value="<?=$data['medication_txt'][$rs["id"]]?>" style="width:100px;display:<?=$data['medication'][$rs["id"]]==1?"":"none";?>;" onKeyPress="Check_num();" onBlur="Cls_event();"><span id="note_txt<?=$rs["id"];?>" style="display:<?=$data['medication'][$rs["id"]]==1?"":"none";?>;">劑量/天</span>
                    <div class="medicationItenBox" id="txt_<?=$rs["id"];?>" style="display:<?=$data['medication'][$rs["id"]]==1 && $sub_num>0?"":"none";?>;">
                      <?
					  foreach ($sub_medication as $sub) {
					  ?>
					  <label><input type="checkbox" name="sub_medication[<?=$rs["id"];?>][]" value="<?=$sub["id"];?>" <?php if(is_array($data['sub_medication'][$rs["id"]]) && in_array($sub["id"],$data['sub_medication'][$rs["id"]])) echo 'checked';?>> <?=$sub["name"];?></label>
					  <?}?>
                    </div>
                  </div>
                  <div class="clearfloat"></div>
                </div>
				<?$i++;}?>
              </div>
            </div>
			<?php require_once(ROOT_PATH.'Tpl/sign.php');?>
            <?php require_once(ROOT_PATH.'Tpl/footer_button.html');?>
          </div>
        </div>
      </div>
    </div>
</form>
<script type="text/javascript">
<!--
<?if ($ModifyPermission) {?>
$(document).ready(function(){
	$("input[name=Hb_not_done]").click(function(){
	if (this.checked) {$("input[name='Hb[0]']").attr('disabled',true); $("input[name='Hb[1]']").attr('disabled',true);$("input[name='Hb[0]']").val(''); $("input[name='Hb[1]']").val('');}
	else {$("input[name='Hb[0]']").attr('disabled',false).focus(); $("input[name='Hb[1]']").attr('disabled',false);}
	});
	$("input[name=triglyceride_not_done]").click(function(){
	if (this.checked) {$("input[name=triglyceride]").attr('disabled',true); $("input[name=triglyceride]").val('');}
	else {$("input[name=triglyceride]").attr('disabled',false).focus();}
	});
	$("input[name=uric_acid_not_done]").click(function(){
	if (this.checked) {$("input[name='uric_acid[0]']").attr('disabled',true); $("input[name='uric_acid[1]']").attr('disabled',true);$("input[name='uric_acid[0]']").val(''); $("input[name='uric_acid[1]']").val('');}
	else {$("input[name='uric_acid[0]']").attr('disabled',false).focus(); $("input[name='uric_acid[1]']").attr('disabled',false);}
	});
	$("input[name='Hb[0]'],input[name='serum_creatinine[0]'],input[name='uric_acid[0]']").keyup(function(e){
        if (e.keyCode == 190 || e.keyCode == 110) {
		var obj=$(this).attr('name').replace("[0]", "[1]");
		var str=$(this).val();
        str=str.replace(".", "");
        $(this).val(str);
		$("input[name='"+obj+"']").focus();
		}
    });
})
function checkYes(id,val,num) {
	if (val==1) {$("#medication_txt"+id+",#note_txt"+id).show().focus(); if (num>0) $("#txt_"+id).show();}
	else {$("#medication_txt"+id+",#note_txt"+id).hide().val('');$("#txt_"+id).hide();}
}
<?}?>
function checkForm() {
	// var status=0; //0 未填,1 有填
	var ckField;
	var finish = 0;
	var status = new Array();
	var msg = new Array();
	var finish_stuts = new Array();
	
	ckField=$("input[name=vital_status]:checked").val();
	if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("Vital Status is not selected!");} 
	else if (ckField!='Lost Follow' && ckField!='在其他醫院看診,無資料') {
	if (ckField=='Dead') {ckField=$("input[name=dead_reason]:checked").val();if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("Dead Reason is not selected!");} else status.push(1);}
	else {
	status.push(1);
	ckField=$("input[name=re_hospitalization]:checked").val();
	if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("Re-hospitalization is not selected!");} 
	else if (ckField==1) {
	ckField=$("input[name=re_hospitalization_yes]:checked").val();if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("Re-hospitalization planned is not selected!");} else status.push(1);
	ckField=$("input[name=re_hospitalization_reason]:checked").val();if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("Reason is not selected!");} else status.push(1);
	ckField=$("input[name=re_hospitalization_start]").val();if (ckField=='') {status.push(0);msg.push("Start date is not selected!");} else status.push(1);
	ckField=$("input[name=re_hospitalization_end]").val();if (ckField=='') {status.push(0);msg.push("End date is not selected!");} else status.push(1);
	}
	else status.push(1);
	ckField=$("input[name=revascularization]:checked").val();
	if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("Repeat Revascularization is not selected!");} 
	else if (ckField==1) {ckField=$("input[name=revascularization_yes]").val();if (ckField=='') {status.push(0);msg.push("Repeat date is not selected!");} else status.push(1);}
	else status.push(1);
	ckField=$("input[name=PCI]:checked").val();
	if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("PCI is not selected!");} 
	else if (ckField==1) {
	ckField=$("input[name=PCI_yes_date]").val();if (ckField=='') {status.push(0);msg.push("PCI date is not selected!");} else status.push(1);
	ckField=$("input[name=PCI_yes]:checked").val();if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("PCI planned is not selected!");} else status.push(1);
	}
	else status.push(1);
	ckField=$("input[name=CABG]:checked").val();
	if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("CABG is not selected!");} 
	else if (ckField==1) {ckField=$("input[name=CABG_yes]").val();if (ckField=='') {status.push(0);msg.push("CABG date is not selected!");} else status.push(1);}
	else status.push(1);
	ckField=$("input[name=pacing]:checked").val();
	if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("Biventricular Pacing is not selected!");} 
	else if (ckField==1) {ckField=$("input[name=pacing_yes]").val();if (ckField=='') {status.push(0);msg.push("Biventricular Pacing date is not selected!");} else status.push(1);}
	else status.push(1);
	ckField=$("input[name=ICD]:checked").val();
	if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("ICD is not selected!");} 
	else if (ckField==1) {ckField=$("input[name=ICD_yes]").val();if (ckField=='') {status.push(0);msg.push("ICD date is not selected!");} else status.push(1);}
	else status.push(1);
	if (!$("input[name=Hb_not_done]").is(':checked')) if ($("input[name='Hb[0]']").val()=='' && $("input[name='Hb[1]']").val()=='') {status.push(0);msg.push("Hb is Unfilled!");} else status.push(1);
	// if ($("input[name=sugar_AC]").val()=='') {status.push(0);msg.push("Sugar AC is Unfilled!");} else status.push(1);
	// if ($("input[name=2h_PC_sugar]").val()=='') {status.push(0);msg.push("2h PC Sugar is Unfilled!");} else status.push(1);
	// if ($("input[name=HbA1C]").val()=='') {status.push(0);msg.push("HbA1C is Unfilled!");} else status.push(1);
	// if (!$("input[name=BUN_not_done]").is(':checked')) {
	// if ($("input[name=BUN]").val()=='') {status.push(0);msg.push("BUN is Unfilled!");} else status.push(1);
	// if ($("input[name='serum_creatinine[0]']").val()=='' && $("input[name='serum_creatinine[1]']").val()=='') {status.push(0);msg.push("Serum Creatinine is Unfilled!");} else status.push(1);
	// }
	// if ($("input[name=cholesterol]").val()=='') {status.push(0);msg.push("Total cholesterol is Unfilled!");} else status.push(1);
	// if ($("input[name=cholesterol_HDL]").val()=='') {status.push(0);msg.push("HDL is Unfilled!");} else status.push(1);
	// if ($("input[name=cholesterol_LDL]").val()=='') {status.push(0);msg.push("LDL is Unfilled!");} else status.push(1);
	if (!$("input[name=triglyceride_not_done]").is(':checked')) if ($("input[name=triglyceride]").val()=='') {status.push(0);msg.push("Triglyceride is Unfilled!");} else status.push(1);
	if (!$("input[name=uric_acid_not_done]").is(':checked')) if ($("input[name='uric_acid[0]']").val()=='' && $("input[name='uric_acid[1]']").val()=='') {status.push(0);msg.push("Uric acid is Unfilled!");} else status.push(1);
	ckField=$("input[name=hypoglycemia]:checked").val();
	if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("Hypoglycemia is not selected!");} 
	else if (ckField==1) {
	if ($("input[name=hypoglycemia_sugar_level]").val()=='') {status.push(0);msg.push("sugar level is Unfilled!");} else status.push(1);
	ckField=$("input[name=hypoglycemia_yes]:checked").val();if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("Hypoglycemia is not selected!");} else status.push(1);
	}
	else status.push(1);
	
<?foreach ($medication_category as $key=>$rs) {?>
	ckField=$("input:checked[name='medication[<?=$rs["id"];?>]']").val();
	if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("<?=$rs["name"];?> is not selected!");} 
	else if (ckField==1) {
	ckField=$("input[name='medication_txt[<?=$rs["id"];?>]']").val(); if (ckField=='') {status.push(0);msg.push("<?=$rs["name"];?> is Unfilled!");} else status.push(1);
	<?
	$sub_medication = $webdb->getList("select * from _web_follow_medication_category where parent_id=".$rs["id"]." and is_show=1 order by sort");
	if (count($sub_medication)>0) {
	?>
	ckField=$("input:checked[name='sub_medication[<?=$rs["id"];?>][]']").val(); if (ckField=='' || typeof(ckField)=='undefined') {status.push(0);msg.push("<?=$rs["name"];?> is not selected!");} else status.push(1);
	<?
	}
	?>
	}
	else status.push(1);
<?}?>
	}
	}

	for (key in status) {
		finish+=status[key];
	}
	if (finish==status.length) finish_stuts['status']=1;
	else if (finish==0) finish_stuts['status']=0;
	else finish_stuts['status']=-1;
	finish_stuts['msg'] = msg.join("\n");
	return finish_stuts;
}
//-->
</script>