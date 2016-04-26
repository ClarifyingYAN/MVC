<?php
/**
 * 路由文件
 */
class Route
{
	// 实现路由
	public function __construct() {
		$u = $this->url_parse();
		$moduel = $this->moduel_arr($u[0]);
		if (count($u)>1) {
			$this->arr_get($u[1]);
		}
		call_user_func_array(array('Route', 'call'), $moduel);
	}

	// url解析
	private function url_parse() {
		$r = array();	// 返回值为array
		$url = $_SERVER['REQUEST_URI'];
		if (preg_match("/\\?/", $url)) {
			$r = preg_split("/\\?/", $url);
			return $r;
		} else {
			$r = array($url);
			return $r;
		}

	}

	// 解析url返回数组函数$moduel;
	private function moduel_arr($url) {

		// 判断url为'/MVC/'
		if ($url == '/MVC/') {
			$url .= 'index.php/';
		}

		// 如果url为'/MVC/index.php'
		if ($url == '/MVC/index.php') {
			$url .= '/';
		}

		// 如果url为'/MVC/index.php/...(ModuelName)/...(ControllerName)/...(ActionName)'
		$script_name = $_SERVER['SCRIPT_NAME'] . '/';
		$new_url = str_replace($script_name, '', $url);	// 取出url中index.php后的内容
		$moduel = array(); // 存储读取的mvc的数组
		$moduel = explode('/', $new_url);
		if ($moduel[0] == '') {
			$moduel[0] = 'Home';
		}
		return $moduel;

	}

	// 解析mvc,调用方法
	private function call($Moduel, $Controller = 'Index', $Action = 'index') {
		$nc = $Controller . 'Controller';
		$m_d = './' . $Moduel;	// moduel_dir
		$c_f = $m_d . '/Controller' . '/' . $Controller . 'Controller' . '.class.php'; // controller_file

		// 判断模块是否存在
		if(!file_exists($m_d)) {
			echo 'the moduel doesn\'t exists';
			return false;
		}

		// 判断控制器文件是否存在
		if (!file_exists($c_f)) {
			echo 'the controller doesn\'t exists';
			return false;
		}

		require_once $c_f;	// 引入控制器文件
		$c = new $nc;	// 实例化控制器类

		// 判断类中的该方法是否存在
		if (!method_exists($c, $Action)) {
			echo 'the method doesn\'t exists';
			return false;
		}

		$c -> $Action();	// 调用方法;
	}

	// 解析url返回GET数组
	private function arr_get($url) {
		if (preg_match("/&/", $url)) {
			$arr = preg_split("/&/", $url);
			for ($i=0; $i < count($arr)-1; $i++) {
				$a = preg_split("/=/", $arr[$i]); // array('name', 'value')
				$_GET[$a[0]] = $a[1];
			}
		}
	}

}