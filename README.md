OAuth 2.0 登录助手
==================

此库是为了解决OAuth2.0登录时多个域名使用同一个OAuth2.0服务提供账号登录的需求

比如wechat.readtv.cn和jl.promo.readtv.cn都需要使用同一个微信公众账号登录，公众账号只能设置一个redirect_uri的域名。

原理
----

在用户登录时，分为获取code和获取access token两个步骤。

只有在获取code步骤，公众号设置的redirect_uri才用作用（限制使用公众号的域名的作用）

使用方式
--------

将一台服务器设置为专门的获取code的服务器，比如：oauth-code.readtv.cn

将get-code.php代码部署到服务器，部署前可设置能使用此服务的域名：

```php
$sites = [
    'wechat.readtv.cn',
    'jl.promo.readtv.cn',
];
```

WIP