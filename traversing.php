<?php
/**
 * 实时检测文件是否有变化，有变化则重启服务
 */
date_default_timezone_set('Asia/Shanghai');
function traversing($dir) {
    if(@$handle = opendir($dir)) {
        while(($file = readdir($handle)) !== false) {
            if($file != ".." && $file != "." && !in_array($file,['.git','.gitignore','.idea'])) {
                if(is_dir($dir."/".$file)) { //如果是子文件夹，就进行递归
                    traversing($dir."/".$file);
                } else {
                    $t = filemtime($dir."/".$file);//获取文件修改时间
                    if(time()-$t < 2){
                        exec('php easyswoole reload');
                        return;
                    }
                }

            }
        }
        closedir($handle);
    }
}
swoole_timer_tick(1000,function(){
    traversing(__DIR__);
});
