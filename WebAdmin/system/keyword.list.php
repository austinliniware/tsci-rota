  <div class="search">
	  <a href="index.php?type=<?=$_GET['type']?>&do=info&cn=<?=$className?>">新增關鍵字</a>
  </div>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" class="T_title">關鍵字名稱</th>
      <th scope="col">頁面Title</th>
      <th scope="col">Meta Keywords</th>
      <th scope="col">Meta Description</th>
      <th scope="col">排序</th>
      <th scope="col">操作</th>
    </tr>
    <?foreach($list as $val){?>
    <tr class="Ls2">
      <td class="N_title"><?=$val['name']?></td>
      <td class="N_title"><?=substrs($val['page_title'],15)?>&nbsp;</td>
      <td class="N_title"><?=substrs($val['meta_keywords'],15)?>&nbsp;</td>
      <td class="N_title"><?=substrs($val['meta_description'],15)?>&nbsp;</td>
      <td><input postType="<?=$_GET['cn']?>" postId="<?=$val['id']?>" name="sort" type="text" size="4" value="<?=$val['sort']?>"></td>
      <td class="E_bd">
      	<a href="index.php?type=<?=$_GET['type']?>&do=info&cn=<?=$className?>&id=<?=$val['id']?>">編輯</a> | 
      	<a href="javascript:;" onclick="delFun('<?=$className?>','<?=$val['id']?>')">刪除</a>
      </td>
    </tr>
    <?}?>
  </table>
