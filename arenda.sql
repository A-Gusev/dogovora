SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `admin_arenda`
--
USE `admin_arenda`;

-- --------------------------------------------------------

--
-- Структура таблицы `type_company`
--

CREATE TABLE IF NOT EXISTS `type_company` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Очистить таблицу перед добавлением данных `type_company`
--

TRUNCATE TABLE `type_company`;
--
-- Дамп данных таблицы `contract`
--

INSERT INTO `type_company` (`id`, `type`) VALUES
(1, 'ЮЛ'),
(2, 'ИП'),
(3, 'ФЛ');

-- --------------------------------------------------------


--
-- Структура таблицы `type_contract`
--

CREATE TABLE IF NOT EXISTS `type_contract` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Очистить таблицу перед добавлением данных `type_company`
--

TRUNCATE TABLE `type_contract`;
--
-- Дамп данных таблицы `contract`
--

INSERT INTO `type_contract` (`id`, `type`) VALUES
(1, 'Аренда помещения'),
(2, 'Аренда площади'),
(3, 'Аренда рабочего места'),
(4, 'Прочее');

-- --------------------------------------------------------



--
-- Структура таблицы `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_type` int(3) NOT NULL,
  `name` text NOT NULL,
  `director` text NOT NULL,
  `requisites` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Очистить таблицу перед добавлением данных `company`
--

TRUNCATE TABLE `company`;
--
-- Дамп данных таблицы `company`
--

INSERT INTO `company` (`id`, `id_type`, `name`, `director`, `requisites`) VALUES
(1, '1', 'Design4net', 'Гусев АС', 'ИНН 12345678'),
(2, '2', 'ООО удалить-инвест', 'Петров Иван Андреевич', 'ИНН 12345678'),
(3, '1', 'Seo4net', 'Куплетский СА', 'Реквизиты');

-- --------------------------------------------------------

--
-- Структура таблицы `contract`
--

CREATE TABLE IF NOT EXISTS `contract` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nomer` text NOT NULL,
  `date` date NOT NULL,
  `id_type_dog` int(5) NOT NULL,
  `company_id` int(5) NOT NULL,
  `prim` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Очистить таблицу перед добавлением данных `contract`
--

TRUNCATE TABLE `contract`;
--
-- Дамп данных таблицы `contract`
--

INSERT INTO `contract` (`id`, `nomer`, `date`, `id_type_dog`, `company_id`, `prim`) VALUES
(1, '1', '2014-09-22', '1', 1, 'Примечание 1'),
(2, '2', '2014-10-17', '2', 3, 'Примечание 2'),
(3, '3', '2014-10-17', '3', 1, 'Примечание 3');

-- --------------------------------------------------------

--
-- Структура таблицы `gl_settings`
--

CREATE TABLE IF NOT EXISTS `gl_settings` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `job_title_r` varchar(255) NOT NULL,
  `director` varchar(255) NOT NULL,
  `director_io` varchar(255) NOT NULL,
  `director_r` varchar(255) NOT NULL,
  `bank_account-1` text NOT NULL,
  `bank_account-2` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Очистить таблицу перед добавлением данных `gl_settings`
--

TRUNCATE TABLE `gl_settings`;
--
-- Дамп данных таблицы `gl_settings`
--

INSERT INTO `gl_settings` (`id`, `company_name`, `job_title`, `job_title_r`, `director`, `director_io`, `director_r`, `bank_account-1`, `bank_account-2`) VALUES
(1, 'Рога и Копыта', 'Директор', 'Директору', 'Куликов Иван Сергеевич', 'Куликов И.С', 'Куликову Ивану Сергеевичу', 'ИНН 7715257832\r\nКПП 771501001\r\nр/сч  40703810287810000000\r\nв Московский филиал ОАО АКБ «Росбанк» г. Москва\r\nк/с 30101810000000000272\r\nБИК 044583272\r\nОКПО 55007845\r\nОКАТО 45280574000', 'Банк получателя: ОАО «Сбербанк России», г. Москва\r\nРасчетный счет 40703810438180133973 (рубли РФ)\r\nИНН 7724296034, КПП 770401001.\r\nБИК 044525225\r\nКорсчет 30101810400000000225\r\nОГРН 1067799030826 (свидетельство серия 77 № 008801539 от 28 ноября 2006)\r\nОКАТО 45286590000');