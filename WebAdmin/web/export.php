<h1 class="title"><span>資料匯出</span></h1>
<div>

  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <!-- <tr>
      <th scope="col" class="T_title" width="100">檔案</th>
      <td class="N_title"><a href="export_all.php">下載</a></td>
    </tr> -->
	<?
	$tag_title = array(1=>"Patient Information","Admission Procedure","Admission Medication","Admission In-Hospital outcome","Follow up Post discharge","6 month Follow up","1 year Follow up","2 year Follow up","End of Registry Form");
	foreach ($tag_title as $i=>$title) {?>
    <tr>
      <th scope="col" class="T_title" width="100">檔案<?=$i;?></th>
      <td class="N_title"><a href="export.php?item=<?=$i;?>">下載 <?=$title;?></a></td>
    </tr>
	<?}?>
    <!-- <tr class="Ls2">
      <th scope="col" class="T_title">檔案2</th>
      <td class="N_title"><a href="export_2.php">下載2</a></td>
    </tr> -->
  </table>
</div>
