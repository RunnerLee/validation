# Validation For FastD 3.0

## 使用

创建请求验证器
```
use Runner\Validation\RequestValidatorInterface;

class OrderStoreRequestValidator implements RequestValidatorInterface
{
    public function rules()
    {
        return [
            'price' => 'required|numeric'
        ];
    }
}
```

配置绑定路由
```
#file: config/config.php

return [
    'validation' => [
        'orders.store' => OrderStoreRequestValidator::class
    ],
];

```