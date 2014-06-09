
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='пользователи';
