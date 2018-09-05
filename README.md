## 基于tp5的redis类库

composer require artecfeng/tpredis

```
$redis = new \artecfeng\tpredis\TpRedis();
$keys = $redis->keys('*');
```
