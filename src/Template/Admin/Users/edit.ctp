<h1>Edit user</h1>
<?php 
	$isUpdate = !empty($user->userId) ? true : false;
	$btnVal = "regist";
	$btnName = "Add";
	if ($isUpdate) {
		$btnName = 'Edit';
		$btnVal = "edit";
	} 
	echo $this->Form->create($user, ['url' => ['action' => 'edit']]);
	
?>
<fieldset>
	<?php echo $this->Form->hidden('userId') ?>
	<table>
		<tr>
			<th>User Name</th>
			<td>
				<?php 
				echo $this->Form->text('userName', array('id' =>'userName'));
				echo $this->Form->error('userName');
				?>
			</td>
		</tr>
		<tr>
			<th>Email</th>
			<td>
				<?php 
				echo $this->Form->text('email', array('id' => 'email'));
				echo $this->Form->error('email');
				?>
			</td>
		</tr>
		<tr>
			<th>Password</th>
			<td>
				<?php 
				echo $this->Form->password('password', array('id' => 'password'));
				echo $this->Form->error('password');
				?>
			</td>
		</tr>
		<tr>
			<th>Birthday</th>
			<td>
				<?php 
				echo $this->Form->text('birthDay', array('id' => 'birthDay'));
				echo $this->Form->error('birthDay');
				?>
			</td>
		</tr>
	</table>
</fieldset>

<?php
	echo $this->Form->button($btnName, array('div' => false, 'name' => $btnName, 'value' => $btnVal, 'type' => 'submit'));
	echo $this->Form->button('Reset', array('div' => false, 'name' => 'Reset', 'type' => 'reset'));
?>
<?php echo $this->Form->end()?>