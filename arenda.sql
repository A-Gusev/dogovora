SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `admin_arenda`
--
USE `admin_arenda`;

-- --------------------------------------------------------

--
-- Структура таблицы `type_firm`
--

CREATE TABLE IF NOT EXISTS `type_firm` (
  `tf_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `tf_type` text NOT NULL,
  PRIMARY KEY (`tf_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Очистить таблицу перед добавлением данных `type_company`
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
-- Структура таблицы `type_contract`
--

CREATE TABLE IF NOT EXISTS `type_contract` (
  `tc_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `tc_type` text NOT NULL,
  PRIMARY KEY (`tc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

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
-- Структура таблицы `firms`
--

CREATE TABLE IF NOT EXISTS `firms` (
  `f_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `f_id_type` int(5) NOT NULL,
  `f_inn` text NOT NULL,
  `f_kpp` text NOT NULL,
  `f_ogrn` text NOT NULL,
  `f_name` text NOT NULL,
  `f_address` text NOT NULL,
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
  `f_problem` BOOLEAN NOT NULL,
  `f_mail_s` BOOLEAN NOT NULL,
  `f_mail_s_s` date NOT NULL,
  `f_mail_s_e` date NOT NULL,
  `f_mail_s_checked` BOOLEAN NOT NULL, 
  PRIMARY KEY (`f_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Очистить таблицу перед добавлением данных `firms`
--

TRUNCATE TABLE `firms`;
--
-- Дамп данных таблицы `firms`
--

INSERT INTO `firms` (`f_id`, `f_id_type`, `f_inn`, `f_kpp`, `f_ogrn`, `f_name`, `f_address`, `f_director`, `f_director_io`, `f_director_r`, `f_passport`, `f_tel`, `f_mail`, `f_fax`, `f_requisites`, `f_agreement`, `f_notes`, `f_problem`, `f_mail_s`, `f_mail_s_s`, `f_mail_s_e`, `f_mail_s_checked`) VALUES
(1, '1', '1234567891', '123456789', '1234567890123', 'ООО Рога и Копыта', 'Кедрова 22', 'Иванов Иван Олегович', 'Иванов И.О.', 'Иванову Ивану Олеговичу', '4509 676565
ОВД овэдешного района', '+7 (926) 123-45-67', 'mail@exempl.com', '+7 (926) 123-45-67', 'Реквизиты', 'Договорённости', 'Заметки', '', '1', '2014-10-01',  '2014-11-01', ''),
(2, '2', '1234567891', '123456789', '1234567890123', 'ООО design4net', 'Кедрова 22', 'Иванов Иван Олегович', 'Иванов И.О.', 'Иванову Ивану Олеговичу', '4509 676565
ОВД овэдешного района', '+7 (926) 123-45-67', 'mail@exempl.com', '+7 (926) 123-45-67', 'Реквизиты', 'Договорённости', 'Заметки', '1', '1', '2014-10-01',  '', ''),
(3, '3', '', '', '', 'ООО дизайнчетыренет', 'Кедрова 22', 'Иванов Иван Олегович', 'Иванов И.О.', 'Иванову Ивану Олеговичу', '4509 676565
ОВД овэдешного района', '+7 (926) 123-45-67', 'mail@exempl.com', '+7 (926) 123-45-67', 'Реквизиты', 'Договорённости', 'Заметки', '', '1', '2014-10-01',  '2015-10-01', ''),
(4, '2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',  '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `contract`
--

CREATE TABLE IF NOT EXISTS `contract` (
  `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_nomer` text NOT NULL,
  `c_date` date NOT NULL,
  `c_date-s` date NOT NULL,
  `c_date-po` date NOT NULL,
  `c_date-akt` date NOT NULL,
  `c_number` text NOT NULL,
  `c_m2` text NOT NULL,
  `c_id_type_dog` int(5) NOT NULL,
  `c_company_id` int(5) NOT NULL,
  `c_prim` text NOT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Очистить таблицу перед добавлением данных `contract`
--

TRUNCATE TABLE `contract`;
--
-- Дамп данных таблицы `contract`
--
INSERT INTO `contract` (`c_id`, `c_nomer`, `c_date`, `c_date-s`, `c_date-po`, `c_date-akt`, `c_number`, `c_m2`, `c_id_type_dog`, `c_company_id`, `c_prim`) VALUES
(1, '1', '2014-09-22', '2014-10-01', '2014-10-10', '2014-10-01', '12', '23', 1, 1, 'Примечание 1'),
(2, '2', '2014-09-23', '2014-09-01', '2014-10-25', '2014-10-01', '14', '24', 2, 2, 'Примечание 2'),
(3, '3', '2014-09-24', '2014-10-08', '2015-10-20', '2014-10-01', '14', '27', 2, 2, 'Примечание 3'),
(4, '4', '2014-09-24', '2014-10-08', '2014-12-20', '2014-10-01', '15', '27', 2, 1, 'Примечание 4');

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `s_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `s_company_name` text NOT NULL,
  `s_job_title` text NOT NULL,
  `s_job_title_r` text NOT NULL,
  `s_director` text NOT NULL,
  `s_director_io` text NOT NULL,
  `s_director_r` text NOT NULL,
  `s_bank_account-1` text NOT NULL,
  `s_bank_account-2` text NOT NULL,
  PRIMARY KEY (`s_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Очистить таблицу перед добавлением данных `settings`
--

TRUNCATE TABLE `settings`;
--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`s_id`, `s_company_name`, `s_job_title`, `s_job_title_r`, `s_director`, `s_director_io`, `s_director_r`, `s_bank_account-1`, `s_bank_account-2`) VALUES
(1, 'Рога и Копыта', 'Директор', 'Директору', 'Куликов Иван Сергеевич', 'Куликов И.С', 'Куликову Ивану Сергеевичу', 'ИНН 7715257832\r\nКПП 771501001\r\nр/сч  40703810287810000000\r\nв Московский филиал ОАО АКБ «Росбанк» г. Москва\r\nк/с 30101810000000000272\r\nБИК 044583272\r\nОКПО 55007845\r\nОКАТО 45280574000', 'Банк получателя: ОАО «Сбербанк России», г. Москва\r\nРасчетный счет 40703810438180133973 (рубли РФ)\r\nИНН 7724296034, КПП 770401001.\r\nБИК 044525225\r\nКорсчет 30101810400000000225\r\nОГРН 1067799030826 (свидетельство серия 77 № 008801539 от 28 ноября 2006)\r\nОКАТО 45286590000');

-- --------------------------------------------------------