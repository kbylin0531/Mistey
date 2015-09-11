注意：
   ①项目中之一写明变量类型等详细注释，这有帮助于编辑器检查变量类型，可以帮助避免低级错误
   ②获取可变参数的第二种方法
       function sum(...$numbers) {
            $acc = 0;
            foreach ($numbers as $n) {
                $acc += $n;
            }
            return $acc;
        }
        echo sum(1, 2, 3, 4);//输出 10
-
        //...可用于数组解包
        function add($a, $b) {
            return $a + $b;
        }
        echo add(...[1, 2])."\n";//输出 3
        $a = [1, 2];
        echo add(...$a);//输出 3
   ☂OB缓存有一定的大小，如果超出默认的4096字节则直接输出的浏览器，这时候使用ob_end_clean();不能达到隐藏的效果
   ④项目有配置文件集中在Configure目录可以依据目录更新时间决定是否重新加载Runtime目录下的集中配置文件
   ⑤在线把图片转换成Base64 ，网址：http://imgbase64.duoshitong.com/
   ⑥模板引擎选自smarty无变动，如果需要更新smarty版本，到目录"System\Extension\smarty"下替换
   ⑦exit(12)同样表示为终止程序的作用，但没有输出脚本值，因为如果exit函数的形参为整形数据,那么就代表一个退出的状态号，退出状态号的标准取值范围是：0-254之间，所以exit(12)也表示终止程序的作用。用整形数据的状态用法为：exit(0-254)；终止并输出脚本的用法为：exit("终止程序")；学习愉快！！！
   ⑧获取静态方法调用的类名称使用get_called_class,对象用get_class
   ⑨
        //测试strrposde得到结论:从前往后是从0开始的，从后往前是-1开始的
        //        Util::dump(strrpos('bsabab','ab'));//4
        //        Util::dump(strrpos('bsabab','ab',-1));//4
        //        Util::dump(strrpos('bsabab','ab',-2));//4
        //        Util::dump(strrpos('bsabab','ab',-3));//2
        //        Util::dump(strrpos('bsabab','ab',-4));//2



