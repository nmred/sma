### 广告创意 INFO API 

返回所有的广告创意详细信息

##### 支持格式

JSON

##### HTTP 请求方式

GET

##### 请求参数

| 参数  | 参数类型 | 字段说明 | 
|-------|:-----|:-----|
|  id  | uint32 | 广告创意 Id |

##### 返回格式

```js
{
    "ret": 0,
    "data": {
        "id": 1,
        "name": "test1",
        "adx_id": 1,
        "client_id": 1,
        "standard_id": 1,
        "content": "",
        "created_at": 1462896000,
        "updated_at": 1462896000,
        "posid": [
            "1000000002",
            "1000000001"
        ]
    },
    "msg": ""
}
```
