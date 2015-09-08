<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/8/30
 * Time: 15:37
 * Modified from ThinkPHP Template Engine
 */
namespace System\Core\TemplateDriver;
use System\Core\ConfigHelper;
use System\Core\Storage;
use System\Core\View;
use System\Exception\FileNotFoundException;
use System\Exception\StorageSystem\DirentNotExistsException;
use System\Exception\Template\ParseTagException;
use System\Exception\Template\XMLReadFailedException;
use System\Utils\Util;

/**
 * Class ShuttleDriver 框架默认模板引擎（修改ThinkPHP的模板引擎）
 * @package System\Core\TemplateDriver
 */
class ThinkDriver extends TemplateDriver{

    /**
     * 模板中内容不替换标记
     */
    const NO_LAYOUT_TAG = '{__NOLAYOUT__}';
    /**
     * 内容替换标记
     */
    const DO_LAYOUT_TAG = '{__CONTENT__}';

    /**
     * 标签库开始标记
     */
    const TAGLIB_BEGIN_TAG = '<';
    /**
     * 标签库结束标记
     */
    const TAGLIB_END_TAG = '>';
    /**
     * 存放模板变量
     * @var array
     */
    private $tVars = array();
    /**
     * 模板文件存放目录
     * @var string
     */
    protected $_tpl_dir = null;

    /**
     * 模板文件编译后的存放目录
     * 不同主题的模板文件也会存在于该目录下
     * @var string
     */
    protected $_tpl_compile_dir = null;

    /**
     * 静态缓存的输出文件存放目录
     * @var string
     */
    protected $_tpl_static_dir = null;
    /**
     * 布局文件的目录
     * @var string
     */
    private static $_tpl_layout_dir = null;

    /**
     * 当前使用的模板文件的路径
     * @var string
     */
    protected $_cur_tpl_file = null;

    protected static $_config = array(
        /**
         * 是否开启模板布局
         * @ThinkPHP LAYOUT_ON
         */
        'TEMPLATE_LAYOUT_ON'    => false,
        /**
         * 模板布局文件名称
         * 布局文件应当存放到标准的Resourse/template_layout目录下(全站布局)
         * 另外控制器中可以调用View的setLayoutName方法设置当前控制器的默认布局文件名称(控制器布局)
         * @ThinkPHP LAYOUT_NAME
         */
        'TEMPLATE_LAYOUT_FILENAME'  => null,
        /**
         *模板布局文件存放目录
         * 位置相对于于根目录的布局
         */
        'TEMPLATE_LAYOUT_DIR'       => 'Resourse/template_layout/',
        /**
         * 模板布局文件默认后缀
         * @ThinkPHP TMPL_CACHFILE_SUFFIX
         */
        'TEMPLATE_LAYOUT_SUFFIX'    => '.htm',
        /**
         * 模板禁用的函数列表
         */
        'TEMPLATE_DENY_FUNCTIONS'       => array('echo','exit'),
        /**
         * 是否运村php代码
         */
        'TEMPLATE_PHP_ALLOW'            => true,
        /**
         * 获取需要引入的标签库列表
         * 标签库只需要定义一次，允许引入多个一次
         * 一般放在文件的最前面
         * 格式：<taglib name="html,mytag..." />
         * 当TAGLIB_LOAD配置为true时才会进行检测
         */
        'TAGLIB_ALLOW_LOAD'             => true,
        /**
         * 与架子啊的标签库
         */
        'TAGLIB_PRE_LOAD'               => array(),

        /**
         * 模板变量分隔符
         */
        'TEMPLATE_VAR_L_DEPR'           => '{',
        'TEMPLATE_VAR_R_DEPR'           => '}',

        /**
         * 自定义模板常量
         */
        'TEMPLATE_CONST'                => array(
            '__VERSION__'       => '0.0831'
        ),

        'TEMPLATE_VAR_IDENTIFY'             => 'array',// 模板变量识别。留空自动判断,参数为'obj'则表示对象
    );

    /**
     * 用于记录标签内部block的内容
     * @var array
     */
    private $_blocks = array();

    /**
     * 临时存放literal标签
     * @var array
     */
    private $_literal = array();

    /**
     * 需要加载的标签库
     * @var array
     */
    private $_tagLib = array();

    private $tempVar = array();

