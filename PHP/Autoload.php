<?php
/**
* 自动加载类
*/
class Autoload
{
	// private $extension = '.php'; // 后缀
	// private $config = array();

	// 引入Config文件
	// private function importConfig() {
	// 	$this->config = $this->inlcudeFile('config.php');
	// }

	// 寻找文件
	public function findFile($classes) {
		$files = scandir('./PHP/');
		if (in_array($classes . '.php', $files)) {	// 判断是否有要实例化的类
			foreach ($files as $filename) {
				if ($filename != '.' && $filename != '..' && $filename != 'YanPHP.php') {
					$this->includeFile($filename);
				}
			}
		} else {
			echo '没有' . $classes . '类文件';
			return false;
		}
	}

	// 引入文件
	private function includeFile($filename = '') {
		$filename = './PHP/' . $filename;	// 目前只指定引入'./PHP/'下的文件
		if(file_exists($filename)) {
			return include_once($filename);
		}
		echo $filename . "文件不存在";
	}

	// 实现自动加载
	public function __construct() {
		spl_autoload_register(function($classes) {
			$this->findFile($classes);
		});
	}
}