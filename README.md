# UUID
generate UUID with concurrency.

## Description

### warning
Please use 64bits php, because in php32 it will overflow.

### How's the return value?

It is an array contains 1 or more 64 bits integer, which can be store in MySQL as bigint data type.

### How's it can be "UNIQUE" in the same request?

The value is generated by millisecond, machine id and sequence. 

When use it on more then one machine , you should set `MACHINE_ID`(0 ~ 63) as your local environment.

In the same millisecond, it will use sequence for promising the value is unique.

### How's the data store in the 64 bits number?

```text

+---------------------------------+--------------------+----------------------+
| millisecond timestamp (41 bits) | sequence (16 bits) | machine id (6 bits)  |
+---------------------------------+--------------------+----------------------+

```

### How to resolve race condition?

You can overwrite public method `generate()`, try to get sequence value from redis, memcache or any sequence generator which can be daemoned at memory.

