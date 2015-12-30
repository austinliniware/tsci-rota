	<table cellspacing="0" cellpadding="0" class="Admin_L">
		<tr>
			<th scope="col" class="T_title">菜單</td>
			<th scope="col">瀏覽</td>
			<th scope="col">添加</td>
			<th scope="col">修改</td>
			<th scope="col">刪除</td>
		</tr>
		<?foreach($permission as $p){?>
		<tr class="Ls2">
			<td class="N_title"><?=$p['title']?></td>
			<td>
				<input type="checkbox" name="perm[<?=$p['id']?>][s_tag]" value="1" <?if($perm[$p['id']]['s_tag']==1) echo 'checked'?>>可瀏覽
			</td>
			<td>
				<input type="checkbox" name="perm[<?=$p['id']?>][a_tag]" value="1" <?if($perm[$p['id']]['a_tag']==1) echo 'checked'?>>可添加
			</td>
			<td>
				<input type="checkbox" name="perm[<?=$p['id']?>][e_tag]" value="1" <?if($perm[$p['id']]['e_tag']==1) echo 'checked'?>>可修改
			</td>
			<td>
				<input type="checkbox" name="perm[<?=$p['id']?>][d_tag]" value="1" <?if($perm[$p['id']]['d_tag']==1) echo 'checked'?>>可刪除
			</td>
		</tr>
		<?}?>
	</table>
