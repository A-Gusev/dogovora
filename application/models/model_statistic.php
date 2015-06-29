<?php

namespace application\models;
use application\core\Model;

class Model_statistic extends Model
{
    public static function contract_all()  // Вывод количества договоров, привязанных к фирмам
    {
        $query = 'SELECT COUNT(*)
              FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`';
        $model = new Model();
        return $model->menu_count($query);
    }

    public static function contract_actual()   // Вывод количества действующих договоров
    {
        $query = 'SELECT COUNT(*)
          FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
          WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) >= 0';
        $model = new Model();
        return $model->menu_count($query);
    }

    public static function contract_expiry_date_30_days() // Вывод количества договоров, заканчивающихся в ближайшие 30 дней
    {
        $query = 'SELECT COUNT(*)
            FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
            WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) < 31 AND TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) >= 0';
        $model = new Model();
        return $model->menu_count($query);
    }

    public static function contract_expiry_date_3_months() // Вывод количества договоров, заканчивающихся в ближайшие 3 месяца
    {
        $query = 'SELECT COUNT(*)
          FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
          WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) < 93 AND TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) > 0';
        $model = new Model();
        return $model->menu_count($query);
    }

    public static function company_special_control() // Вывод количества контрагентов на особом контроле
    {
        $query = 'SELECT COUNT(`f_problem`)
          FROM `firms`
          WHERE `firms`.`f_problem`= 1';
        $model = new Model();
        return $model->menu_count($query);
    }

    public static function company_count() // Вывод количества контрагентов
    {
        $query = 'SELECT COUNT(`f_id`)
          FROM `firms`';
        $model = new Model();
        return $model->menu_count($query);
    }

}