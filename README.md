# beacon-web
The \"Beacon Standard Edition\" distribution
### Beacon 介绍

这是一个PHP快速开发框架,轻量级简单高效安全的 CMF 框架。
文档会在有空的时间陆续更新....
加星是我的动力。
如果有问题可以 issues 我。

#### 环境要求 

php 7.0 及以上，我的开发环境是php 7.2
如果是 nginx 请自行设置支持 pathinfo

mysql 是 5.7 及以上。

#### 需要设置 url 重写，指向 index.php


网站目录应该指向 /www
/www/index.php 是入口文件。

补充 nginx 配置

```
server {
        listen       8082;
        server_name  localhost;
        location / {
            root   /cygdrive/e/works/php/beacon/www;
            index  index.html index.htm index.php;
			      if (!-e $request_filename){
				      rewrite ^(.*)$ /index.php$1 last;
			      }
        }
        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   html;
        }
        location ~ \.php(/.+)?$ {
            root           /cygdrive/e/works/php/beacon/www;
            set $path_info "";
            set $real_script_name $fastcgi_script_name;
            if ($fastcgi_script_name ~ "^(.+?\.php)(/.+)$") {
                set $real_script_name $1;
                set $path_info $2;
            }
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
			      fastcgi_param SCRIPT_FILENAME /cygdrive/e/works/php/beacon/www$real_script_name;
            fastcgi_param SCRIPT_NAME $real_script_name;
            fastcgi_param PATH_INFO $path_info;
            include     fastcgi_params;
        }
    }
```


debug.php 需要用命令行运行，最好是在 phpstrom 中直接右键运行，有意外不到的收获。

`php debug.php`

调试日志将在命令行中打印出来.


