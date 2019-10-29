# hyperf-soar

## 安装
~~~bash
composer require wilbur/hyperf-soar --dev
~~~

## 发布配置文件
~~~
php bin/hyperf vendor:publish wilbur/hyperf-soar
~~~

### 下载 soar

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

### env 增加配置
~~~env
SOAR_ENABLED=true
SOAR_PATH=your_soar_path
SOAR_TEST_DSN_HOST=127.0.0.1
SOAR_TEST_DSN_PORT=3306
SOAR_TEST_DSN_DBNAME=yourdb
SOAR_TEST_DSN_USER=root
SOAR_TEST_DSN_PASSWORD=
SOAR_REPORT_TYPE=markdown
~~~

## Thanks

* [soar](https://github.com/XiaoMi/soar)
* [soar-php](https://github.com/guanguans/soar-php)