    private $_context = null;
    /**
     * 初始化模板驱动类
     * @param array $context 控制器上下文
     * @throws \System\Exception\ConfigLoadFailedException
     */
    public function __construct($context){
        //加载并应用配置
        Util::mergeConf(self::$_config,ConfigHelper::loadConfig('template'));
        if(null === self::$_tpl_layout_dir){
            self::$_tpl_layout_dir = BASE_PATH.self::$_config['TEMPLATE_LAYOUT_DIR'];
        }
        $this->_context = $context;
    }

    /**
     * 设置模板存放目录
     * @Override
     * @param string $path 目录路径
     * @return $this
     * @throws DirentNotExistsException
     */
    public function setTemplateDir($path){
        if(is_dir($path)){
            $this->_tpl_dir = $path;
        }else{
            throw new DirentNotExistsException($path);
        }
        return $this;
    }
    /**
     * 设置模板编译后存放位置
     * @Override
     * @param $path
     * @return $this
     * @throws DirentNotExistsException
     */
    public function setCompileDir($path){
        $this->_tpl_compile_dir = $path;
        return $this;
    }
    /**
     * 设置模板静态文件存放目录
     * @Override
     * @param $path
     * @return $this
     * @throws DirentNotExistsException
     */
    public function setCacheDir($path){
        $this->_tpl_static_dir = $path;
        return $this;
    }
    /**
     * 分配模板变量
     * @Override
     * @param string|array $tpl_var string时分配当个模板变量，为数组时批量分配
     * @param mixed $value
     * @param bool $cache
     * @return $this 允许链式调用
     */
    public function assign($tpl_var, $value = null, $cache = false){
        if(is_array($tpl_var)){
            $this->tVars = array_merge($this->tVars,$tpl_var);
        }else{
            $tpl_var and $this->tVars[$tpl_var] = $value;
        }
        return $this;
    }
    /**
     * 获取分配的变量
     * @param null|string $tpl_var 参数一未设置或者为null时，返回全部分配的变量
     * @return mixed
     */
    public function get($tpl_var=null){
        return isset($tpl_var)?$this->tVars[$tpl_var]:$this->tVars;
    }

    /**
     * 获取配置信息
     * @param $name
     * @return null
     */
    public function config($name){
        return isset(self::$_config[$name])?self::$_config['name']:null;
    }

