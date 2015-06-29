<?php

namespace application\controllers;

use application\core\Controller;

class Controller_contract extends Controller
{
	function __construct()
	{
		$this->model = new \application\models\Model_contract();
		$this->view = new \application\core\View();
	}

    public function action_review()
    {
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        $id = $routes[3];
        if ($data = $this->model->get_contract($id)) {
            $this->view->generate('contract_view.php', 'template_view.php', $data);
        }
        else {
            $this->view->redirect('/contract/all');
        }
    }

    public function action_edit()
    {
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        $id = $routes[3];
        if (isset ($_REQUEST['button'])) {      // Если кнопка Сохранить нажата
            $data = $this->model->contract_update($_REQUEST);
            if ($_REQUEST['button'] == 'save') {
                $this->view->redirect('/contract/edit/'.$id);
            }
        }
        else {
            $data = $this->model->get_contract($id);
            if (isset ($this->model->id)) {
                $this->view->generate('contract_edit.php', 'template_view.php', $data);
            }
        }
        $this->view->redirect('/contract/all');
    }

    public function action_all()  // все договора, привязанные к фирмам
    {
        $query = 'SELECT *
          FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
          ORDER BY  `contract`.`c_id` ASC';
        $data = $this->model->find_all($query);
        $this->view->title = 'Список договоров';
        $this->view->generate('contract_all.php', 'template_view.php', $data);
    }

    public function action_actual()   // действующие договора
    {
        $query = 'SELECT *
          FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
          WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) >= 0
          ORDER BY  `contract`.`c_id` ASC';
        $data = $this->model->find_all($query);
        $this->view->title = 'Действующие договора';
        $this->view->generate('contract_all.php', 'template_view.php', $data);
    }

    public function action_expiry_date_30_days() // закончатся в ближайшие 30 дней
    {
        $query = 'SELECT *
            FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
            WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) < 31 AND TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) >= 0
            ORDER BY  `contract`.`c_id` ASC';
        $data = $this->model->find_all($query);
        $this->view->title = 'Список договоров, заканчивающихся в ближайшие 30 дней';
        $this->view->generate('contract_all.php', 'template_view.php', $data);
    }

    public function action_expiry_date_3_months() // закончатся в ближайшие 3 месяца
    {
        $query = 'SELECT *
          FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
          WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) < 93 AND TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) > 0
          ORDER BY  `contract`.`c_id` ASC';
        $data = $this->model->find_all($query);
        $this->view->title = 'Список договоров, заканчивающихся в ближайшие 3 месяца';
        $this->view->generate('contract_all.php', 'template_view.php', $data);
    }
}