<h1>Please login</h1>
<?php
echo $this->Form->create('', ['url' => ['controller' => 'Logins', 'action' => 'login']]);
?>
<table>
	<tr>
		<th>Email</th>
		<td><?php echo $this->Form->text('email',['id' => 'email'])?></td>
	</tr>
	<tr>
		<th>Password</th>
		<td><?php echo $this->Form->password('password', ['id' => 'password'])?></td>
	</tr>
	
</table>
<?php 
echo $this->Form->button('Submit', ['div' => false, 'name' => 'submit', 'type' => 'Submit']);
echo $this->Form->end();
?>