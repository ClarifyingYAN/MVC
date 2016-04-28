<?php
/**
 * 模版解析类
 */
class TplParser
{
	private $reg = '/\{(\$[a-zA-Z]\w+)\}/';					// 变量正则匹配规则
	public $vars = array();			// 变量数组 vars('varname' => 'value')
	private $path = './Home/View/';		// 模版路径
	private $contents = NULL;
	// private $file_suffix = '.html';		// 文件名后缀

	// 模版解析
	// public function __construct($filename) {
	// 	$filename = './Home/View/' . $filename;
	// 	$this->findFile($filename);
	// }

	// 寻找模版文件
	private function findFile($filename) {
		if (file_exists($filename)) {
			$contents = file_get_contents($filename);	// 获取模版内容
			$this->reg_match($contents);
		} else {
			echo '不存在模版';
		}
	}

	// 正则匹配替换
	private function reg_match($contents) {
		if (preg_match($this->reg, $contents)) {
			$contents = preg_replace($this->reg, '<?php echo $1; ?>', $contents);
			$this->contents = $contents;
		}
	}

	// 变量注册
	public function assign($vars) {
		if (is_array($vars)) {
			foreach ($vars as $k => $v) {
				$this->vars[$k] = $v;
			}
		} else {
			echo '请按格式输入(必须传入数组)';
		}
	}

	public function display($modelname = 'index.html') {	// 在固定文件夹下的模版
		// 注入变量
		extract($this->vars);
		$filename = $this->path . $modelname;	// 模版下的模版文件

		$this->findFile($filename);				// 开始查找文件，直到替换完成

		$contents = $this->contents;

		$cache_dir = './Home/Cache/';
		file_put_contents($cache_dir . basename($modelname, '.html') . '.php', $contents);
		include $cache_dir . basename($modelname, '.html') . '.php';
	}

}