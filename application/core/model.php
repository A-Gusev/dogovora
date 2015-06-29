<?php
namespace application\core;

class Model
{
	private $link;
	public function __construct()
	{
		$host = 'localhost';
		$user = 'root';
		$password = 'root';
		$db = 'admin_arenda';
		$this->link = mysqli_connect($host, $user, $password, $db);

		/* проверка подключения */
		if (mysqli_connect_errno()) {
			echo 'Не удалось подключиться: '. mysqli_connect_error();
			exit();
		}

		/* установка кодировки utf8 */
		if (!$this->link->set_charset("utf8")) {
			echo 'Ошибка при загрузке набора символов utf8: '.$this->link->error;
	    }
    }

    public function find_all($select)
    {
        $query = $select;
        $result_mas=array();
        $result = mysqli_query($this->link, $query);
        while ($mas = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_mas[]=$mas;
        }
        return $result_mas;
    }

    public function find_one($select)
    {
        $query = $select;
        $result_mas=array();
        $result = mysqli_query($this->link, $query);
        $result_mas = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $result_mas;
    }

    public function update($query_upd)
    {
        $query = $query_upd;
        $result = mysqli_query($this->link, $query);
        return true;
    }
}