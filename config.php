<?php
define ('DEBUG', 0);
define ('ALWAYS_PASS_CAPTCHA', 0);
define ('_MODULES_CONFIGURATION_BASE_PATH', dirname(__FILE__));
define ('_MODULES_CONFIGURATION_SITE_PATH', _MODULES_CONFIGURATION_BASE_PATH);
define ('_MODULES_CONFIGURATION_INCLUDE_PATH', dirname(__FILE__) . '/include');
define ('_MODULES_CONFIGURATION_TEMPLATE_CACHE_CONTROL', '?template=av-240&colorScheme=green&header=headers1&button=buttons1');
define ('_MODULES_CONFIGURATION_SITE_API_PATH', _MODULES_CONFIGURATION_SITE_PATH.'/data/settings/api.php');
define ('_MODULES_CONFIGURATION_SITE_PASSWORD_PATH', _MODULES_CONFIGURATION_SITE_PATH.'/data/settings/pwd.php');
define ('_MODULES_CONFIGURATION_SITE_LANG', 'pt_BR');
define ('_MODULES_CONFIGURATION_STORAGE_ATTACH_PATH', 'data/storage/attachments');
define ('_MODULES_CONFIGURATION_STORAGE_TYPE', 'publish');
define ('_MODULES_CONFIGURATION_TMP_PATH', _MODULES_CONFIGURATION_BASE_PATH.'/data/tmp');
define ('_MODULES_CONFIGURATION_TMP_URL', 'data/tmp/');
define ('_MODULES_CONFIGURATION_SETTINGS_PATH', _MODULES_CONFIGURATION_SITE_PATH.'/data/settings');
define ('_MODULES_CONFIGURATION_SETTINGS_ATTACHMENTS_BASE_URL', '');
$_configurationModulesInstance = array (
	'9twhg67fm0k' => array (
		'TRANSPORT' => 'direct',
		'STORAGE_ATTACHMENTS_BASE_URL' => '',
		'STORAGE_BASE_PATH' => _MODULES_CONFIGURATION_BASE_PATH,
		'STORAGE_DB_DSN' => 'sqlite:///'._MODULES_CONFIGURATION_BASE_PATH.'/data/storage/sb_modules.php',
		'MODULE_NAME' => 'Galeria de Imagens',
		'VERSION' => '4.5.0',
		'REQUIRED_API_VERSION' => '4.5.0',
	),
);
define ('LOCALE_DECIMAL_POINT', '.');
define ('LOCALE_MONETARY_UNIT_CODE', 'BRL');
define ('LOCALE_CURRENCY_SYMBOL_LEFT', 'R$');
define ('LOCALE_CURRENCY_SYMBOL_RIGHT', 'R$');
define ('LOCALE_DATE_FORMAT', 'Y/m/d');
define ('LOCALE_TIME_FORMAT', 'H:i:s');
define ('LOCALE_WEEK_BEGIN', '1');
date_default_timezone_set('America/Sao_Paulo');
?>