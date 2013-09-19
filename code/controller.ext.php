<?php

/**
 *
 * ZPanel - A Cross-Platform Open-Source Web Hosting Control panel.
 * 
 * @package ZPanel
 * @version $Id$
 * @author Bobby Allen - ballen@zpanelcp.com
 * @copyright (c) 2008-2011 ZPanel Group - http://www.zpanelcp.com/
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License v3
 *
 * This program (ZPanel) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
class module_controller {

    static $phpcache;

    static function getModuleName() {
        $module_name = ui_module::GetModuleName();
        return $module_name;
    }

    static function getModuleIcon() {
        global $controller;
        $module_icon = "modules/" . $controller->GetControllerRequest('URL', 'module') . "/assets/icon.png";
        return $module_icon;
    }

    static function getModuleDesc() {
        $message = ui_language::translate(ui_module::GetModuleDescription());
        return $message;
    }
	
	static function ExecuteSavePassword($apcUserName, $apcPassword) {
		global $controller;
		$myFile = "/etc/zpanel/panel/modules/apc_module/code/accode.php";
		$fh = fopen($myFile, 'w+') or die("can't open file");
		$stringData = "<?php\n";
		fwrite($fh, $stringData);
		$stringData = "include ('noaccess.php');\n";
		fwrite($fh, $stringData);
		$stringData = "defaults('ADMIN_USERNAME','$apcUserName');\n";
		fwrite($fh, $stringData);
		$stringData = "defaults('ADMIN_PASSWORD','$apcPassword');\n";
		fwrite($fh, $stringData);
		$stringData = "?>\n";
		fwrite($fh, $stringData);
	}
	
	static function ExecuteSaveMemoryCache($apcMemID) {
		global $controller;
		$myFile = "/etc/zpanel/panel/modules/apc_module/code/apc.ini";
		$fh = fopen($myFile, 'w+') or die("can't open file");
		$stringData = "apc.shm_size=$apcMemID\n";
		fwrite($fh, $stringData);
		$stringData = "extension = apc.so\napc.enabled=1\napc.shm_segments=1\napc.num_files_hint=1024\napc.user_entries_hint=4096\napc.ttl=7200\napc.use_request_time=1\napc.user_ttl=7200\napc.gc_ttl=3600\napc.cache_by_default=1\napc.filters\napc.mmap_file_mask=/tmp/apc.XXXXXX\napc.file_update_protection=2\napc.enable_cli=0\napc.max_file_size=1M\napc.stat=1\napc.stat_ctime=0\napc.canonicalize=0\napc.write_lock=1\napc.report_autofilter=0\napc.rfc1867=0\napc.rfc1867_prefix =upload_\napc.rfc1867_name=APC_UPLOAD_PROGRESS\napc.rfc1867_freq=0\napc.rfc1867_ttl=3600\napc.include_once_override=0\napc.lazy_classes=0\napc.lazy_functions=0\napc.coredump_unmap=0\napc.file_md5=0\napc.preload_path\n";
		fwrite($fh, $stringData);

	}

    static function doSavePassword() {
		global $controller;
		$formvars = $controller->GetAllControllerRequests('FORM');
		if (isset($formvars['apcUserName']) && isset($formvars['apcPassword'])){
        	if (self::ExecuteSavePassword($formvars['apcUserName'], $formvars['apcPassword'])) {
            	return true;
        	} else {
            	return false;
        	}
		}
    }

    static function doSaveMemoryCache() {
		global $controller;
		$formvars = $controller->GetAllControllerRequests('FORM');
		if (isset($formvars['apcMemID'])){
        	if (self::ExecuteSaveMemoryCache($formvars['apcMemID'])) {
            	return true;
        	} else {
            	return false;
			sleep(5);
		shell_exec('sudo service httpd restart');
		$message ("Apache service restarted");
        	}
		}
    }

}

?>