    /**
     * 显示模板
     * 是否显示缓存由View决定
     * @Override
     * @param string $template 模板文件名称(包含后缀不含目录)，当文件不存在时抛出异常
     * @param null $cache_id
     * @param null $compile_id
     * @param null $parent
     * @return string 缓存模板文件路径
     * @throws FileNotFoundException
     */
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null){
        //获取模板文件的路径和内容
        $template_file = $this->_tpl_dir.$template;
//        util::dump($this->_tpl_dir,$template,$template_file,$this->tVars);exit;
        if(!is_file($template_file)){
            throw new FileNotFoundException($this->_cur_tpl_file);
        }

        //模板编译后的文件
        $compiled_file = $this->_tpl_compile_dir.md5($this->_cur_tpl_file).'.php';

        //文件存在，不编译
        if(!Storage::hasFile($compiled_file)){

            //模板文件内容获取
            $this->_cur_tpl_file = $template_file;
            $file_content = Storage::readFile($this->_cur_tpl_file);

            //解析模板布局
            if(self::$_config['TEMPLATE_LAYOUT_ON']){
                //是否单独脱离全局模板布局
                if(false !== strpos($file_content,self::NO_LAYOUT_TAG)){
                    $file_content = str_replace(self::NO_LAYOUT_TAG,'',$file_content);//删除标记
                }else{
                    //获取布局模板
                    $tpl_layout_file = self::$_config['TEMPLATE_LAYOUT_DIR'].
                        self::$_config['TEMPLATE_LAYOUT_FILENAME'].
                        self::$_config['TEMPLATE_LAYOUT_SUFFIX'];
                    if(!is_file($tpl_layout_file)){
                        throw new FileNotFoundException($tpl_layout_file);
                    }
                    //替换布局文件名称
                    $file_content = str_replace(self::DO_LAYOUT_TAG,$file_content,$tpl_layout_file);
                }
            }

            //编译模板内容(如果含有模板布局文件，同样编译)
            $file_content = $this->compile($file_content);
            Storage::writeFile($compiled_file,$file_content);
        }
        extract($this->tVars);
        include_once $compiled_file;
    }

    /**
     * 将读取到的内容编译
     * @param string $file_content 模板内容
     * @return string
     */
    private function compile($file_content){
        //解析模板内容
        $this->parse($file_content);

        // 还原被替换的Literal标签,参数二为false表示还原
        $this->switchTagLiteral($file_content,false);

        // 添加安全代码
        $file_content =  '<?php defined(\'BASE_PATH\') or die(\'No Permission!\'); ?>'.$file_content;

        // 优化生成的php代码(两端PHP代码块之间消除间隔)
        $file_content = str_replace('?><?php','',$file_content);

        // 模版编译过滤标签
        $this->templateConstReplace($file_content);
        return self::stripWhitespace($file_content);
    }

    /**
     * 解析模板内容
     * @param string $file_content 模板内容
     * @return string
     * @throws \Exception
     */
    public function parse(&$file_content){
        //如果是空字符串，直接返回
        if(is_string($file_content) and !trim($file_content)){//内容为空，不解析
            return $file_content = '';
        }

        // 检查include标签
        $this->parseTagInclude($file_content);
//        Util::dump($file_content);exit;
        // 检查PHP语法
        $this->parseNativePHP($file_content);

//        Util::dump($file_content);exit;
        //记录literal标签下的代码
        $this->switchTagLiteral($file_content,true);

        //加载标签库列表,允许在模板中动态添加
        if(self::$_config['TAGLIB_ALLOW_LOAD']) {
            $this->getIncludeTagLib($file_content);
            if(!empty($this->_tagLib)) {
                // 对导入的TagLib进行解析
                foreach($this->_tagLib as $tagLibName) {
                    $this->parseTagsUsingTaglib($tagLibName,$file_content);
                }
            }
        }
        // 预先加载的标签库 无需在每个模板中使用taglib标签加载 但必须使用标签库XML前缀
        if(self::$_config['TAGLIB_PRE_LOAD']) {
            foreach (self::$_config['TAGLIB_PRE_LOAD'] as $tag){
                $this->parseTagsUsingTaglib($tag,$file_content);
            }
        }
        // 内置标签库 无需使用taglib标签导入就可以使用 并且不需使用标签库XML前缀
        $this->parseTagsUsingTaglib('cx',$file_content,true);
        //解析普通模板标签 {$tagName}
        $conf = &self::$_config;
        $file_content = preg_replace_callback(
            "/({$conf['TEMPLATE_VAR_L_DEPR']})([^\\d\\w\\s{$conf['TEMPLATE_VAR_L_DEPR']}{$conf['TEMPLATE_VAR_R_DEPR']}].+?)({$conf['TEMPLATE_VAR_R_DEPR']})/is",
            array($this, 'parseTag'),$file_content);
//        Util::dump($file_content);exit;
        return $file_content;
    }
    /**
     * 解析模板中的include标签
     * @param $content
     * @param bool $extend
     * @return void
     * @throws ParseTagException
     * @throws XMLReadFailedException
     */
    protected function parseTagInclude(&$content, $extend = true) {
        static $_increg = null;
        isset($_increg) or $_increg = '/'.self::TAGLIB_BEGIN_TAG.'include\s*?(.+?)\s*?\/'.self::TAGLIB_END_TAG.'/is';
        // 解析继承
        $extend and $this->parseTagExtend($content);

        // 解析布局
        $this->parseTagLayout($content);

        $matches = null;
        //获取匹配次数
        $count = preg_match_all($_increg,$content,$matches);
        if($count) {
            for($i=0;$i<$count;$i++) {
                $include = $matches[1][$i];//各个include的属性内容
                $attrs = Util::readXmlAttrs($include);
                $file = $attrs['file'];
                unset($attrs['file']);
                //对每个include标签全部内容进行替换
                $content = str_replace($matches[0][$i],$this->walkIncludeItem($file,$attrs,$extend),$content);
            }
        }
    }
    /**
     * 解析模板中的extend标签
     * @param string $content 模板内容
     * @return string
     * @throws ParseTagException
     */
    private function parseTagExtend(&$content){
        static $entendreg = null;
        static $blockreg = null;
        isset($entendreg) or $entendreg = '/'.self::TAGLIB_BEGIN_TAG.'extend\s*?(.+?)\s*?\/'.self::TAGLIB_END_TAG.'/is';
        isset($blockreg) or $blockreg = '/'.self::TAGLIB_BEGIN_TAG.'block\sname=[\'"](.+?)[\'"]\s*?'.
            self::TAGLIB_END_TAG.'(.*?)'.self::TAGLIB_BEGIN_TAG.'\/block'.self::TAGLIB_END_TAG.'/is';

        //匹配是否存在entend标签
        $matches = null;
        $has = preg_match($entendreg,$content,$matches);
//        Util::dump($content,$matches);exit;
        if(false === $has){
            throw new ParseTagException('extend',$content);
        }else{
            if($has){
                //清空掉extend标签(整个标签)内容
                $content = str_replace($matches[0],'',$content);
                //处理本模板文件中的block标签,其他的内容将全部被忽略
                preg_replace_callback($blockreg, function($match){
                    if(is_array($match)){
                        $this->_blocks[$match[1]] = $match[2];
                    }
                    return '';
                },$content);//调用parseBlock函数，传入$matches参数

                //读取entend的name属性所指向的资源文件的内容
                $attrs = Util::readXmlAttrs($matches[1]);
                //读取extend的属性name指向的资源模板文件，读取其内容
                $content = $this->getIncludeContent($attrs['file']);
                //解析模板内部的include标签
                $this->parseTagInclude($content, false); //对继承模板中的include进行分析，不支持多重继承
                //用记录的block内容替换block标签替换block标签
                $content = $this->replaceBlock($content);
//                Util::dump($content);exit;
            }else{
                //删除所有的block标签，内部的内容直接取出（摘掉了block这个难看的外壳）
                $content = preg_replace_callback(
                    $blockreg,
                    function($match){
                        //标签内容返回
                        return stripslashes($match[2]);//去除反引号
                    },
                    $content);
            }
        }
    }
    /**
     * 替换继承模板中的block标签
     * @access private
     * @param string $content  模板内容
     * @return string|array
     */
    private function replaceBlock($content){
        static $parse = 0;
        static $strReg = null;
        static $arrReg = null;
        isset($strReg) or $strReg = '/('.self::TAGLIB_BEGIN_TAG.'block\s*?name=[\'"](.+?)[\'"]\s*?'.
                self::TAGLIB_END_TAG.')(.*?)'.self::TAGLIB_BEGIN_TAG.'\/block'.self::TAGLIB_END_TAG.'/is';
        isset($arrReg) or $arrReg = '/'.self::TAGLIB_BEGIN_TAG.'block\s*?name=[\'"](.+?)[\'"]\s*?'.
                self::TAGLIB_END_TAG.'/is';

        if(is_string($content)){
            do{
                $content = preg_replace_callback($strReg, array($this, 'replaceBlock'), $content);
            } while ($parse && $parse--);//到0为止不再减，
        }elseif(is_array($content)){
            //将前面正则表达式匹配到的内容作替换
            if(preg_match($arrReg, $content[3])){  //存在嵌套，进一步解析
                $parse = 1;
                $content[3] = preg_replace_callback($strReg, array($this, 'replaceBlock'),
                    $content[3].self::TAGLIB_BEGIN_TAG.'/block'.self::TAGLIB_END_TAG);
                return $content[1] . $content[3];
            }else{//一般是不存在嵌套的情况
                $name    = $content[2];
                $content = $content[3];
                $content = isset($this->_blocks[$name]) ? $this->_blocks[$name] : $content;
            }
        }
        return $content;
    }

    /**
     * 临时存储literal标签内容
     * @param string $content 模板内ring
     * @param bool $is_store true时保存并替换其中的literal标签，false时还原
     */
    public function switchTagLiteral(&$content,$is_store=true){
        if($is_store){
            $content = preg_replace_callback(
                '/'.self::TAGLIB_BEGIN_TAG.'literal'.self::TAGLIB_END_TAG.
                    '(.*?)'.
                    self::TAGLIB_BEGIN_TAG.'\/literal'.self::TAGLIB_END_TAG.'/is',
                function($matches){
                    if(is_array($matches)){
                        $matches = $matches[1];
                    }elseif(!trim($matches)){
                        return '';
                    }
                    $index = count($this->_literal);
                    $parseStr = "<!--###literal{$index}###-->";
                    $this->_literal[$index] = stripslashes($matches);
                    return $parseStr;
                },$content);
        }else{
            $content = preg_replace_callback('/<!--###literal(\d+)###-->/is',
                function($tag){
                    if(is_array($tag)) $tag = $tag[1];
                    $parseStr = $this->_literal[$tag];
                    unset($this->_literal[$tag]);
                    return $parseStr;
                },
                $content);
        }
    }
    /**
     * TagLib库解析
     * @access public
     * @param string $tagLib 标签库类名称
     * @param string $content 要解析的模板内容
     * @param boolean $hide 是否隐藏标签库前缀
     * @return string
     */
    public function parseTagsUsingTaglib($tagLib,&$content,$hide=false) {
        $begin = self::TAGLIB_BEGIN_TAG;
        $end   = self::TAGLIB_END_TAG;
        //标签库类名称
        $clsnm = 'System\\Core\\TemplateDriver\\ShuttleTagLib\\'.ucwords($tagLib);
        $tLib = new $clsnm($this->_context);
        $context = $this;

        //遍历该标签库的标签
        foreach ($tLib->getTags() as $name=>$val){
            $tags = array($name);
            if(isset($val['alias'])) {// 别名设置
                $tags       = explode(',',$val['alias']);
                $tags[]     =  $name;
            }
            $level      =   isset($val['level'])?$val['level']:1;
            $closeTag   =   isset($val['close'])?$val['close']:true;
            foreach ($tags as $tag){
                $parseTag = !$hide? $tagLib.':'.$tag: $tag;// 实际要解析的标签名称
                if(!method_exists($tLib,'_'.$tag)) {
                    // 别名可以无需定义解析方法
                    $tag  =  $name;
                }
                $n1 = empty($val['attr'])?'(\s*?)':'\s([^'.$end.']*)';
                $this->tempVar = array($tagLib, $tag);

                if (!$closeTag){
                    $patterns       = '/'.$begin.$parseTag.$n1.'\/(\s*?)'.$end.'/is';
                    $content        = preg_replace_callback($patterns, function($matches) use($tLib,$tag,$context){
                        return $context->parseXmlTag($tLib,$tag,$matches[1],$matches[2]);
                    },$content);
                }else{
                    $patterns       = '/'.$begin.$parseTag.$n1.$end.'(.*?)'.$begin.'\/'.$parseTag.'(\s*?)'.$end.'/is';
                    for($i=0;$i<$level;$i++) {
                        $content=preg_replace_callback($patterns,function($matches) use($tLib,$tag,$context){
                            return $context->parseXmlTag($tLib,$tag,$matches[1],$matches[2]);
                        },$content);
                    }
                }
            }
        }
    }


















    /**
     * 加载公共模板并缓存 和当前模板在同一路径，否则使用相对路径
     * @access private
     * @param string $include_path  包含的资源字符串，格式见如  ModuleA/ModuleB@Controller/action:theme
     * @param array $vars include标签的属性列表
     * @param bool $extend 是否遍历继承关系
     * @return string
     */
    private function walkIncludeItem($include_path,$vars=array(),$extend=true){
        //分析模板文件名并读取内容
        $parseStr = $this->getIncludeContent($include_path);
        //替换模板中指定的include参数，见文档传入参数
        foreach ($vars as $key=>$val) {
            $parseStr = str_replace("[{$key}]",$val,$parseStr);
        }
        //再次对包含文件进行模板分析
        $this->parseTagInclude($parseStr,$extend);
        return $parseStr;
    }
    /**
     * 去除代码中的空白和注释
     * @param string $content 代码内容
     * @return string
     */
    public static function stripWhitespace(&$content) {
        $stripStr   = '';
        //分析php源码
        $tokens     = token_get_all($content);
        $last_space = false;
        for ($i = 0, $j = count($tokens); $i < $j; $i++) {
            if (is_string($tokens[$i])) {
                $last_space = false;
                $stripStr  .= $tokens[$i];
            } else {
                switch ($tokens[$i][0]) {
                    //过滤各种PHP注释
                    case T_COMMENT:
                    case T_DOC_COMMENT:
                        break;
                    //过滤空格
                    case T_WHITESPACE:
                        if (!$last_space) {
                            $stripStr  .= ' ';
                            $last_space = true;
                        }
                        break;
                    case T_START_HEREDOC:
                        $stripStr .= "<<<Shutle\n";
                        break;
                    case T_END_HEREDOC:
                        $stripStr .= "Shutle;\n";
                        for($k = $i+1; $k < $j; $k++) {
                            if(is_string($tokens[$k]) && $tokens[$k] == ';') {
                                $i = $k;
                                break;
                            } else if($tokens[$k][0] == T_CLOSE_TAG) {
                                break;
                            }
                        }
                        break;
                    default:
                        $last_space = false;
                        $stripStr  .= $tokens[$i][1];
                }
            }
        }
        return $stripStr;
    }

    /**
     * 模板常量替换
     * @param string $content 模板内容
     * @return string 返回替换后的字符串
     */
    protected function templateConstReplace(&$content) {
        // 系统默认的特殊变量替换
        $replace =  array(
            '__ROOT__'      =>  __ROOT__,       // 当前网站地址
            '__MODULE__'    =>  __MODULE__,
            '__CONTROLLER__'=>  __CONTROLLER__,
            '__URL__'       =>  __CONTROLLER__,//不包含操作
            '__PUBLIC__'    =>  __ROOT__.'/Public',// 站点公共目录
        );
        // 允许用户自定义模板的字符串替换
        $replace =  array_merge($replace,self::$_config['TEMPLATE_CONST']);
        $content = str_replace(array_keys($replace),array_values($replace),$content);
    }



    /**
     * 解析标签库的标签
     * 需要调用对应的标签库文件解析类
     * @access public
     * @param object $tagLib  标签库对象实例
     * @param string $tag  标签名
     * @param string $attr  标签属性
     * @param string $content  标签内容
     * @return string|false
     */
    public function parseXmlTag($tagLib,$tag,$attr,$content) {
        if(ini_get('magic_quotes_sybase'))
            $attr   =   str_replace('\"','\'',$attr);
        $parse      =   '_'.$tag;
        $content    =   trim($content);
        $tags       =   $tagLib->parseXmlAttr($attr,$tag);
        return $tagLib->$parse($tags,$content);
    }
    /**
     * 搜索模板页面中包含的TagLib库
     * 并返回列表
     * @access public
     * @param string $content  模板内容
     * @return void
     */
    public function getIncludeTagLib(&$content) {
        //搜索是否有TagLib标签
        $matches = null;
        $find = preg_match('/'.self::TAGLIB_BEGIN_TAG.'taglib\s(.+?)(\s*?)\/'.self::TAGLIB_END_TAG.'\W/is'
            ,$content,$matches);
        if($find) {
            //替换TagLib标签
            $content = str_replace($matches[0],'',$content);
            //解析TagLib标签
            $array = Util::readXmlAttrs($matches[1]);
            $this->_tagLib = explode(',',$array['name']);
        }
    }

    /**
     * 检查PHP代码
     * @param $content
     * @return mixed
     * @throws \Exception
     */
    protected function parseNativePHP(&$content) {
        if(ini_get('short_open_tag')){
            // 开启短标签的情况要将短标签改为长标签
            $content = preg_replace('/(<\?(?!php|=|$))/i', '<?php echo \'\\1\'; ?>\n', $content );
        }
        //检查PHP在禁止的情况下是否存在php代码(通过判断'<?php'是否存在)
        if(!self::$_config['TEMPLATE_PHP_ALLOW'] and false !== strpos($content,'<?php')) {
//            Util::dump(self::$_config['TEMPLATE_PHP_ALLOW'],$content);exit;
            throw new \Exception('Template file deny the php code!');
        }
    }




    /**
     * 分析加载的模板文件并读取内容 支持多个模板文件读取
     * @access private
     * @param string $blocknames  模板文件名,如果是多个则以'，'分隔,如： 'blockA,blockB'
     * @return string
     */
    private function getIncludeContent($blocknames){
        if(0 === strpos($blocknames,'$')){
            //支持加载变量文件名,如： $name = 'blockA,blockB'
            $blocknames = $this->get(substr($blocknames,1));
        }

        $blocks  =   explode(',',$blocknames);
        $parseStr   =   '';
        foreach ($blocks as $blockname){
            if(empty($blockname)) continue;
            if(false === strpos($blockname,self::$_config['TEMPLATE_LAYOUT_SUFFIX'])) {
                // 解析规则为 见View::parseTemplatePath()方法注释
                $blockname = View::parseTemplatePath($blockname,true);//参数二为true，得到url字符串
            }
//            Util::dump($blockname,is_file($blockname));exit;
            // 获取模板文件内容
            $parseStr .= Storage::readFile($blockname);
        }
//        util::dump($parseStr);
        return $parseStr;
    }
    /**
     * 对模板变量使用函数
     * 格式 {$varname|function1|function2=arg1,arg2}
     * @access public
     * @param string $name 变量名
     * @param array $functions  函数列表
     * @return string
     */
    public function parseTemplateFunction($name,$functions){
        //对变量使用函数
        $length = count($functions);
        //取得模板禁止使用函数列表
        $template_deny_funs = self::$_config['TEMPLATE_DENY_FUNCTIONS'];
        for($i=0; $i<$length ;$i++ ){
            $args = explode('=',$functions[$i],2);
            //模板函数过滤
            $fun = trim($args[0]);
            switch($fun) {
                case 'default':  // 特殊模板函数
                    $name =  "(isset( {$name} ) && ( {$name} !== ''))? {$name} : {$args[1]}";
                    break;
                default:  // 通用模板函数
                    if(!in_array($fun,$template_deny_funs)){
                        if(isset($args[1])){
                            if(strstr($args[1],'###')){
                                $args[1] = str_replace('###',$name,$args[1]);
                                $name = "$fun($args[1])";
                            }else{
                                $name = "$fun($name,$args[1])";
                            }
                        }else if(!empty($args[0])){
                            $name = "$fun($name)";
                        }
                    }
            }
        }
        return $name;
    }


    /**
     * 系统变量解析
     * 格式 以 $Shuttle. 打头的变量属于特殊模板变量
     * @param string $varStr  变量字符串
     * @return string
     */
    public function parseTemplateVars($varStr){
        $vars = explode('.',$varStr);
        $vars[1] = strtoupper(trim($vars[1]));
        $parseStr = '';
        if(count($vars) >= 3){
            /**
             * 输出三段的
             * {$Think.server.script_name} // 输出$_SERVER['SCRIPT_NAME']变量
             * {$Think.session.user_id} // 输出$_SESSION['user_id']变量
             * {$Think.get.pageNumber} // 输出$_GET['pageNumber']变量
             * {$Think.cookie.name}  // 输出$_COOKIE['name']变量
             */
            $vars[2] = trim($vars[2]);
            switch($vars[1]){
                case 'SERVER':
                    $parseStr = '$_SERVER[\''.strtoupper($vars[2]).'\']';break;
                case 'GET':
                    $parseStr = '$_GET[\''.$vars[2].'\']';break;
                case 'POST':
                    $parseStr = '$_POST[\''.$vars[2].'\']';break;
                case 'COOKIE':
                    if(isset($vars[3])) {
                        $parseStr = '$_COOKIE[\''.$vars[2].'\'][\''.$vars[3].'\']';
                    }else{
                        $parseStr = 'cookie(\''.$vars[2].'\')';
                    }
                    break;
                case 'SESSION':
                    if(isset($vars[3])) {
                        $parseStr = '$_SESSION[\''.$vars[2].'\'][\''.$vars[3].'\']';
                    }else{
                        $parseStr = 'session(\''.$vars[2].'\')';
                    }
                    break;
                case 'ENV':
                    $parseStr = '$_ENV[\''.strtoupper($vars[2]).'\']';break;
                case 'REQUEST':
                    $parseStr = '$_REQUEST[\''.$vars[2].'\']';break;
                case 'CONST':
                    $parseStr = strtoupper($vars[2]);break;
                case 'LANG':
                    $parseStr = 'L("'.$vars[2].'")';break;
                case 'CONFIG':
                    isset($vars[3]) and $vars[2] .= '.'.$vars[3];
                    $parseStr = 'C("'.$vars[2].'")';break;
                default:break;
            }
        }else if(count($vars)==2){
            switch($vars[1]){
                case 'NOW':
                    $parseStr = "date('Y-m-d g:i a',time())";
                    break;
                case 'TEMPLATE':
                    $parseStr = "'".$this->_cur_tpl_file."'";//'C("TEMPLATE_NAME")';
                    break;
                case 'LDELIM':
                    $parseStr = 'C("TMPL_L_DELIM")';
                    break;
                case 'RDELIM':
                    $parseStr = 'C("TMPL_R_DELIM")';
                    break;
                default:
                    defined($vars[1]) and  $parseStr = $vars[1];
            }
        }
        return $parseStr;
    }

    /**
     * 解析模板中布局标签
     * @param $content
     * @return void
     * @throws ParseTagException
     */
    protected function parseTagLayout(&$content) {
        static $_reg = null;
        isset($_reg) or $_reg = '/'.self::TAGLIB_BEGIN_TAG.'layout\s*?(.+?)\s*?\/'.self::TAGLIB_END_TAG.'/is';
        //检测是否存在layout
        $has = preg_match($_reg,$content,$matches);
        if(false === $has){
            throw new ParseTagException('layout',$content);
        }else{
            if($has) {//存在布局标签
                //替换Layout标签
                $content    =   str_replace($matches[0],'',$content);
                //解析Layout标签
                $attrs      =   Util::readXmlAttrs($matches[1]);//布局标签的属性
                if(!self::$_config['TEMPLATE_LAYOUT_ON'] or //模板布局未开启
                    self::$_config['TEMPLATE_LAYOUT_FILENAME'] !=$attrs['name'] ) {//布局标签不等于默认的标签
                    //ThinkPHP则回去读取主题目录下的对应文件
                    throw new ParseTagException('layout','The Template has not open layout or layout file not exist!');
                }
            }else{
                //不存在布局标签，删除
                $content = str_replace(self::NO_LAYOUT_TAG,'',$content);
            }
        }
    }

    /**
     * 模板标签解析
     * 格式： {TagName:args [|content] }
     * @access public
     * @param string $tagStr 标签内容
     * @return string
     */
    public function parseTag($tagStr){
        if(is_array($tagStr)) $tagStr = $tagStr[2];
        //if (MAGIC_QUOTES_GPC) {
        $tagStr = stripslashes($tagStr);
        //}
        $flag   =  substr($tagStr,0,1);
        $flag2  =  substr($tagStr,1,1);
        $name   = substr($tagStr,1);
        if('$' == $flag && '.' != $flag2 && '(' != $flag2){ //解析模板变量 格式 {$varName}
            return $this->parseVar($name);
        }elseif('-' == $flag || '+'== $flag){ // 输出计算
            return  '<?php echo '.$flag.$name.';?>';
        }elseif(':' == $flag){ // 输出某个函数的结果
            return  '<?php echo '.$name.';?>';
        }elseif('~' == $flag){ // 执行某个函数
            return  '<?php '.$name.';?>';
        }elseif(substr($tagStr,0,2)=='//' || (substr($tagStr,0,2)=='/*' && substr(rtrim($tagStr),-2)=='*/')){
            //注释标签
            return '';
        }
        // 未识别的标签直接返回
        return self::$_config['TEMPLATE_VAR_L_DEPR'].$tagStr.self::$_config['TEMPLATE_VAR_R_DEPR'];
    }

    /**
     * 模板变量解析,支持使用函数
     * 格式： {$varname|function1|function2=arg1,arg2}
     * @access public
     * @param string $varStr 变量数据
     * @return string
     */
    public function parseVar($varStr){
        $varStr     =   trim($varStr);
        static $_varParseList = array();
        //如果已经解析过该变量字串，则直接返回变量值
        if(isset($_varParseList[$varStr])) return $_varParseList[$varStr];
        $parseStr   =   '';
//        $varExists  =   true;
        if(!empty($varStr)){
            $varArray = explode('|',$varStr);
            //取得变量名称
            $var = array_shift($varArray);
            if('Shuttle.' == substr($var,0,6)){
                // 所有以Think.打头的以特殊变量对待 无需模板赋值就可以输出
                $name = $this->parseTemplateVars($var);
            }elseif( false !== strpos($var,'.')) {
                //支持 {$var.property}
                $vars = explode('.',$var);
                $var  =  array_shift($vars);
                switch(strtolower(self::$_config['TEMPLATE_VAR_IDENTIFY'])) {
                    case 'array': // 识别为数组
                        $name = '$'.$var;
                        foreach ($vars as $key=>$val)
                            $name .= '["'.$val.'"]';
                        break;
                    case 'obj':  // 识别为对象
                        $name = '$'.$var;
                        foreach ($vars as $key=>$val)
                            $name .= '->'.$val;
                        break;
                    default:  // 自动判断数组或对象 只支持二维
                        $name = 'is_array($'.$var.')?$'.$var.'["'.$vars[0].'"]:$'.$var.'->'.$vars[0];
                }
            }elseif(false !== strpos($var,'[')) {
                //支持 {$var['key']} 方式输出数组
                $name = "$".$var;
                preg_match('/(.+?)\[(.+?)\]/is',$var,$match);
//                $var = $match[1];
            }elseif(false !==strpos($var,':') && false ===strpos($var,'(') && false ===strpos($var,'::') && false ===strpos($var,'?')){
                //支持 {$var:property} 方式输出对象的属性
//                $vars = explode(':',$var);
                $var  =  str_replace(':','->',$var);
                $name = "$".$var;
//                $var  = $vars[0];
            }else {
                $name = "$$var";
            }
            //对变量使用函数
            if(count($varArray)>0)
                $name = $this->parseTemplateFunction($name,$varArray);
            $parseStr = '<?php echo ('.$name.'); ?>';
        }
        $_varParseList[$varStr] = $parseStr;
        return $parseStr;
    }


}