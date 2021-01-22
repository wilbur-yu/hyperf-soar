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

## 样例
```json
{
    "code": 200,
    "message": "success",
    "data": {
        "id": 0,
        "title": "谢谢参与",
        "type": "none",
        "value": "0"
    },
    "soar": [
        {
            "query": "select snapshot from u_awards where user_id = '41' and json_unquote(json_extract(snapshot, '$.\"type\"')) = 'cash'",
            "explain": [
                {
                    "Item": "FUN.001",
                    "Severity": "L2",
                    "Summary": "避免在 WHERE 条件中使用函数或其他运算符",
                    "Content": "虽然在 SQL 中使用函数可以简化很多复杂的查询，但使用了函数的查询无法利用表中已经建立的索引，该查询将会是全表扫描，性能较差。通常建议将列名写在比较运算符左侧，将查询过滤条件放在比较运算符右侧。也不建议在查询比较条件两侧书写多余的括号，这会对阅读产生比较大的困扰。",
                    "Case": "select id from t where substring(name,1,3)='abc'",
                    "Position": 0,
                    "Score": 90
                }
            ]
        },
        {
            "query": "select id, v, amount, balance, type, value, image, title from awards where balance > '0' and is_enabled = '1'",
            "explain": [
                {
                    "Item": "OK",
                    "Severity": "L0",
                    "Summary": "OK",
                    "Content": "OK",
                    "Case": "OK",
                    "Position": 0,
                    "Score": 100
                }
            ]
        },
        {
            "query": "select id, user_id, value from user_points where user_points.user_id in (41)",
            "explain": [
                {
                    "Item": "OK",
                    "Severity": "L0",
                    "Summary": "OK",
                    "Content": "OK",
                    "Case": "OK",
                    "Position": 0,
                    "Score": 100
                }
            ]
        },
        {
            "query": "update u_points set value = value - 20, u_points.updated_at = '2021-01-22 16:05:06' where id = '26'",
            "explain": [
                {
                    "Item": "OK",
                    "Severity": "L0",
                    "Summary": "OK",
                    "Content": "OK",
                    "Case": "OK",
                    "Position": 0,
                    "Score": 100
                }
            ]
        },
        {
            "query":"insert into u_awards (award_id, snapshot, client_ip, used_point, expired_at, extra, user_id, updated_at, created_at) values ('0', '{\"id\":0,\"v\":100,\"type\":\"none\",\"image\":\"\",\"value\":\"0\",\"title\":\"\谢\谢\参\与\"}', '127.0.0.1', '20', '2021-04-22 16:05:06', '[]', '41', '2021-01-22 16:05:06', '2021-01-22 16:05:06')",
            "explain": [
                {
                    "Item": "LIT.001",
                    "Severity": "L2",
                    "Summary": "用字符类型存储IP地址",
                    "Content": "字符串字面上看起来像IP地址，但不是 INET_ATON() 的参数，表示数据被存储为字符而不是整数。将IP地址存储为整数更为有效。",
                    "Case": "insert into tbl (IP,name) values('10.20.306.122','test')",
                    "Position": 207,
                    "Score": 90
                }
            ]
        }
    ]
}
```

## 感谢

* [soar](https://github.com/XiaoMi/soar)
* [soar-php](https://github.com/guanguans/soar-php)