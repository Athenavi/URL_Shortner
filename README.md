# URL_Shortner
网址缩短，短链

1.请为此项目安装创建一个数据库，导入sql文件

2.配置config.ini

3.配置nginx伪静态
````
location ~* "^/([a-zA-Z0-9]{6})$" {
        rewrite "^/([a-zA-Z0-9]{6})$" /index.php?shorturl=$1 last;
    }
    
    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }
````