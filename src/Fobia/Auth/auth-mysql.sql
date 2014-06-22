
--
-- База данных: `auth`
--

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL COMMENT 'битовая маска',
  `name` varchar(20) NOT NULL COMMENT 'название роли',
  `caption` varchar(100) DEFAULT NULL COMMENT 'пояснение',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='роли пользователе';

-- --------------------------------------------------------


--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(64) DEFAULT NULL,
  `role_mask` int(10) unsigned NOT NULL DEFAULT '0',
  `online` datetime DEFAULT NULL COMMENT 'время online',
  `sid` varchar(47) DEFAULT NULL COMMENT 'Текущая сессия',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='пользователи' AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `role_mask`, `online`, `sid`) VALUES
(1, 'test@test', '76a1bd87b1b49ea21ab9a25da7a2d27587f3e0b6f3f833bfb11ba132434f4abd', 0, '2014-06-22 15:01:10', NULL);
