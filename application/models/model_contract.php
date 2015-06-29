<?php
namespace application\models;
use application\core\Model;

class Model_contract extends Model
{
    public $id = '';
    public $nomer = '';
    public $date = '';
    public $date_s = '';
    public $date_po = '';
    public $date_akt = '';
    public $bank = '1';
    public $bank_name = '';
    public $price = '0';
    public $number = '';
    public $m2 = '';
    public $id_type_dog = '';
    public $company_id = '';
    public $prim = '';
    //выше про контракт, ниже про фирму, привязанного к контракту
    public $f_id = '';
    public $id_type = '';
    public $inn = '';
    public $kpp = '';
    public $ogrn = '';
    public $address = '';
    public $doljnost = '';
    public $director = '';
    public $director_io = '';
    public $director_r = '';
    public $passport = '';
    public $tel = '';
    public $mail = '';
    public $fax = '';
    public $requisites = '';
    public $agreement = '';
    public $notes = '';
    public $problem = '';
    public $mail_s = '';
    public $mail_s_s = '';
    public $mail_s_e = '';
    public $mail_s_checked = '';


    public function get_contract($id=null)
    {
        if ($id === null) {
            return false;
        }

        /* подготавливаем запрос к БД о договоре */
        $query = "
      SELECT *
      FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
      WHERE  `contract`.`c_id`=".$id;
        $db = new Model();
        $mas=$db->find_one($query);

        if (!$mas) {
            return false;
        }

        $this->id = $mas['c_id'];
        $this->nomer = $mas['c_nomer'];
        $this->date = $mas['c_date'];
        $this->date_s = $mas['c_date-s'];
        $this->date_po = $mas['c_date-po'];
        $this->date_akt = $mas['c_date-akt'];
        $this->bank = $mas['c_bank'];
        $this->price = $mas['c_price'];
        $this->number = $mas['c_number'];
        $this->m2 = $mas['c_m2'];
        $this->id_type_dog = $mas['c_id_type_dog'];
        $this->company_id = $mas['c_company_id'];
        $this->prim = $mas['c_prim'];
        //выше про контракт, ниже про фирму, привязанного к контракту
        $this->f_id = $mas['f_id'];
        $this->id_type = $mas['f_id_type'];
        $this->inn = $mas['f_inn'];
        $this->kpp = $mas['f_kpp'];
        $this->ogrn = $mas['f_ogrn'];
        $this->address = $mas['f_address'];
        $this->doljnost = $mas['f_doljnost'];
        $this->director = $mas['f_director'];
        $this->director_io = $mas['f_director_io'];
        $this->director_r = $mas['f_director_r'];
        $this->passport = $mas['f_passport'];
        $this->tel = $mas['f_tel'];
        $this->mail = $mas['f_mail'];
        $this->fax = $mas['f_fax'];
        $this->requisites = $mas['f_requisites'];
        $this->agreement = $mas['f_agreement'];
        $this->notes = $mas['f_notes'];
        $this->problem = $mas['f_problem'];
        $this->mail_s = $mas['f_mail_s'];
        $this->mail_s_s = $mas['f_mail_s_s'];
        $this->mail_s_e = $mas['f_mail_s_e'];
        $this->mail_s_checked = $mas['f_mail_s_checked'];

        //$this->type_dog = self::type_contract()[$this->id_type_dog];
      //  $this->massiv = self::company_name();
     //  print_r($this);

        return $this;
    }

    public static function type_contract() // Вытаскивает массив со списком всех типов у договоров
    {
        $contract_type_query = "
      SELECT *
      FROM `type_contract`";
        $model = new Model();
        $array = $model->find_all($contract_type_query);
        foreach ($array as $types) {
            $result[$types['tc_id']] = $types['tc_type'];
        }
        return $result;
        // Array ( [1] => Аренда помещения [2] => Аренда площади [3] => Аренда рабочего места [4] => Прочее )
    }

    public static function company_name() // Вытаскивает массив со списком всех компаний
    {
        $company_name_query = "
      SELECT `firms`.`f_id` , `firms`.`f_name`
      FROM `firms`
      ORDER BY `firms`.`f_name` ASC";
        $model = new Model();
        $array = $model->find_all($company_name_query);
        foreach ($array as $types) {
            $result[$types['f_id']] = $types['f_name'];
        }
        return $result;
        // Array ( [57] => Алайкина Т.Н. [4] => Волошин Сергей Викторович [61] => Гузев А.О.
        // [39] => Закрытое акционерное общество «Ариста - Инвест» [58] => Закрытое акционерное общество «Матрикс»
        // [40] => Закрытое акционерное общество «Титр- энерго» [19] => Закрытое акционерное общество "Ропнет"
    }

    public static function bank_name()
    {
        $bank_query = "
      SELECT `settings`.`s_name_bank_account-1`, `settings`.`s_name_bank_account-2`
	  FROM `settings`
	  WHERE `settings`.`s_id`=1";
        $model = new Model();
        $array = $model->find_one($bank_query);
        $array_res[1]=$array['s_name_bank_account-1'];
        $array_res[2]=$array['s_name_bank_account-2'];
//      print_r($array_res);      // Array ( [1] => Счёт в АО «Райффайзенбанк» [2] => Счёт в АО «ГЕНБАНК» )
        return $array_res;
    }

    public function contract_update(array $data) // Вытаскивает массив со списком всех типов у договоров
    {
        //забираем данные из формы
        $id=$data['id'];
        $nomer=htmlentities(trim($data['nomer']));
        $date=htmlentities(trim($data['date']));
        $contract_type=$data['id_type_dog'];
        $name=htmlentities(trim($data['name']));
        $date_s=htmlentities(trim($data['date-s']));
        $date_po=htmlentities(trim($data['date-po']));
        $date_akt=htmlentities(trim($data['date-akt']));
        $bank=$data['bank'];
        $price=htmlentities(trim($data['price']));
        $number=htmlentities(trim($data['number']));
        $m2=htmlentities(trim($data['m2']));
        $prim=htmlentities(trim($data['prim']));

        /* подготавливаем запрос к БД */
        $update_sql = "UPDATE `admin_arenda`.`contract` SET `c_nomer` = '$nomer',
        `c_date` = '$date',
        `c_date-s` = '$date_s',
        `c_date-po` = '$date_po',
        `c_date-akt` = '$date_akt',
        `c_price` = '$price',
        `c_number` = '$number',
        `c_bank` = '$bank',
        `c_m2` = '$m2',
        `c_id_type_dog` = '$contract_type',
        `c_company_id` = '$name',
        `c_prim` = '$prim'
        WHERE `contract`.`c_id` = '$id'";

        //$model = new Model();
        return $this->update($update_sql);

    }
}