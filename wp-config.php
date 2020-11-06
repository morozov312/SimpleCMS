<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'simpleCMS' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'admin' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', 'admin' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'b:)XJv$]= rOq2&fbY5b*]FHBpG`t@]~lmN!TJX{l$:u^!A|7uF%<!`Y}cO*eEfb' );
define( 'SECURE_AUTH_KEY',  'moM>7cey>F>W8P*WxRhjQS/cD{4~Dv}L,=uR#?0D2_}$xmxJ|WPqZs&7En;k<v`Q' );
define( 'LOGGED_IN_KEY',    '(3;(`QGh*Y?/m bxf^j5af48 ~][rpMkD^&<bfzQkN*o#{o-8L4#/,|3W(6~0B(A' );
define( 'NONCE_KEY',        ':aiJ!Lod_qBTHKW&p|u5eDJ0Fv8CY9/S{ma/C&!ism(z3_&lTn>VbH&mB4,LG>1F' );
define( 'AUTH_SALT',        '{]g,xot%ZBTf9^MoGFLs.BMx.(UNH`LK%qyIoHWVgjGv$Sc=6pEF^awhjM]^9]*/' );
define( 'SECURE_AUTH_SALT', 'ERU<TPrziziPNZK~r>fsDW,ub;Bi!A@#wM[ q7ogL=+|Nrk.dXMC2/T=H:y+#0_e' );
define( 'LOGGED_IN_SALT',   '^:`mR-t5t|e54 &f$Z2:C36QRy^w#1Oew#W~:$mtYBR7Q6241-i,FW)O+nJ=3gw+' );
define( 'NONCE_SALT',       '=<6v;EVfkI6wN-=||@,*vFI~is^wUHCm#a3N S%+<>vE5ZPo?7M<[dvQ&CO;<#1-' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once( ABSPATH . 'wp-settings.php' );
