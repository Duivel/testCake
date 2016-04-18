<h1>User List</h1>
<?php 
if (is_null($userList) || empty($userList)) {
	echo "No users";
} else {
?> 
<table>
	<tr>
		<th>Index</th>
		<th>User Name</th>
		<th>Birthday</th>
		<th>Email</th>
	</tr>
<?php
	$i = 1;
	foreach($userList as $user):
?>
	<tr>
		<td><?php echo $this->Html->link($i, array('controller' => 'Users', 'action' => 'edit', $user->userId))?></td>
		<td><?php echo h($user->userName)?></td>
		<td><?php echo h($user->displayDate())?></td>
		<td><?php echo h($user->email)?></td>
	</tr>
<?php
	$i++;
	endforeach;
}
?>
</table>