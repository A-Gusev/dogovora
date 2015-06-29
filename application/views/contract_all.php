<?php
use \application\models\Model_contract as contract;

echo '<table id="myTable" class="tablesorter table table-hover">
	<caption><h1>'.$this->title.'</h1></caption>
	<thead>
		<tr>
			<th>№ договора</th>
			<th>Тип</th>
			<th>Компания</th>
			<th>С</th>
			<th>По</th>
			<th>Д. акта</th>
			<th>Счёт</th>
			<th>Цена в месяц</th>
			<th>№ помещения</th>
			<th>Ред.</th>
			<th>Удалить</th>
		</tr>
	</thead>
	<tbody>';
foreach ($data as $mas) {
    echo '
			<td><a href="review/'.$mas['c_id'].'">Договор №'.$mas['c_nomer'].' от '.$mas['c_date'].'</a></td>
			<td>'.contract::type_contract()[$mas["c_id_type_dog"]].'</td>
			<td><a href="../company/contract-firm.php?id='.$mas['f_id'].'">'.$mas['f_name'].'</a></td>
			<td>'.$mas['c_date-s'].'</td>
			<td>'.$mas['c_date-po'].'</td>
			<td>';
                if ($mas['c_date-akt'] == 0 ) {
                    echo 'не задана';
                }
                else {
                    echo $mas['c_date-akt'];
                }
    echo '
            </td>
			<td>'.contract::bank_name()[$mas["c_bank"]].'</td>
			<td>';
                if ($mas['c_price'] == '' || $mas['c_price'] == 0) {
                    echo '-';}
                else {
                    echo number_format($mas['c_price'], 0, ',', ' ') . ' <span class="glyphicon glyphicon-rub" aria-hidden="true"></span>';
                }
    echo '
            </td>
			<td>'.$mas['c_number'].'</td>
			<td>
				<a href="edit/'.$mas['c_id'].'"><button type="submits" class="btn btn-default"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
			</td>
			<td>
				<a href="trash/'.$mas['c_id'].'"><button type="submits" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
			</td>
		</tr>';
}

echo '
	</tbody>
</table>
<br /><br /><br />';
