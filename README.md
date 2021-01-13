# hyperf-soar

## 安装

~~~bash
# hyperf 1.* 
composer require wilbur/hyperf-soar:1.0 --dev

# hyperf 2.*
composer require wilbur/hyperf-soar:dev-master --dev
~~~

## 发布配置文件
[详细配置](https://github.com/XiaoMi/soar/blob/master/doc/config.md)
~~~
php bin/hyperf.php vendor:publish wilbur/hyperf-soar
~~~

## 下载 soar

~~~bash
# macOS
* wget https://github.com/XiaoMi/soar/releases/download/0.11.0/soar.darwin-amd64 -O vendor/bin/soar
# linux
* wget https://github.com/XiaoMi/soar/releases/download/0.11.0/soar.linux-amd64 -O vendor/bin/soar
# windows
* wget https://github.com/XiaoMi/soar/releases/download/0.11.0/soar.windows-amd64 -O vendor/bin/soar
# authorization
* chmod +x vendor/bin/soar
~~~

## env 增加配置

~~~env
SOAR_ENABLED=true
SOAR_TEST_DSN_DISABLE=false
SOAR_PATH=your_soar_path
SOAR_TEST_DSN_HOST=127.0.0.1
SOAR_TEST_DSN_PORT=3306
SOAR_TEST_DSN_DBNAME=yourdb
SOAR_TEST_DSN_USER=root
SOAR_TEST_DSN_PASSWORD=
SOAR_REPORT_TYPE=json
~~~

## 执行方式
> 在 `hyperf start` 后,监听 `QueryExec` 事件, 在全局的响应中插入了监听到的 `sql` 列表对应的优化建议
> 目前只对response()->json()进行了插入

## todo
- 在-report-type=json时, 加入评分

## 感谢

* [soar](https://github.com/XiaoMi/soar)
* [soar-php](https://github.com/guanguans/soar-php)