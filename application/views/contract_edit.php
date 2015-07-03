<?php
echo '
<form class="form-horizontal" role="form" action="" method="POST">
	<legend>Редактирование данных договора № <strong>'.$data->nomer.' от '.$data->date.'</strong></legend>
	<div class="form-group">
		<label class="col-sm-3 control-label">Номер договора</label>
		<div class="col-sm-8">
			<input title="Введите номер договора" placeholder="Введите номер договора" class="form-control" name="nomer"
			value="'.$data->nomer.'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Дата заключения договора</label>
		<div class="col-sm-8">
			<input required type="date" class="form-control" title="Введите дату заключения договора" name="date"
			max="2020-01-01" min="2000-01-01" value="'.$data->date.'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Тип договора</label>
		<div class="col-sm-8">
			<select class="form-control" name="id_type_dog">';
                foreach ($data::type_contract() as $k=>$v) {
                    echo '<option value="'.$k.'"';
                    if ($k == $data->id_type_dog) {echo ' selected ';}
                    echo '>'.$v.'</option>';
                }
                echo '
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Название компании</label>
		<div class="col-sm-8">
			<select class="form-control" name="name">';
    foreach ($data->company_name() as $k=>$v) {
        echo '<option value="'.$k.'"';
        if ($data->company_name()[$data->company_id]==$v) echo ' selected ';
        echo ' >' . $v . '</option>';
    }
                echo '
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Договор с...</label>
		<div class="col-sm-8">
			<input required type="date" class="form-control" title="Договор действителен с..."
			name="date-s" max="2020-01-01" min="2000-01-01" value="'.$data->date_s.'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Договор по...</label>
		<div class="col-sm-8">
			<input required type="date" class="form-control" title="Договор действителен до..."
			name="date-po" max="2020-01-01" min="2000-01-01" value="'.$data->date_po.'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Дата акта</label>
		<div class="col-sm-8">
			<input type="date" class="form-control" title="Дата акта" name="date-akt"
			max="2020-01-01" min="2000-01-01" value="'.$data->date_akt.'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Название счёта</label>
		<div class="col-sm-8">
			<input type="radio" name="bank" value="1"';
			if ($data->bank != 2) {echo ' checked';}
			echo '> '.$data->bank_name()['1'].'<br />
			<input type="radio" name="bank" value="2"';
			if ($data->bank == 2) {echo ' checked';}
			echo '> '.$data->bank_name()['2'].'</div>
	    </div>';
	echo '<div class="form-group">
		<label class="col-sm-3 control-label">Цена договора в месяц</label>
		<div class="col-sm-8">
			<input class="form-control" title="Цена договора в месяц" placeholder="Введите цену договора в месяц"
			name="price" value="'.$data->price.'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Номер помещения</label>
		<div class="col-sm-8">
			<input class="form-control" title="Номер помещения" placeholder="Введите номер помещения"
			name="number" value="'.$data->number.'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Площадь помещения в м<sup>2</sup></label>
		<div class="col-sm-8">
			<input class="form-control" title="Номер помещения" placeholder="Введите номер помещения"
			name="m2" value="'.$data->m2.'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Примечания</label>
		<div class="col-sm-8">
			<textarea class="form-control" title="Примечания" placeholder="Укажите примечания"
			name="prim" rows="4">'.$data->prim.'</textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-5">
			<input type="hidden" name="id" value="'.$data->id.'">
			<button type="submit" class="btn btn-success" name="button" value="save">Сохранить</button>
			<button type="submit" class="btn btn-success" name="button" value="close">Сохранить и закрыть</button>
			<a href="/contract/all"><button type="button" class="btn btn-default">Закрыть</button></a>
		</div>
	</div>
</form>
<br /><br />';