<?php
	echo '
			<div class="form-horizontal">
				<legend>Информация о договоре</legend>
				<div class="form-group">
					<div class="col-sm-3 text-right">Номер и дата договора</div>
					<div class="col-sm-8">Договор №'.$data->nomer.' от '.$data->date.'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Тип договора</div>
					<div class="col-sm-8">'.$data::type_contract()[$data->id_type_dog].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Название компании</div>
					<div class="col-sm-8"><a href="#">'.$data->company_name()[$data->company_id].'</a></div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Договор с...</div>
					<div class="col-sm-8">'.$data->date_s.'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Договор по...</div>
					<div class="col-sm-8">'.$data->date_po;
						if ($data->date_po >= $today && $data->date_po < $m1)
							{echo ' <span class="label label-danger">договор закончится в ближайшие 30 дней</span>';}
						elseif ($data->date_po < $m3 && $data->date_po >= $m1)
							{echo ' <span class="label label-warning">договор закончится в ближайшие 3 месяца</span>';}
						elseif ($data->date_po >= $m3)
							{echo ' <span class="label label-success">действующий договор</span>';}
						else {echo ' <span class="label label-danger">договор закончился</span>';}
				echo '
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Дата акта</div>
					<div class="col-sm-8">';
                        if ($data->date_akt == 0 ) {
                            echo 'не задана';
                        }
                        else {
                            echo $data->date_akt;
                        }
                    echo '
                    </div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Название счёта</div>
					<div class="col-sm-8">'.$data->bank_name()[$data->bank].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Цена договора в месяц</div>
					<div class="col-sm-8">';
                        if ($data->price == '' || $data->price == 0) {
                            echo '-';}
                        else {
                            echo number_format($data->price, 0, ',', ' ') . ' <span class="glyphicon glyphicon-rub" aria-hidden="true"></span>';
                        }
            echo '
                         </div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Номер помещения</div>
					<div class="col-sm-8">'.$data->number.'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Площадь помещения</div>
					<div class="col-sm-8">'.$data->m2.' м<sup>2</sup></div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Примечания</div>
					<div class="col-sm-8">'.$data->prim.'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">
					    <a href="../delete/'.$data->id.'"><button type="submit" class="btn btn-danger">Удалить договор</button></a>
					</div>
					<div class="col-sm-8">
					    <a href="../edit/'.$data->id.'"><button type="button" class="btn btn-default">Редактировать</button></a>
					</div>
				</div>
			</div>
    <br /><br />';
