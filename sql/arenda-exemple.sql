--
-- База данных, заполненная демо-данными (удалит старые значения)
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+03:00";

--
-- База данных: `admin_arenda`
--
CREATE DATABASE IF NOT EXISTS `admin_arenda` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `admin_arenda`;

-- --------------------------------------------------------

--
-- Структура таблицы `contract`
--

DROP TABLE IF EXISTS `contract`;
CREATE TABLE IF NOT EXISTS `contract` (
`c_id` int(10) unsigned NOT NULL,
  `c_nomer` text NOT NULL,
  `c_date` date NOT NULL,
  `c_date-s` date NOT NULL,
  `c_date-po` date NOT NULL,
  `c_date-akt` date NOT NULL,
  `c_number` text NOT NULL,
  `c_m2` text NOT NULL,
  `c_id_type_dog` int(5) NOT NULL,
  `c_company_id` int(5) NOT NULL,
  `c_prim` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `contract`
--

TRUNCATE TABLE `contract`;
--
-- Дамп данных таблицы `contract`
--

INSERT INTO `contract` (`c_id`, `c_nomer`, `c_date`, `c_date-s`, `c_date-po`, `c_date-akt`, `c_number`, `c_m2`, `c_id_type_dog`, `c_company_id`, `c_prim`) VALUES
(1, '1', '2014-09-22', '2014-10-01', '2015-01-20', '2014-10-01', '12', '23', 1, 1, 'Примечание 1'),
(2, '2', '2014-09-23', '2014-09-01', '2015-03-06', '2014-10-01', '14', '24', 2, 2, 'Примечание 2'),
(3, '3', '2014-09-24', '2014-10-08', '2015-10-20', '2014-10-01', '14', '27', 2, 2, 'Примечание 3'),
(4, '4', '2014-09-24', '2014-10-08', '2014-12-20', '2014-10-01', '15', '27', 1, 2, 'Примечание 4');

-- --------------------------------------------------------

--
-- Структура таблицы `firms`
--

DROP TABLE IF EXISTS `firms`;
CREATE TABLE IF NOT EXISTS `firms` (
`f_id` int(8) unsigned NOT NULL,
  `f_id_type` int(5) NOT NULL,
  `f_inn` text NOT NULL,
  `f_kpp` text NOT NULL,
  `f_ogrn` text NOT NULL,
  `f_name` text NOT NULL,
  `f_address` text NOT NULL,
  `f_doljnost` text NOT NULL,
  `f_director` text NOT NULL,
  `f_director_io` text NOT NULL,
  `f_director_r` text NOT NULL,
  `f_passport` text NOT NULL,
  `f_tel` text NOT NULL,
  `f_mail` text NOT NULL,
  `f_fax` text NOT NULL,
  `f_requisites` text NOT NULL,
  `f_agreement` text NOT NULL,
  `f_notes` text NOT NULL,
  `f_problem` tinyint(1) NOT NULL,
  `f_mail_s` tinyint(1) NOT NULL,
  `f_mail_s_s` date NOT NULL,
  `f_mail_s_e` date NOT NULL,
  `f_mail_s_checked` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `firms`
--

TRUNCATE TABLE `firms`;
--
-- Дамп данных таблицы `firms`
--

INSERT INTO `firms` (`f_id`, `f_id_type`, `f_inn`, `f_kpp`, `f_ogrn`, `f_name`, `f_address`, `f_doljnost`, `f_director`, `f_director_io`, `f_director_r`, `f_passport`, `f_tel`, `f_mail`, `f_fax`, `f_requisites`, `f_agreement`, `f_notes`, `f_problem`, `f_mail_s`, `f_mail_s_s`, `f_mail_s_e`, `f_mail_s_checked`) VALUES
(1, 3, '11111111111', '123456789', '1234567890123', 'ООО дизайнчетыренет', 'Кедрова 22', '', 'Иванов Иван Олегович', 'Иванов И.О.', 'Иванову Ивану Олеговичу', '4509 676565\r\nОВД овэдешного района', '+7 (926) 123-45-67', 'mail@exemple.com', '+7 (926) 123-45-67', 'Реквизиты', 'Договорённости', 'Заметки', 0, 1, '2014-10-01', '2015-10-01', 1),
(2, 2, '1234567891', '123456789', '1234567890123', 'ООО design4net', 'Кедрова 22', 'Глава совета директоров', 'Иванов Иван Олегович', 'Иванов И.О.', 'Иванову Ивану Олеговичу', '4509 676565\r\nОВД овэдешного района', '+7 (926) 123-45-67', 'mail@exemple.com', '+7 (926) 123-45-67', 'Реквизиты', 'Особые договорённости', 'Особые Заметки', 1, 1, '2014-10-01', '2014-10-23', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
`s_id` int(3) unsigned NOT NULL,
  `s_company_name` text NOT NULL,
  `s_job_title` text NOT NULL,
  `s_job_title_r` text NOT NULL,
  `s_director` text NOT NULL,
  `s_director_io` text NOT NULL,
  `s_director_r` text NOT NULL,
  `s_bank_account-1` text NOT NULL,
  `s_bank_account-2` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `settings`
--

TRUNCATE TABLE `settings`;
--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`s_id`, `s_company_name`, `s_job_title`, `s_job_title_r`, `s_director`, `s_director_io`, `s_director_r`, `s_bank_account-1`, `s_bank_account-2`) VALUES
(1, 'Рога и Копыта', 'Директор', 'Директору', 'Куликов Иван Сергеевич', 'Куликов И.С.', 'Куликову Ивану Сергеевичу', 'ИНН 7715257832\r\nКПП 771501001\r\nр/сч  40703810287810000000\r\nв Московский филиал ОАО АКБ «Росбанк» г. Москва\r\nк/с 30101810000000000272\r\nБИК 044583272\r\nОКПО 55007845\r\nОКАТО 45280574000', 'Банк получателя: ОАО «Сбербанк России», г. Москва\r\nРасчетный счет 40703810438180133973 (рубли РФ)\r\nИНН 7724296034, КПП 770401001.\r\nБИК 044525225\r\nКорсчет 30101810400000000225\r\nОГРН 1067799030826 (свидетельство серия 77 № 008801539 от 28 ноября 2006)\r\nОКАТО 45286590000');

-- --------------------------------------------------------

--
-- Структура таблицы `type_contract`
--

DROP TABLE IF EXISTS `type_contract`;
CREATE TABLE IF NOT EXISTS `type_contract` (
`tc_id` int(5) unsigned NOT NULL,
  `tc_type` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `type_contract`
--

TRUNCATE TABLE `type_contract`;
--
-- Дамп данных таблицы `type_contract`
--

INSERT INTO `type_contract` (`tc_id`, `tc_type`) VALUES
(1, 'Аренда помещения'),
(2, 'Аренда площади'),
(3, 'Аренда рабочего места'),
(4, 'Прочее');

-- --------------------------------------------------------

--
-- Структура таблицы `type_firm`
--

DROP TABLE IF EXISTS `type_firm`;
CREATE TABLE IF NOT EXISTS `type_firm` (
`tf_id` int(5) unsigned NOT NULL,
  `tf_type` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `type_firm`
--

TRUNCATE TABLE `type_firm`;
--
-- Дамп данных таблицы `type_firm`
--

INSERT INTO `type_firm` (`tf_id`, `tf_type`) VALUES
(1, 'ЮЛ'),
(2, 'ИП'),
(3, 'ФЛ');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
`u_id` int(11) NOT NULL,
  `u_login` varchar(30) NOT NULL,
  `u_psw` varchar(40) NOT NULL,
  `u_hash` varchar(32) NOT NULL,
  `u_name` varchar(50) DEFAULT NULL,
  `u_rights` int(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `users`
--

TRUNCATE TABLE `users`;
--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`u_id`, `u_login`, `u_psw`, `u_hash`, `u_name`, `u_rights`) VALUES
(1, 'admin', '61b91a39fa4ecd3526f8c8ce21b5ba1c82805800', '', 'Иван', 2),
(2, 'manager', '97d6f99dca05b2f79d89c69a815ea276283381b9', '', '', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `contract`
--
ALTER TABLE `contract`
 ADD PRIMARY KEY (`c_id`);

--
-- Индексы таблицы `firms`
--
ALTER TABLE `firms`
 ADD PRIMARY KEY (`f_id`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
 ADD PRIMARY KEY (`s_id`);

--
-- Индексы таблицы `type_contract`
--
ALTER TABLE `type_contract`
 ADD PRIMARY KEY (`tc_id`);

--
-- Индексы таблицы `type_firm`
--
ALTER TABLE `type_firm`
 ADD PRIMARY KEY (`tf_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `contract`
--
ALTER TABLE `contract`
MODIFY `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `firms`
--
ALTER TABLE `firms`
MODIFY `f_id` int(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
MODIFY `s_id` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `type_contract`
--
ALTER TABLE `type_contract`
MODIFY `tc_id` int(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `type_firm`
--
ALTER TABLE `type_firm`
MODIFY `tf_id` int(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;