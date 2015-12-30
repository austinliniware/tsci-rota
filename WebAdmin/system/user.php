<?php
$admin=new admin();
$userlist=$admin->getList();
$pageCtrl=$admin->getPageInfoHTML();
?>
 <h1 class="title"><span>用戶列表</span></h1>
 <div class="pidding_5">
  <div class="search">
   <a href="index.php?type=system&do=userinfo">添加一個新用戶</a>
  </div>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th scope="col" class="T_title">用戶名</th>
      <th scope="col" class="T_title">姓名</th>
      <th scope="col" style="text-align: center;">權限群組</th>
      <th scope="col" width="90" style="text-align: center;">操作</th>
    </tr>
    <?foreach($userlist as $user){?>
    <tr class="Ls2">
      <td class="N_title"><?=$user['login_name']?></td>
      <td class="N_title"><?=$user['real_name']?></td>
      <td><?=$user['gp_name']?></td>
      <td class="E_bd"><a href="index.php?type=system&do=userinfo&id=<?=$user['id']?>">編輯</a> | <a href="index.php?type=system&do=user_perm&admin_id=<?=$user['id']?>">私有權限</a><?if($user['id']>100){?> | <a href="javascript:;" onclick="delFun('admin','<?=$user['id']?>')">刪除</a><?}?></td>
    </tr>
    <?}?>
  </table>
  <div class="news-viewpage"><?=$pageCtrl?></div>
  </div>
