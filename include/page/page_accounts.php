<?phpif(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");$color1='#aaa';$color2='#ccc';if($page*$per_page>$total) $less=($page*$per_page)-$total;else $less=0;$first=($page-1)*$per_page+1;$last=($page*$per_page-$less);$num=$last-$first+1;?><html><head><meta http-equiv="Content-type" content="text/html;charset=UTF-8" /><META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"><META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"><META HTTP-EQUIV="EXPIRES" CONTENT="0"><link href="../css/list.css" media="screen" rel="stylesheet" type="text/css" /><title>Accounts</title><style></style><script>var del_all_toggle_stat=false;var tmp;function highlight(row) {tmp=row.style.background;row.style.background="#fff";}function unhighlight(row) {row.style.background=tmp;}function delete_click(id, checked) {if(!checked) normal(id);else red(id);}function green(id) {tmp=document.getElementById('row'+id).style.background="green";}function red(id) {tmp=document.getElementById('row'+id).style.background="red";}function normal(id) {if(id%2) tmp=document.getElementById('row'+id).style.background='<?php echo $color1 ?>';else tmp=document.getElementById('row'+id).style.background='<?php echo $color2 ?>';}function check_all(action) {	<?php	echo "first=$first;\n";	echo "	num=$num;\n";	?>	for(i=first; i<first+num; i++) {		obj=document.getElementById(action+i);		if(del_all_toggle_stat) {			obj.checked=false;			normal(i-first+1);		}		else {			obj.checked=true;			red(i-first+1);		}	}	del_all_toggle_stat=!del_all_toggle_stat;	//if(del_all_toggle_stat) document.getElementById('check_all2').value='Unselect all';	//else document.getElementById('check_all2').value='Select all';}function is_digit(e) {	code = e.keyCode ? e.keyCode : e.which;	if(code<48 || code>57) return false;	else return true;}function validate_goto() {<?phpecho '	last_page=', ceil($total/$per_page), ";\n";?>	page=document.getElementById('page').value;	if(page<1 || page>last_page ) {		alert('Page number must be between (including) 1 and '+last_page+'.');		document.getElementById('page').value='';		return false;	}	else return true;}</script></head><body bgcolor="#7587b0"><center><form action="" method="post" name="accounts_form"><?phpecho 'Records ', $first, ' - ', $last, ' of ', $total;echo '<table border cellpadding="3">';echo '<input type="hidden" name="antixsrf_token" value="';echo $_COOKIE['reg8log_antixsrf_token'];echo '">';require_once $index_dir.'include/func/duration2friendly_str.php';echo '<tr style="background: brown; color: #fff"><th></th>';echo '<th>';echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=uid&sort_dir=";if($sort_by=='uid' and $sort_dir=='asc') echo 'desc';else echo 'asc';echo "'>Uid</a>";if($sort_by=='uid') {	echo '&nbsp;';	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';	else echo '<img src="../image/sort_desc.gif">';}echo "</th>";echo '<th>';echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=username&sort_dir=";if($sort_by=='username' and $sort_dir=='asc') echo 'desc';else echo 'asc';echo "'>Username</a>";if($sort_by=='username') {	echo '&nbsp;';	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';	else echo '<img src="../image/sort_desc.gif">';}echo "</th>";echo '<th>';echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=gender&sort_dir=";if($sort_by=='gender' and $sort_dir=='asc') echo 'desc';else echo 'asc';echo "'>Gender</a>";if($sort_by=='gender') {	echo '&nbsp;';	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';	else echo '<img src="../image/sort_desc.gif">';}echo "</th>";echo '<th>';echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=email&sort_dir=";if($sort_by=='email' and $sort_dir=='asc') echo 'desc';else echo 'asc';echo "'>Email</a>";if($sort_by=='email') {	echo '&nbsp;';	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';	else echo '<img src="../image/sort_desc.gif">';}echo "</th>";echo '<th>';echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=timestamp&sort_dir=";if($sort_by=='timestamp' and $sort_dir=='asc') echo 'desc';else echo 'asc';echo "'>Account creation</a>";if($sort_by=='timestamp') {	echo '&nbsp;';	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';	else echo '<img src="../image/sort_desc.gif">';}echo "</th>";if($log_last_login) {	echo '<th>';	echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=last_login&sort_dir=";	if($sort_by=='last_login' and $sort_dir=='asc') echo 'desc';	else echo 'asc';	echo "'>Last login</a>";	if($sort_by=='last_login') {		echo '&nbsp;';		if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';		else echo '<img src="../image/sort_desc.gif">';	}	echo "</th>";}if($log_last_activity) {	echo '<th>';	echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=last_activity&sort_dir=";	if($sort_by=='last_activity' and $sort_dir=='asc') echo 'desc';	else echo 'asc';	echo "'>Last activity</a>";	if($sort_by=='last_activity') {		echo '&nbsp;';		if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';		else echo '<img src="../image/sort_desc.gif">';	}	echo "</th>";}if($log_last_logout) {	echo '<th>';	echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=last_logout&sort_dir=";	if($sort_by=='last_logout' and $sort_dir=='asc') echo 'desc';	else echo 'asc';	echo "'>Last logout</a>";	if($sort_by=='last_logout') {		echo '&nbsp;';		if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';		else echo '<img src="../image/sort_desc.gif">';	}	echo "</th>";}echo '<th>';echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=banned&sort_dir=";if($sort_by=='banned' and $sort_dir=='asc') echo 'desc';else echo 'asc';echo "'>Banned</a>";if($sort_by=='banned') {	echo '&nbsp;';	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';	else echo '<img src="../image/sort_desc.gif">';}echo "</th>";echo '<th  class="admin_action">Delete</th></tr>';$i=0;$r=false;while($rec=$reg8log_db->fetch_row()) {	if(!$r) echo '<tr align="center" style="background: ', $color1,'" onmouseover="highlight(this);" onmouseout="unhighlight(this);"';	else echo '<tr align="center" style="background: ', $color2,'" onmouseover="highlight(this);" onmouseout="unhighlight(this);"';	$i++;	echo ' id="row', $i, '">';	$r=!$r;	$row=($page-1)*$per_page+$i;	echo '<td>', $row, '</td>';	echo '<td>', $rec['uid'], '</td>';	echo '<td>', htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8'), '</td>';	echo '<td>', $rec['gender'], '</td>';	echo '<td>', $rec['email'], '</td>';	echo '<td>', duration2friendly_str(time()-$rec['timestamp'], 2), ' ago', '</td>';	if($log_last_login) {		if($rec['last_login']) echo '<td>', duration2friendly_str(time()-$rec['last_login'], 2), ' ago', '</td>';		else echo '<td>N/A</td>';	}	if($log_last_activity) {		if($rec['last_activity']) echo '<td>', duration2friendly_str(time()-$rec['last_activity'], 2), ' ago', '</td>';		else echo '<td>N/A</td>';	} 	if($log_last_logout) {		if($rec['last_logout']) echo '<td>', duration2friendly_str(time()-$rec['last_logout'], 2), ' ago', '</td>';		else echo '<td>N/A</td>';	}	if($rec['banned']) echo '<td>Yes</td>';	else echo '<td>No</td>';	echo '<td><input type="checkbox" name="', $rec['auto'], '" id="del', $row, '" value="del" onclick="delete_click(', $i, ', ', 'this.checked)"></td>';	echo '</tr>';}echo '<tr align="center"';if(!$r) echo ' style="background: ', $color1;else echo ' style="background: ', $color2;echo '">';$colspan=7;if($log_last_activity) $colspan++;if($log_last_logout) $colspan++;if($log_last_login) $colspan++;echo "<td colspan=\"$colspan\"", ' align="left"><input type="submit" value="Delete selected accounts" style="color: red;" name="delete"></td><td><input type="button" onclick="check_all(\'del\')" value="All" disabled id="check_all2"></td></tr>';echo '</table>';echo '<script>';echo "\ndocument.getElementById('check_all2').disabled=false;\n";echo '</script>';require $index_dir.'include/page/page_gen_paginated_page_links.php';if($total>$per_pages[0]) {	if($total<=$per_page) echo '<br>';	echo '<br>Records per page: <select name="per_page" onchange="document.accounts_form.change_per_page.click()">';	foreach($per_pages as $value) {		if($value!=$per_page) echo "<option>$value</option>";		else echo "<option selected>$value</option>";	}	echo '</select>&nbsp;<input type="submit" value="Show" name="change_per_page" style="display: visible">';	echo  '<script>	document.accounts_form.change_per_page.style.display="none";	</script>';}?></form><a href="index.php">Admin operations</a><br><br><a href="../index.php">Login page</a></center><?phprequire $index_dir.'include/page/page_foot_codes.php';?></body></html>