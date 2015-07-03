<?php
	echo '
			<div class="form-horizontal">
				<legend>Удаление договора №'.$data->nomer.' от '.$data->date.'</legend>
				<h3 class="text-center">Вы действительно хотите <strong><span class="text-red">удалить</span> договор № '.$data->nomer.'
                от '.$data->date.'</strong>, заключённый с '.$data->company_name()[$data->company_id].' ?</strong></h3>
                <p class="text-center red">Это действие нельзя отменить!</p>
				<div class="form-group">
					<div class="col-md-6 text-center">
					    <form class="form-horizontal" role="form" action="" method="POST">
					        <input type="hidden" name="id" value="'.$data->id.'">
			                <button type="submit" name="button" class="btn btn-danger">Да, удалить договор</button>
			            </form>
					</div>
					<div class="col-md-6 text-center">
					    <a href="../edit/'.$data->id.'"><button type="button" class="btn btn-default">Редактировать договор</button></a>
					</div>
				</div>
			</div>
    <br /><br />';
