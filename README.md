OAuth 2.0 登录助手
==================

某些OAuth2.0用户提供商，可能要求只能设置一个redirect_uri host，但是你却想使用多个redirect_uri host，如果是这种情况，此库适合你。

原理
----

在用户登录时，分为获取code和获取access token两个步骤。

只有在获取code步骤，公众号设置的redirect_uri才用作用（限制使用公众号的域名的作用）

而获取access token 阶段完全没有此限制，直接使用code获取就行了

使用方式
--------

```
$ composer require chrisyue/dark-portal-bundle:dev-master
```

然后在`AppKernel`里开启此Bundle你懂的

设置security.yml文件：

```
providers:
    oauth:
        id: chrisyue_dark_portal.security.user.provider

firewalls:
    secured_area:
        pattern: ^/
        oauth_code:
            appid: %your_appid%
            secret: %your_secret%
            scope: %your_scope%
            code_endpoint: %your_code_endpoint% # 不着急，此字段意义见后面
        provider: oauth
```

比如wechat.xxx.com和weixin.xxx.com都需要使用同一个微信公众账号登录，公众账号只能设置一个redirect_uri的域名。

将一台服务器设置为专门的获取code的服务器，比如：oauth-code.xxx.com

将get-code.php代码部署到服务器，部署前可设置能使用此服务的域名：

```php
$hosts = [
    'wechat.xxx.com',
    'weixin.xxx.com',
];
```

假如部署后此脚本路径为`http://oauth-code.xxx.com/get-code.php`，那么`your_code_endpoint`参数的值就是它。

TODO
----

* 单元测试
* 不仅限于微信使用
