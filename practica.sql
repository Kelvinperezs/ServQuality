-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 02-10-2015 a las 17:02:22
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `practica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_api_key`
--

CREATE TABLE IF NOT EXISTS `ost_api_key` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `isactive` tinyint(1) NOT NULL DEFAULT '1',
  `ipaddr` varchar(16) NOT NULL,
  `apikey` varchar(255) NOT NULL,
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ipaddr` (`ipaddr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `ost_api_key`
--

INSERT INTO `ost_api_key` (`id`, `isactive`, `ipaddr`, `apikey`, `updated`, `created`) VALUES
(1, 0, '192.168.1.5', 'siri!', '2015-06-25 15:26:19', '2015-06-25 15:26:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_config`
--

CREATE TABLE IF NOT EXISTS `ost_config` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `isonline` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `timezone_offset` float(3,1) NOT NULL DEFAULT '0.0',
  `enable_daylight_saving` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `staff_ip_binding` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `staff_max_logins` tinyint(3) unsigned NOT NULL DEFAULT '4',
  `staff_login_timeout` int(10) unsigned NOT NULL DEFAULT '2',
  `staff_session_timeout` int(10) unsigned NOT NULL DEFAULT '30',
  `client_max_logins` tinyint(3) unsigned NOT NULL DEFAULT '4',
  `client_login_timeout` int(10) unsigned NOT NULL DEFAULT '2',
  `client_session_timeout` int(10) unsigned NOT NULL DEFAULT '30',
  `max_page_size` tinyint(3) unsigned NOT NULL DEFAULT '25',
  `max_open_tickets` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `max_file_size` int(11) unsigned NOT NULL DEFAULT '1048576',
  `autolock_minutes` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `overdue_grace_period` int(10) unsigned NOT NULL DEFAULT '0',
  `alert_email_id` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `default_email_id` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `default_dept_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `default_priority_id` tinyint(2) unsigned NOT NULL DEFAULT '2',
  `default_template_id` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `default_smtp_id` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `spoof_default_smtp` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `clickable_urls` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allow_priority_change` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `use_email_priority` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_captcha` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_auto_cron` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_mail_fetch` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_email_piping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `send_sql_errors` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `send_mailparse_errors` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `send_login_errors` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `save_email_headers` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `strip_quoted_reply` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `log_ticket_activity` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ticket_autoresponder` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message_autoresponder` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ticket_notice_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ticket_alert_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ticket_alert_admin` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ticket_alert_dept_manager` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ticket_alert_dept_members` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message_alert_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message_alert_laststaff` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `message_alert_assigned` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `message_alert_dept_manager` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `note_alert_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `note_alert_laststaff` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `note_alert_assigned` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `note_alert_dept_manager` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `overdue_alert_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `overdue_alert_assigned` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `overdue_alert_dept_manager` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `overdue_alert_dept_members` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `auto_assign_reopened_tickets` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `show_assigned_tickets` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `show_answered_tickets` tinyint(1) NOT NULL DEFAULT '0',
  `hide_staff_name` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `overlimit_notice_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `email_attachments` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allow_attachments` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_email_attachments` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_online_attachments` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_online_attachments_onlogin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `random_ticket_ids` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `log_level` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `log_graceperiod` int(10) unsigned NOT NULL DEFAULT '12',
  `upload_dir` varchar(255) NOT NULL DEFAULT '',
  `allowed_filetypes` varchar(255) NOT NULL DEFAULT '.doc, .pdf',
  `time_format` varchar(32) NOT NULL DEFAULT ' H:i:G',
  `date_format` varchar(32) NOT NULL DEFAULT 'd/m/Y',
  `datetime_format` varchar(60) NOT NULL DEFAULT 'd/m/Y - G:i:H',
  `daydatetime_format` varchar(60) NOT NULL DEFAULT 'D, M j Y - G:i:H',
  `reply_separator` varchar(60) NOT NULL DEFAULT '-- do not edit --',
  `admin_email` varchar(125) NOT NULL DEFAULT '',
  `helpdesk_title` varchar(255) NOT NULL DEFAULT 'osTicket Sitema de Soporte',
  `helpdesk_url` varchar(255) NOT NULL DEFAULT '',
  `api_passphrase` varchar(125) NOT NULL DEFAULT '',
  `ostversion` varchar(16) NOT NULL DEFAULT '',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `isoffline` (`isonline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `ost_config`
--

INSERT INTO `ost_config` (`id`, `isonline`, `timezone_offset`, `enable_daylight_saving`, `staff_ip_binding`, `staff_max_logins`, `staff_login_timeout`, `staff_session_timeout`, `client_max_logins`, `client_login_timeout`, `client_session_timeout`, `max_page_size`, `max_open_tickets`, `max_file_size`, `autolock_minutes`, `overdue_grace_period`, `alert_email_id`, `default_email_id`, `default_dept_id`, `default_priority_id`, `default_template_id`, `default_smtp_id`, `spoof_default_smtp`, `clickable_urls`, `allow_priority_change`, `use_email_priority`, `enable_captcha`, `enable_auto_cron`, `enable_mail_fetch`, `enable_email_piping`, `send_sql_errors`, `send_mailparse_errors`, `send_login_errors`, `save_email_headers`, `strip_quoted_reply`, `log_ticket_activity`, `ticket_autoresponder`, `message_autoresponder`, `ticket_notice_active`, `ticket_alert_active`, `ticket_alert_admin`, `ticket_alert_dept_manager`, `ticket_alert_dept_members`, `message_alert_active`, `message_alert_laststaff`, `message_alert_assigned`, `message_alert_dept_manager`, `note_alert_active`, `note_alert_laststaff`, `note_alert_assigned`, `note_alert_dept_manager`, `overdue_alert_active`, `overdue_alert_assigned`, `overdue_alert_dept_manager`, `overdue_alert_dept_members`, `auto_assign_reopened_tickets`, `show_assigned_tickets`, `show_answered_tickets`, `hide_staff_name`, `overlimit_notice_active`, `email_attachments`, `allow_attachments`, `allow_email_attachments`, `allow_online_attachments`, `allow_online_attachments_onlogin`, `random_ticket_ids`, `log_level`, `log_graceperiod`, `upload_dir`, `allowed_filetypes`, `time_format`, `date_format`, `datetime_format`, `daydatetime_format`, `reply_separator`, `admin_email`, `helpdesk_title`, `helpdesk_url`, `api_passphrase`, `ostversion`, `updated`) VALUES
(1, 1, -4.0, 0, 1, 4, 2, 30, 4, 2, 30, 25, 0, 1048576, 3, 72, 2, 1, 0, 2, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 12, '', '.doc, .pdf', ' H:i:G', 'd/m/Y', 'd/m/Y - G:i:H', 'D, M j Y - G:i:H', '-- do not edit --', 'juan.cadiz236@gmail.com', 'servTicket', 'http://localhost/servTicket/', '', '1.6 ST', '2015-06-25 15:26:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_department`
--

CREATE TABLE IF NOT EXISTS `ost_department` (
  `dept_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tpl_id` int(10) unsigned NOT NULL DEFAULT '0',
  `email_id` int(10) unsigned NOT NULL DEFAULT '0',
  `autoresp_email_id` int(10) unsigned NOT NULL DEFAULT '0',
  `manager_id` int(10) unsigned NOT NULL DEFAULT '0',
  `dept_name` varchar(32) NOT NULL DEFAULT '',
  `dept_signature` text NOT NULL,
  `ispublic` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ticket_auto_response` tinyint(1) NOT NULL DEFAULT '1',
  `message_auto_response` tinyint(1) NOT NULL DEFAULT '0',
  `can_append_signature` tinyint(1) NOT NULL DEFAULT '1',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`dept_id`),
  UNIQUE KEY `dept_name` (`dept_name`),
  KEY `manager_id` (`manager_id`),
  KEY `autoresp_email_id` (`autoresp_email_id`),
  KEY `tpl_id` (`tpl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `ost_department`
--

INSERT INTO `ost_department` (`dept_id`, `tpl_id`, `email_id`, `autoresp_email_id`, `manager_id`, `dept_name`, `dept_signature`, `ispublic`, `ticket_auto_response`, `message_auto_response`, `can_append_signature`, `updated`, `created`) VALUES
(1, 0, 1, 0, 0, 'Soporte', 'Dpto de Soporte', 1, 1, 1, 1, '2015-06-25 15:26:19', '2015-06-25 15:26:19'),
(4, 0, 3, 3, 0, 'Sistema  y Desarrollo', 'Sistema', 1, 1, 1, 0, '2015-06-25 15:48:39', '2015-06-25 15:48:39'),
(3, 0, 1, 1, 0, 'Redes y Servidores', 'Redes y Servidore', 1, 1, 1, 0, '2015-06-25 15:47:35', '2015-06-25 15:47:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_email`
--

CREATE TABLE IF NOT EXISTS `ost_email` (
  `email_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `noautoresp` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `priority_id` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `dept_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `email` varchar(125) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `userid` varchar(125) NOT NULL,
  `userpass` varchar(125) NOT NULL,
  `mail_active` tinyint(1) NOT NULL DEFAULT '0',
  `mail_host` varchar(125) NOT NULL,
  `mail_protocol` enum('POP','IMAP') NOT NULL DEFAULT 'POP',
  `mail_encryption` enum('NONE','SSL') NOT NULL,
  `mail_port` int(6) DEFAULT NULL,
  `mail_fetchfreq` tinyint(3) NOT NULL DEFAULT '5',
  `mail_fetchmax` tinyint(4) NOT NULL DEFAULT '30',
  `mail_delete` tinyint(1) NOT NULL DEFAULT '0',
  `mail_errors` tinyint(3) NOT NULL DEFAULT '0',
  `mail_lasterror` datetime DEFAULT NULL,
  `mail_lastfetch` datetime DEFAULT NULL,
  `smtp_active` tinyint(1) DEFAULT '0',
  `smtp_host` varchar(125) NOT NULL,
  `smtp_port` int(6) DEFAULT NULL,
  `smtp_secure` tinyint(1) NOT NULL DEFAULT '1',
  `smtp_auth` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`email_id`),
  UNIQUE KEY `email` (`email`),
  KEY `priority_id` (`priority_id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `ost_email`
--

INSERT INTO `ost_email` (`email_id`, `noautoresp`, `priority_id`, `dept_id`, `email`, `name`, `userid`, `userpass`, `mail_active`, `mail_host`, `mail_protocol`, `mail_encryption`, `mail_port`, `mail_fetchfreq`, `mail_fetchmax`, `mail_delete`, `mail_errors`, `mail_lasterror`, `mail_lastfetch`, `smtp_active`, `smtp_host`, `smtp_port`, `smtp_secure`, `smtp_auth`, `created`, `updated`) VALUES
(1, 0, 2, 3, 'jcadiz@inamujer.gob.ve', 'Soporte', '', 'j66wYc9/c/X6IYMTj+DTvkkia/BFUiXzOP/MmVKNYsg=', 0, '', 'POP', 'NONE', 0, 5, 30, 0, 0, NULL, NULL, 0, '', 0, 1, 1, '2015-06-25 15:26:20', '2015-07-02 14:49:02'),
(2, 0, 1, 1, 'alerts@inamujer.gob.ve', 'Alertas de Ticket', '', '', 0, '', 'POP', 'NONE', NULL, 5, 30, 0, 0, NULL, NULL, 0, '', NULL, 1, 1, '2015-06-25 15:26:20', '2015-06-25 15:26:20'),
(3, 0, 1, 1, 'ejemplo@inamujer.gob.ve', '', '', '', 0, '', 'POP', 'NONE', NULL, 5, 30, 0, 0, NULL, NULL, 0, '', NULL, 1, 1, '2015-06-25 15:26:20', '2015-06-25 15:26:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_email_banlist`
--

CREATE TABLE IF NOT EXISTS `ost_email_banlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '',
  `submitter` varchar(126) NOT NULL DEFAULT '',
  `added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `ost_email_banlist`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_email_template`
--

CREATE TABLE IF NOT EXISTS `ost_email_template` (
  `tpl_id` int(11) NOT NULL AUTO_INCREMENT,
  `cfg_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL DEFAULT '',
  `notes` text,
  `ticket_autoresp_subj` varchar(255) NOT NULL DEFAULT '',
  `ticket_autoresp_body` text NOT NULL,
  `ticket_notice_subj` varchar(255) NOT NULL,
  `ticket_notice_body` text NOT NULL,
  `ticket_alert_subj` varchar(255) NOT NULL DEFAULT '',
  `ticket_alert_body` text NOT NULL,
  `message_autoresp_subj` varchar(255) NOT NULL DEFAULT '',
  `message_autoresp_body` text NOT NULL,
  `message_alert_subj` varchar(255) NOT NULL DEFAULT '',
  `message_alert_body` text NOT NULL,
  `note_alert_subj` varchar(255) NOT NULL,
  `note_alert_body` text NOT NULL,
  `assigned_alert_subj` varchar(255) NOT NULL DEFAULT '',
  `assigned_alert_body` text NOT NULL,
  `ticket_overdue_subj` varchar(255) NOT NULL DEFAULT '',
  `ticket_overdue_body` text NOT NULL,
  `ticket_overlimit_subj` varchar(255) NOT NULL DEFAULT '',
  `ticket_overlimit_body` text NOT NULL,
  `ticket_reply_subj` varchar(255) NOT NULL DEFAULT '',
  `ticket_reply_body` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`tpl_id`),
  KEY `cfg_id` (`cfg_id`),
  FULLTEXT KEY `message_subj` (`ticket_reply_subj`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `ost_email_template`
--

INSERT INTO `ost_email_template` (`tpl_id`, `cfg_id`, `name`, `notes`, `ticket_autoresp_subj`, `ticket_autoresp_body`, `ticket_notice_subj`, `ticket_notice_body`, `ticket_alert_subj`, `ticket_alert_body`, `message_autoresp_subj`, `message_autoresp_body`, `message_alert_subj`, `message_alert_body`, `note_alert_subj`, `note_alert_body`, `assigned_alert_subj`, `assigned_alert_body`, `ticket_overdue_subj`, `ticket_overdue_body`, `ticket_overlimit_subj`, `ticket_overlimit_body`, `ticket_reply_subj`, `ticket_reply_body`, `created`, `updated`) VALUES
(1, 1, 'Plantilla por defecto', 'Plantillas por defecto', 'Ticket Abierto [#%ticket]', '%name,\r\n\r\nLa consulta ha sido recibida y tiene asignado el siguiente ID #%ticket. Uno de nuestros representates respondera a tu solicitud entre 24 y 72 horas.\r\n\r\nPuedes consultar el progreso de tu ticket en linea desde este enlace: %url/view.php?e=%email&t=%ticket.\r\n\r\nSi deseas enviar comentarios o informacion adicional sobre este tema, por favor, no abras un ticket nuevo. Simplemente ingresa a traves del enlace anterior y actualiza el ticket.\r\n\r\n%signature', '[#%ticket] %subject', '%name,\r\n\r\nNuestro equipo de atencion al usuario ha respondido tu ticket, #%ticket con el siguiente mensaje.\r\n\r\n%message\r\n\r\nSi deseas hacernos llegar informacion adicional sobre este tema, por favor, no abras un ticket nuevo. Puedes actualizar o ver en linea el progreso de este ticket en: %url/view.php?e=%email&t=%ticket.\r\n\r\n%signature', 'Alerta de Ticket Nuevo', '%staff,\r\n\r\nNuevo Ticket #%ticket creado.\r\n-------------------\r\nNombre: %name\r\nEmail: %email\r\nDpto: %dept\r\n\r\n%message\r\n-------------------\r\n\r\nPara ver o responder a este ticket, por favor ingresa al sistema de soporte.\r\n\r\n- Tu sistema de soporte amigable - potenciado por osTicket.', '[#%ticket] Mensaje Recibido', '%name,\r\n\r\nTu respuesta a la consulta de soporte #%ticket se ha recibido.\r\n\r\nPuedes ver el progreso de tu ticket en linea desde: %url/view.php?e=%email&t=%ticket.\r\n\r\n%signature', 'Alerta de Nuevo Mensaje', '%staff,\r\n\r\nHa recibido un mensaje para el ticket #%ticket\r\n\r\n----------------------\r\nNombre: %name\r\nEmail: %email\r\nDpto: %dept\r\n\r\n%message\r\n-------------------\r\n\r\nPara ver o responder a este ticket, por favor ingresa al sistema de soporte .\r\n\r\n- Tu sistema de soporte amigable - potenciado por osTicket.', 'Alerta de nueva nota interna', '%staff,\r\n\r\nNota interna recibida para el ticket #%ticket\r\n\r\n----------------------\r\nNombre: %name\r\n\r\n%note\r\n-------------------\r\n\r\nPara ver o responder a este ticket, por favor ingresa al sistema de soporte.\r\n\r\n- Tu sistema de soporte amigable - potenciado por osTicket.', 'Ticket #%ticket Asignado a ti', '%assignee,\r\n\r\n%assigner te a asignado el ticket #%ticket a ti!\r\n\r\n%message\r\n\r\nPara ver o responder a este ticket, por favor ingresa al sistema de soporte .\r\n\r\n- Tu sistema de soporte amigable - potenciado por osTicket.', 'Alerta de ticket vencido', '%staff,\r\n\r\nEl ticket, #%ticket asignado a ti o tu departamento se a vencido.\r\n\r\n%url/scp/tickets.php?id=%id\r\n\r\nTodos debemos trabajar duro para garantizar que todos los tickets se estan abordando de manera oportuna. Por favor, resuelve el problema asi no recibira otro mensaje como este.\r\n\r\n\r\n- Tu sistema de soporte amigable (aunque con un grado de paciencia) - potenciado por osTicket.', 'Ticket Denegado', '%name\r\n\r\nNo se ha creado el ticket de ayuda . Has superado el numero maximo de tickets permitido.\r\n\r\nSe trata de un bloqueo temporal. Para poder abrir otro ticket, una de tus consultas pendientes debe estar cerrada. Para actualizar o agregar comentarios a un ticket abierto, simplemente ingresa a traves del siguiente enlace.\r\n\r\n%url/view.php?e=%email\r\n\r\nGracias.\r\n\r\nEl sistema de soporte', '[#%ticket] %subject', '%name,\r\n\r\nUn miembro del personal de atencion al Usuario ha respondido tu consulta con la ID, #%ticket on la siguiente respuesta:\r\n\r\n%response\r\n\r\nEsperamos que esta respuesta haya solucionado tu pregunta. Si no, por favor, accede a tu cuenta para responder o crear un ticket nuevo.\r\n\r\n%url/view.php?e=%email&t=%ticket\r\n\r\n%signature', '2015-06-25 15:26:19', '2015-06-25 15:26:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_groups`
--

CREATE TABLE IF NOT EXISTS `ost_groups` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `group_name` varchar(50) NOT NULL DEFAULT '',
  `dept_access` varchar(255) NOT NULL DEFAULT '',
  `can_create_tickets` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `can_edit_tickets` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `can_delete_tickets` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_close_tickets` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_transfer_tickets` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `can_ban_emails` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_manage_kb` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`group_id`),
  KEY `group_active` (`group_enabled`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `ost_groups`
--

INSERT INTO `ost_groups` (`group_id`, `group_enabled`, `group_name`, `dept_access`, `can_create_tickets`, `can_edit_tickets`, `can_delete_tickets`, `can_close_tickets`, `can_transfer_tickets`, `can_ban_emails`, `can_manage_kb`, `created`, `updated`) VALUES
(1, 1, 'Administradores', '3,4,1', 1, 1, 1, 1, 1, 1, 1, '2015-06-25 15:26:19', '2015-07-02 14:53:03'),
(2, 1, 'Jefes de Departamento', '4', 1, 1, 0, 1, 1, 1, 1, '2015-06-25 15:26:19', '2015-07-02 14:53:47'),
(3, 1, 'Miembros del Staff', '4,1', 1, 0, 0, 0, 0, 0, 0, '2015-06-25 15:26:19', '2015-07-02 14:54:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_help_topic`
--

CREATE TABLE IF NOT EXISTS `ost_help_topic` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `isactive` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `noautoresp` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `priority_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dept_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `topic` varchar(32) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`topic_id`),
  UNIQUE KEY `topic` (`topic`),
  KEY `priority_id` (`priority_id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `ost_help_topic`
--

INSERT INTO `ost_help_topic` (`topic_id`, `isactive`, `noautoresp`, `priority_id`, `dept_id`, `topic`, `created`, `updated`) VALUES
(1, 1, 0, 2, 1, 'Soporte', '2015-06-25 15:26:19', '2015-06-25 15:43:21'),
(3, 1, 0, 2, 3, ' Redes y Servidore', '2015-07-02 14:51:34', '2015-07-02 14:51:34'),
(4, 1, 0, 3, 4, 'Sigeps', '2015-07-02 14:52:04', '2015-07-02 14:52:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_kb_premade`
--

CREATE TABLE IF NOT EXISTS `ost_kb_premade` (
  `premade_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dept_id` int(10) unsigned NOT NULL DEFAULT '0',
  `isenabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `title` varchar(125) NOT NULL DEFAULT '',
  `answer` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`premade_id`),
  UNIQUE KEY `title_2` (`title`),
  KEY `dept_id` (`dept_id`),
  KEY `active` (`isenabled`),
  FULLTEXT KEY `title` (`title`,`answer`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `ost_kb_premade`
--

INSERT INTO `ost_kb_premade` (`premade_id`, `dept_id`, `isenabled`, `title`, `answer`, `created`, `updated`) VALUES
(2, 0, 1, 'Ejemplo (con variables)', '\r\n%name,\r\n\r\nTu ticket #%ticket creado el %createdate esta en el departamento %dept .\r\n\r\n', '2015-06-25 15:26:19', '2015-06-25 15:26:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_staff`
--

CREATE TABLE IF NOT EXISTS `ost_staff` (
  `staff_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `dept_id` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(32) NOT NULL DEFAULT '',
  `firstname` varchar(32) DEFAULT NULL,
  `lastname` varchar(32) DEFAULT NULL,
  `passwd` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `phone` varchar(24) NOT NULL DEFAULT '',
  `phone_ext` varchar(6) DEFAULT NULL,
  `mobile` varchar(24) NOT NULL DEFAULT '',
  `signature` text NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT '1',
  `isadmin` tinyint(1) NOT NULL DEFAULT '0',
  `isvisible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `onvacation` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `daylight_saving` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `append_signature` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `change_passwd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `timezone_offset` float(3,1) NOT NULL DEFAULT '0.0',
  `max_page_size` int(11) unsigned NOT NULL DEFAULT '0',
  `auto_refresh_rate` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastlogin` datetime DEFAULT NULL,
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`staff_id`),
  UNIQUE KEY `username` (`username`),
  KEY `dept_id` (`dept_id`),
  KEY `issuperuser` (`isadmin`),
  KEY `group_id` (`group_id`,`staff_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `ost_staff`
--

INSERT INTO `ost_staff` (`staff_id`, `group_id`, `dept_id`, `username`, `firstname`, `lastname`, `passwd`, `email`, `phone`, `phone_ext`, `mobile`, `signature`, `isactive`, `isadmin`, `isvisible`, `onvacation`, `daylight_saving`, `append_signature`, `change_passwd`, `timezone_offset`, `max_page_size`, `auto_refresh_rate`, `created`, `lastlogin`, `updated`) VALUES
(1, 1, 1, 'jcadiz', 'Juan', 'Cadiz', 'd1c439329bade92d9837a9214feb031f', 'juan.cadiz236@gmail.com', '0212-5960260', '260', '04163115063', 'Coordinacion de Redes y Servidores', 1, 1, 1, 0, 1, 0, 0, -4.0, 25, 0, '2015-06-25 15:26:20', '2015-10-02 14:47:46', '2015-06-29 17:02:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_syslog`
--

CREATE TABLE IF NOT EXISTS `ost_syslog` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `log_type` enum('Depurar','Advertencia','Error') NOT NULL,
  `title` varchar(255) NOT NULL,
  `log` text NOT NULL,
  `logger` varchar(64) NOT NULL,
  `ip_address` varchar(16) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`log_id`),
  KEY `log_type` (`log_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Volcar la base de datos para la tabla `ost_syslog`
--

INSERT INTO `ost_syslog` (`log_id`, `log_type`, `title`, `log`, `logger`, `ip_address`, `created`, `updated`) VALUES
(1, '', 'ostiserv Instalado', 'Felicidades, Instalación básica de ostiserv Ticket completada\r\n\r\nGacias por elegir ostiserv Ticket', '', '127.0.0.1', '2015-06-25 15:26:20', '2015-06-25 15:26:20'),
(2, '', 'Failed login attempt (client)', 'Email: jcadiz\nTicket #: echulona\nIP: 127.0.0.1\nHora: 26 Jun, 2015, 3:38 pm ADT\n\nAttempts #2', '', '127.0.0.1', '2015-06-26 15:37:58', '2015-06-26 15:37:58'),
(3, '', '', 'Nombre de usuario: jcadiz\nIP: 127.0.0.1\nHora: Jul 8, 2015, 7:51 pm ADT\n\nIntentos #2', '', '127.0.0.1', '2015-07-08 19:51:27', '2015-07-08 19:51:27'),
(5, '', 'Plantilla de error de captura', 'No se pudieron obtener ''nuevo ticket'' plantilla de alerta #0', '', '127.0.0.1', '2015-07-15 11:31:19', '2015-07-15 11:31:19'),
(6, '', 'Failed login attempt (client)', 'Email: mponce@inamujer.gob.ve\nTicket #: echulona\nIP: 127.0.0.1\nHora: 15 Jul, 2015, 11:33 am ADT\n\nAttempts #2', '', '127.0.0.1', '2015-07-15 11:32:55', '2015-07-15 11:32:55'),
(8, '', 'Desconexi&oacute;n Miembro del Staff ', 'jcadiz Desconectado [127.0.0.1]', '', '127.0.0.1', '2015-10-02 14:47:31', '2015-10-02 14:47:31'),
(9, '', 'Inicio de sesi&oacute;n de Staff', 'jcadiz Identificado como [127.0.0.1]', '', '127.0.0.1', '2015-10-02 14:47:46', '2015-10-02 14:47:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_ticket`
--

CREATE TABLE IF NOT EXISTS `ost_ticket` (
  `ticket_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticketID` int(11) unsigned NOT NULL DEFAULT '0',
  `dept_id` int(10) unsigned NOT NULL DEFAULT '1',
  `priority_id` int(10) unsigned NOT NULL DEFAULT '2',
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(120) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `subject` varchar(64) NOT NULL DEFAULT '[Sin Asunto]',
  `helptopic` varchar(255) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `phone_ext` varchar(8) DEFAULT NULL,
  `ip_address` varchar(16) NOT NULL DEFAULT '',
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `source` enum('Web','Email','Telefono','Otro') NOT NULL DEFAULT 'Otro',
  `isoverdue` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isanswered` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `duedate` datetime DEFAULT NULL,
  `reopened` datetime DEFAULT NULL,
  `closed` datetime DEFAULT NULL,
  `lastmessage` datetime DEFAULT NULL,
  `lastresponse` datetime DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ticket_id`),
  UNIQUE KEY `email_extid` (`ticketID`,`email`),
  KEY `dept_id` (`dept_id`),
  KEY `staff_id` (`staff_id`),
  KEY `status` (`status`),
  KEY `priority_id` (`priority_id`),
  KEY `created` (`created`),
  KEY `closed` (`closed`),
  KEY `duedate` (`duedate`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `ost_ticket`
--

INSERT INTO `ost_ticket` (`ticket_id`, `ticketID`, `dept_id`, `priority_id`, `topic_id`, `staff_id`, `email`, `name`, `subject`, `helptopic`, `phone`, `phone_ext`, `ip_address`, `status`, `source`, `isoverdue`, `isanswered`, `duedate`, `reopened`, `closed`, `lastmessage`, `lastresponse`, `created`, `updated`) VALUES
(3, 3, 3, 3, 3, 0, 'mponte@inamujer.gob.ve', 'Maria Ponte', 'nuevos equipos', ' Redes y Servidore', '0212-5960211', '211', '127.0.0.1', 'open', 'Web', 1, 0, NULL, NULL, NULL, '2015-07-15 11:31:19', NULL, '2015-07-15 11:31:19', '2015-09-23 16:52:30'),
(2, 568387, 1, 2, 1, 0, 'cleomar491@gmail.com', 'laura lopez', 'maquina', 'Soporte', '0212-5960211', '211', '127.0.0.1', 'closed', 'Web', 0, 1, NULL, NULL, '2015-07-02 15:57:39', '2015-06-26 10:35:25', '2015-07-02 15:57:01', '2015-06-26 10:35:25', '2015-07-02 15:57:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_ticket_attachment`
--

CREATE TABLE IF NOT EXISTS `ost_ticket_attachment` (
  `attach_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ref_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ref_type` enum('M','R') NOT NULL DEFAULT 'M',
  `file_size` varchar(32) NOT NULL DEFAULT '',
  `file_name` varchar(128) NOT NULL DEFAULT '',
  `file_key` varchar(128) NOT NULL DEFAULT '',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`attach_id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `ref_type` (`ref_type`),
  KEY `ref_id` (`ref_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `ost_ticket_attachment`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_ticket_lock`
--

CREATE TABLE IF NOT EXISTS `ost_ticket_lock` (
  `lock_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `expire` datetime DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`lock_id`),
  UNIQUE KEY `ticket_id` (`ticket_id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Volcar la base de datos para la tabla `ost_ticket_lock`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_ticket_message`
--

CREATE TABLE IF NOT EXISTS `ost_ticket_message` (
  `msg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) unsigned NOT NULL DEFAULT '0',
  `messageId` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `headers` text,
  `source` varchar(16) DEFAULT NULL,
  `ip_address` varchar(16) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`msg_id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `msgId` (`messageId`),
  FULLTEXT KEY `message` (`message`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `ost_ticket_message`
--

INSERT INTO `ost_ticket_message` (`msg_id`, `ticket_id`, `messageId`, `message`, `headers`, `source`, `ip_address`, `created`, `updated`) VALUES
(3, 3, '', 'para hacer nuevas instalaciones de equipos tecnologicos en el area de atencion al publico', '', 'Web', '127.0.0.1', '2015-07-15 11:31:19', NULL),
(2, 2, '', 'la pantalla esta en azul', '', 'Web', '127.0.0.1', '2015-06-26 10:35:25', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_ticket_note`
--

CREATE TABLE IF NOT EXISTS `ost_ticket_note` (
  `note_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `source` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT 'Nota Interna Generica',
  `note` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`note_id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `staff_id` (`staff_id`),
  FULLTEXT KEY `note` (`note`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `ost_ticket_note`
--

INSERT INTO `ost_ticket_note` (`note_id`, `ticket_id`, `staff_id`, `source`, `title`, `note`, `created`) VALUES
(1, 2, 1, 'system', 'Ticket marcado como vencido', 'Ticket marcado como vencido por el sistema.', '2015-07-02 14:46:00'),
(2, 2, 1, 'Juan Cadiz', 'Ticket asignado a Juan Cadiz', 'ghjhgkhgkhju', '2015-07-02 15:55:45'),
(3, 2, 1, 'system', 'Ticket cerrado', 'Ticket cerrado sin responder por Juan Cadiz', '2015-07-02 15:57:39'),
(4, 3, 1, 'system', 'Ticket marcado como vencido', 'Ticket marcado como vencido por el sistema.', '2015-09-23 16:52:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_ticket_priority`
--

CREATE TABLE IF NOT EXISTS `ost_ticket_priority` (
  `priority_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `priority` varchar(60) NOT NULL DEFAULT '',
  `priority_desc` varchar(30) NOT NULL DEFAULT '',
  `priority_color` varchar(7) NOT NULL DEFAULT '',
  `priority_urgency` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ispublic` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`priority_id`),
  UNIQUE KEY `priority` (`priority`),
  KEY `priority_urgency` (`priority_urgency`),
  KEY `ispublic` (`ispublic`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `ost_ticket_priority`
--

INSERT INTO `ost_ticket_priority` (`priority_id`, `priority`, `priority_desc`, `priority_color`, `priority_urgency`, `ispublic`) VALUES
(1, 'low', 'Baja', '#DDFFDD', 4, 1),
(2, 'normal', 'Normal', '#FFFFF0', 3, 1),
(3, 'high', 'Alta', '#FEE7E7', 2, 1),
(4, 'emergency', 'Urgente', '#FEE7E7', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_ticket_response`
--

CREATE TABLE IF NOT EXISTS `ost_ticket_response` (
  `response_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ticket_id` int(11) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(11) unsigned NOT NULL DEFAULT '0',
  `staff_name` varchar(32) NOT NULL DEFAULT '',
  `response` text NOT NULL,
  `ip_address` varchar(16) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`response_id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `msg_id` (`msg_id`),
  KEY `staff_id` (`staff_id`),
  FULLTEXT KEY `response` (`response`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `ost_ticket_response`
--

INSERT INTO `ost_ticket_response` (`response_id`, `msg_id`, `ticket_id`, `staff_id`, `staff_name`, `response`, `ip_address`, `created`, `updated`) VALUES
(1, 2, 2, 1, 'Juan Cadiz', 'por volcamiento de memoria', '127.0.0.1', '2015-07-02 15:57:01', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ost_timezone`
--

CREATE TABLE IF NOT EXISTS `ost_timezone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `offset` float(3,1) NOT NULL DEFAULT '0.0',
  `timezone` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Volcar la base de datos para la tabla `ost_timezone`
--

INSERT INTO `ost_timezone` (`id`, `offset`, `timezone`) VALUES
(1, -12.0, 'Eniwetok, Kwajalein'),
(2, -11.0, 'Midway Island, Samoa'),
(3, -10.0, 'Hawaii'),
(4, -9.0, 'Alaska'),
(5, -8.0, 'Pacific Time (US & Canada)'),
(6, -7.0, 'Mountain Time (US & Canada)'),
(7, -6.0, 'Central Time (US & Canada), Mexico City'),
(8, -5.0, 'Eastern Time (US & Canada), Bogota, Lima'),
(9, -4.0, 'Atlantic Time (Venezuela), Caracas, La Paz'),
(10, -3.5, 'Newfoundland'),
(11, -3.0, 'Brazil, Buenos Aires, Georgetown'),
(12, -2.0, 'Mid-Atlantic'),
(13, -1.0, 'Azores, Cape Verde Islands'),
(14, 0.0, 'Western Europe Time, London, Lisbon, Casablanca'),
(15, 1.0, 'Brussels, Copenhagen, Madrid, Paris'),
(16, 2.0, 'Kaliningrad, South Africa'),
(17, 3.0, 'Baghdad, Riyadh, Moscow, St. Petersburg'),
(18, 3.5, 'Tehran'),
(19, 4.0, 'Abu Dhabi, Muscat, Baku, Tbilisi'),
(20, 4.5, 'Kabul'),
(21, 5.0, 'Ekaterinburg, Islamabad, Karachi, Tashkent'),
(22, 5.5, 'Bombay, Calcutta, Madras, New Delhi'),
(23, 6.0, 'Almaty, Dhaka, Colombo'),
(24, 7.0, 'Bangkok, Hanoi, Jakarta'),
(25, 8.0, 'Beijing, Perth, Singapore, Hong Kong'),
(26, 9.0, 'Tokyo, Seoul, Osaka, Sapporo, Yakutsk'),
(27, 9.5, 'Adelaide, Darwin'),
(28, 10.0, 'Eastern Australia, Guam, Vladivostok'),
(29, 11.0, 'Magadan, Solomon Islands, New Caledonia'),
(30, 12.0, 'Auckland, Wellington, Fiji, Kamchatka');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
