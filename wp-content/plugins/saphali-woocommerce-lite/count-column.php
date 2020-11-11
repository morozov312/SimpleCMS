<?
if(isset($_POST['column_count_saphali'])) {
	if(empty($_POST["clear"])) {
			if(!empty($_POST["column_count_saphali"])) {
				if(!update_option('column_count_saphali',$_POST['column_count_saphali'])) add_option('column_count_saphali',$_POST['column_count_saphali']); 
			}
		}	else delete_option('column_count_saphali');
}

?>
<form action="" method="POST">
Количество колонок: <input  value='<?php echo get_option('column_count_saphali'); ?>' type="text" name="column_count_saphali" />
<div class='clear'></div>
<input type="submit" class="button alignleft" value="Сохранить"/>
</form> 
<form action="" method="POST"><input type="hidden" name="column_count_saphali" value="1"/><input type="hidden" name="clear" value="1"/><input type="submit" class="button alignright" value="Сброс на умолчание"/></form> 