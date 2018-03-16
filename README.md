# UUID
generate UUID with concurrency.

ID 生成策略 (请在64位系统中使用，否则需要修改generate函数，防止int溢出)

毫秒级时间41位+进程id 16位+机器码 6位。

0           41    57     63 

+-----------+------+------+

|time       |pid   |inc   |

+-----------+------+------+

前41bits是以微秒为单位的timestamp。

接着16位进程id，linux进程id默认最大。

最后6bits是机器码。

机器码(6bits)标明最多只能有63台机器同时产生ID.

