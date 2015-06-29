<?php

namespace application\controllers;

use application\core\Controller;

class Controller_dogovor extends Controller
{
	function __construct()
	{
		$this->model = new \application\models\Model_dogovor();
		$this->view = new \application\core\View();
	}

    public function action_review()
    {
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        $id = $routes[3];
        $data = $this->model->get_contract($id);
        $this->view->generate('dogovor_view.php', 'template_view.php', $data);
    }

    public function action_edit()
    {
        if (isset ($_REQUEST['button'])) {      // Если кнопка Сохранить нажата

            $data = $this->model->contract_update();


        }
        else {
            $routes = explode('/', $_SERVER['REQUEST_URI']);
            $id = $routes[3];
            $data = $this->model->get_contract($id);
            if (isset ($this->model->id)) {
                $this->view->generate('dogovor_edit.php', 'template_view.php', $data);
            }
            else {
                echo '<h2>Нет такого договора!</h2>
                <h3>Как Вы вообще сюда попали?</h3>
                <h4>Идите <a href="/dogovor/all">сюда</a> и больше не возвращайтесь!!!1</h4>';
                $this->action_all();
            }
        }
    }

    public function action_all()  // все договора, привязанные к фирмам
    {
        $query = 'SELECT *
          FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
          ORDER BY  `contract`.`c_id` ASC';
        $data = $this->model->find_all($query);
        $this->view->title = 'Список договоров';
        $this->view->generate('dogovor_all.php', 'template_view.php', $data);
    }

    public function action_actual()   // действующие договора
    {
        $query = 'SELECT *
          FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
          WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) >= 0
          ORDER BY  `contract`.`c_id` ASC';
        $data = $this->model->find_all($query);
        $this->view->title = 'Действующие договора';
        $this->view->generate('dogovor_all.php', 'template_view.php', $data);
    }

    public function action_expiry_date_30_days() // закончатся в ближайшие 30 дней
    {
        $query = 'SELECT *
            FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
            WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) < 31 AND TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) >= 0
            ORDER BY  `contract`.`c_id` ASC';
        $data = $this->model->find_all($query);
        $this->view->title = 'Список договоров, заканчивающихся в ближайшие 30 дней';
        $this->view->generate('dogovor_all.php', 'template_view.php', $data);
    }

    public function action_expiry_date_3_months() // закончатся в ближайшие 3 месяца
    {
        $query = 'SELECT *
          FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
          WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) < 93 AND TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) > 0
          ORDER BY  `contract`.`c_id` ASC';
        $data = $this->model->find_all($query);
        $this->view->title = 'Список договоров, заканчивающихся в ближайшие 3 месяца';
        $this->view->generate('dogovor_all.php', 'template_view.php', $data);
    }
}