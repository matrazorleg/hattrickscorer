CREATE TABLE `auth` (
 `id` int NOT NULL AUTO_INCREMENT,
 `tmp_token` varchar(50) NOT NULL,
 `oauth_token` varchar(50) NOT NULL,
 `oauth_token_secret` varchar(50) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2368 DEFAULT CHARSET=utf8mb3

CREATE TABLE `bomber_list` (
 `user_id` int NOT NULL DEFAULT '-1',
 `team_id` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
 `competition` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
 `team_name` varchar(512) NOT NULL DEFAULT '',
 `team_country` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
 `player_id` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
 `player_name` varchar(256) NOT NULL DEFAULT '',
 `goals` int NOT NULL DEFAULT '0',
 `appearance` int NOT NULL DEFAULT '0',
 `history` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
 `in_team` int NOT NULL DEFAULT '0',
 PRIMARY KEY (`team_id`,`competition`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `captains` (
 `id` int NOT NULL,
 `last_request` date NOT NULL,
 `c_campionato` longtext NOT NULL,
 `c_coppa` longtext NOT NULL,
 `c_amichevole` longtext NOT NULL,
 `c_masters` longtext NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3

CREATE TABLE `matches` (
 `id` int NOT NULL,
 `last_request` date NOT NULL,
 `m_campionato` longtext NOT NULL,
 `m_coppa` longtext NOT NULL,
 `m_amichevole` longtext NOT NULL,
 `m_masters` longtext NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3

CREATE TABLE `presence` (
 `id` int NOT NULL,
 `p_campionato` longtext NOT NULL,
 `p_coppa` longtext NOT NULL,
 `p_amichevole` longtext NOT NULL,
 `p_masters` longtext NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3

CREATE TABLE `request` (
 `id` int NOT NULL,
 `last_request` date NOT NULL,
 `campionato` longtext NOT NULL,
 `coppa` longtext NOT NULL,
 `amichevole` longtext NOT NULL,
 `masters` longtext NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3

CREATE TABLE `season` (
 `season_id` int NOT NULL,
 `date_start` date NOT NULL,
 `date_end` date NOT NULL,
 PRIMARY KEY (`season_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3

CREATE TABLE `user` (
 `id` int NOT NULL AUTO_INCREMENT,
 `username` varchar(50) NOT NULL,
 `email` varchar(50) NOT NULL,
 `password` varchar(50) NOT NULL,
 `register_date` datetime NOT NULL,
 `last_visit` datetime NOT NULL,
 `user_code` varchar(50) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3