# 后端API文档



## 游客获取用户记录列表
```
GET /users/touristListUserRecord   无token验证
```

#### 参数列表：

  | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
  | ----- | -------- | -------- | -------- |----|
  |pageSize|int|0-9|是|无|
  |pageNumber|int|正整数|是|无|

#### http响应体
```
{
    "requestId": "5cb003dc516e9",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": [
        {
            "pin": "12345678910",
            "works_number": 3,
            "followers_number": 15,
            "likes_number": 51,
            "masterpiece": "2",
            "masterpiece_id": 2,
            "website": "http://artgallery.com/uid/2",
            "nickname": "zy"
        },
        {
            "pin": "15202992577",
            "works_number": 2,
            "followers_number": 10,
            "likes_number": 8,
            "masterpiece": "http://aeejfge/wfe.jpg",
            "masterpiece_id": 7,
            "website": "http://artgallery.com/uid/1",
            "nickname": "zhangyue"
        },
        {
            "pin": "12345678911",
            "works_number": 2,
            "followers_number": 6,
            "likes_number": 21,
            "masterpiece": "3",
            "masterpiece_id": 3,
            "website": "http://artgallery.com/uid/3",
            "nickname": "zhang"
        }
    ]
}
```

## 用户获取用户记录列表
```
GET /users/pinListUserRecord   有token验证
```

#### 参数列表：

  | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
  | ----- | -------- | -------- | -------- |----|
  |pageSize|int|0-9|是|无|
  |pageNumber|int|正整数|是|无|

#### http响应体
```
{
    "requestId": "5cb0047c38575",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": [
        {
            "pin": "12345678910",
            "works_number": 3,
            "followers_number": 15,
            "likes_number": 51,
            "masterpiece": "2",
            "masterpiece_id": 2,
            "nickname": "zy",
            "avator": "2",
            "website": "http://artgallery.com/uid/2",
            "name": "冬",
            "make_at": 2017,
            "type": 5,
            "likes": 30
        },
        {
            "pin": "12345678911",
            "works_number": 2,
            "followers_number": 6,
            "likes_number": 21,
            "masterpiece": "3",
            "masterpiece_id": 3,
            "nickname": "zhang",
            "avator": "3",
            "website": "http://artgallery.com/uid/3",
            "name": "夏",
            "make_at": 2019,
            "type": 5,
            "likes": 50,
            "relation": "互相关注"
        }
    ]
}
```

## 游客获取作品列表
```
GET /works/touristList 无token检测
```

#### 参数列表：

  | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
  | ----- | -------- | -------- | -------- |----|
  |  type | int | 0-17 | 否 |无|
  | lengthMin| int | 0-200| 否|无 |
  |lengthMax| int|0-200|否|不可单独存在，有这个必然有min|
  | makeAtStart| int | 1980-2019|否|单独存在时相当于精确年份|
  |makeAtEnd|int|1980-2019|否|单独存在时相当于大于这个年份|
  | nickname | string | 0~16| 否| 模糊查询|
  |name|string|0~16| 否|模糊查询| 
  |pageSize|int|0-9|是|无|
  |pageNumber|int|正整数|是|无|

#### http响应体
```
{
    "requestId": "5cb03b9746ba1",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": [
        {
            "id": 1,
            "instance": "1",
            "name": "春秋",
            "pin": "15202992577",
            "likes": 22,
            "website": "http://artgallery.com/uid/1",
            "nickname": "zhangyue"
        }
    ]
}
```

## 用户查看作品列表
```
GET /works/pinList 有token检测
```

#### 参数列表：
 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
  | ----- | -------- | -------- | -------- |----|
  |  type | int | 0-17 | 否 |无|
  | lengthMin| int | 0-200| 否|无 |
  |lengthMax| int|0-200|否|不可单独存在，有这个必然有min|
  | makeAtStart| int | 1980-2019|否|单独存在时相当于精确年份|
  |makeAtEnd|int|1980-2019|否|单独存在时相当于大于这个年份|
  | nickname | string | 0~16| 否| 模糊查询|
  |name|string|0~16| 否|模糊查询| 
  |pageSize|int|0-9|是|无|
  |pageNumber|int|正整数|是|无|

#### http响应体
```
{
    "requestId": "5cb03cdb552a5",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": [
        {
            "id": 2,
            "instance": "2",
            "name": "冬",
            "pin": "12345678910",
            "likes": 30,
            "website": "http://artgallery.com/uid/2",
            "nickname": "zy",
            "relation": "已点赞"
        }
    ]
}
```

## 注册用户
```
POST /users/add 无token检测
```

#### 参数列表
application/x-www-form-urlencoded格式

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 | phoneNumber|string|11位长度|是|无|
 | password|string|8-16位字母数字组合|是|原密码|
 |nickname|string|1-16位中文/字母/数字|是|无|
 |verifyCode|string|4位数字|是|验证码|

#### http响应体
```
{
    "requestId": "5cb0439c5ae63",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": {
        "pin": "13964382701"
    }
}
```

## 获取用户详情（注册信息）
```
GET /users/getUserDetail 无token检测
```

#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |phoneNumber|string|11位长度|否|无|

#### http响应体
```
{
    "requestId": "5cb045b42ce0c",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": {
        "0": {
            "id": 4,
            "pin": "13964382701",
            "phone_number": "13964382701",
            "password": "db08879bdb009313a340721967f94efa"
        },
        "count": 1
    }
}
``` 

 
## 用户登出
```
PUT /users/logout 有token检测 
```

有额外的HTTP头：<br>
x-artgallery-refreshToken

#### http响应头
```
{
    "requestId": "5cb048ca6a903",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": []
}
```

## 找回用户密码
```
PUT /users/findUserPassword 无token检测
```

#### 参数列表
application/x-www-form-urlencoded格式

| 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
| ----- | -------- | -------- | -------- |----|
|password|string|8-16位字母数字组合|是|无|
|phoneNumber|string|11位长度|是|无|

#### http响应头
```
{
    "requestId": "5cb0497d000c4",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": []
}
```

## 修改用户密码
```
PUT /users/modifyUserPassword 有token检测
```

#### 参数列表

application/json格式

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |oldPassword|string|8-16位字母数字组合|是|无|
 |newPassword|string|8-16位字母数字组合|是|无|

#### http响应体
```
{
    "requestId": "5cb04b0d0132f",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": []
}
```

## 获取用户基本资料详情
```
GET /users/getUserInfo 有token检测
```
无请求参数

#### http响应体
```
{
    "requestId": "5cb04b820499a",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": [
        {
            "id": 1,
            "pin": "15202992577",
            "nickname": "zhangyue",
            "sex": 1,
            "city": "山东省",
            "birthday": 849628800,
            "introduction": "帅气",
            "avator": "1",
            "website": "http://artgallery.com/uid/1"
        }
    ]
}
```

## 获取用户记录详情（把用户个人主页中的显示归到此接口中）
```
GET /users/getUserRecord 无token检测
```

#### 参数列表

| 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
| ----- | -------- | -------- | -------- |----|
|pin|string|0-255|是|目标用户的pin|

#### http响应体
```
{
    "requestId": "5cb04c0ad7f0e",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": [
        {
            "nickname": "zhangyue",
            "avator": "1",
            "introduction": "帅气",
            "city": "山东省",
            "pin": "15202992577",
            "works_number": 2,
            "followers_number": 10,
            "likes_number": 8
        }
    ]
}
```
 
## 游客查看用户作品列表
```
GET /users/touristListWorks 无token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
  |pin|string|0-255|是|目标用户的pin|
  |worksId|int|正整数|否|判断是其他作品还是全部作品|
  |pageSize|int|0-9|是|无|
  |pageNumber|int|正整数|是|无|

#### http响应体
```
{
    "requestId": "5cb04d60e4a7c",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": [
        {
            "id": 4,
            "instance": "http://wffegege/wfe.jpg",
            "name": "人像",
            "make_at": 2005,
            "pin": "15202992577"
        },
        {
            "id": 5,
            "instance": "http://sffgwgew/wfw/jpg",
            "name": "人像",
            "make_at": 2017,
            "pin": "15202992577"
        },
        {
            "id": 6,
            "instance": "http://sffgwgew/wfw/jpg",
            "name": "人像",
            "make_at": 2017,
            "pin": "15202992577"
        },
        {
            "id": 7,
            "instance": "http://aeejfge/wfe.jpg",
            "name": "人像",
            "make_at": 2005,
            "pin": "15202992577"
        }
    ]
}
```

## 用户查看用户作品列表
```
GET /users/pinListWorks 有token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
  |pin|string|0-255|是|目标用户的pin|  
 |worksId|int|正整数|否|判断是其他作品还是全部作品|
 |pageSize|int|0-9|是|无|
  |pageNumber|int|正整数|是|无|

#### http响应体
```
{
    "requestId": "5cb04dbbd6084",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": [
        {
            "id": 1,
            "instance": "1",
            "name": "春秋",
            "make_at": 2018,
            "pin": "15202992577"
        },
        {
            "id": 4,
            "instance": "http://wffegege/wfe.jpg",
            "name": "人像",
            "make_at": 2005,
            "pin": "15202992577"
        },
        {
            "id": 5,
            "instance": "http://sffgwgew/wfw/jpg",
            "name": "人像",
            "make_at": 2017,
            "pin": "15202992577"
        },
        {
            "id": 6,
            "instance": "http://sffgwgew/wfw/jpg",
            "name": "人像",
            "make_at": 2017,
            "pin": "15202992577"
        },
        {
            "id": 7,
            "instance": "http://aeejfge/wfe.jpg",
            "name": "人像",
            "make_at": 2005,
            "pin": "15202992577"
        }
    ]
}
```

## 用户查看作品详情
```
GET /works/pinGetWorksDetail 有token检测
```

#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |worksId|int|正整数|是|作品id|

#### http响应体
```
{
    "requestId": "5cb04e5e96823",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": {
        "id": 1,
        "instance": "1",
        "name": "春秋",
        "type": 5,
        "length": 100,
        "height": 35,
        "introduction": "",
        "pin": "15202992577",
        "website": "http://artgallery.com/uid/1",
        "nickname": "zhangyue",
        "avator": "1",
        "right": "可删改"
    }
}
``` 

 ## 游客 查看作品详情
```
GET /works/touristGetWorksDetail 无token检测
```

#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |worksId|int|正整数|是|作品id|

#### http响应体
```
{
    "requestId": "5cb04e95eacbd",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": [
        {
            "id": 2,
            "instance": "2",
            "name": "冬",
            "type": 5,
            "length": 250,
            "height": 40,
            "introduction": "",
            "pin": "12345678910",
            "website": "http://artgallery.com/uid/2",
            "nickname": "zy",
            "avator": "2"
        }
    ]
}
``` 
 
## 查看作品点赞详情
```
GET /works/getLikesDetail 无token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |worksId|int|正整数|是|作品id|

#### http响应体
```
{
    "requestId": "5cb04eb30f0bc",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": [
        {
            "pin": "12345678910",
            "avator": "2",
            "website": "http://artgallery.com/uid/2",
            "nickname": "zy"
        },
        {
            "pin": "12345678911",
            "avator": "3",
            "website": "http://artgallery.com/uid/3",
            "nickname": "zhang"
        }
    ]
}
```
 
## 用户查看作品评论详情
```
GET /works/pinGetCommentsDetail 有token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |worksId|int|正整数|是|作品id|

#### http响应体
```
{
    "requestId": "5cb04f535f1b5",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": [
        {
            "pin": "15202992577",
            "avator": "1",
            "nickname": "zhangyue",
            "website": "http://artgallery.com/uid/1",
            "id": 1,
            "works_id": 2,
            "content": "你的作品真好!",
            "to_pin": "",
            "create_at": 1554864406,
            "right": "可删除"
        },
        {
            "pin": "12345678910",
            "avator": "2",
            "nickname": "zy",
            "website": "http://artgallery.com/uid/2",
            "id": 2,
            "works_id": 2,
            "content": "我也这么觉得",
            "to_pin": "15202992577",
            "create_at": 1554864570
        },
        {
            "pin": "12345678911",
            "avator": "3",
            "nickname": "zhang",
            "website": "http://artgallery.com/uid/3",
            "id": 3,
            "works_id": 2,
            "content": "我同意",
            "to_pin": "12345678910",
            "create_at": 1554864780
        },
        {
            "pin": "12345678910",
            "avator": "2",
            "nickname": "zy",
            "website": "http://artgallery.com/uid/2",
            "id": 4,
            "works_id": 2,
            "content": "谢谢大家",
            "to_pin": "",
            "create_at": 1554864894
        }
    ]
}
```
 
 ## 游客查看作品评论详情
```
GET /works/touristGetCommentsDetail 无token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |worksId|int|正整数|是|作品id|

#### http响应体
```
{
    "requestId": "5cb04f79bb7a5",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": [
        {
            "pin": "15202992577",
            "avator": "1",
            "nickname": "zhangyue",
            "website": "http://artgallery.com/uid/1",
            "id": 1,
            "works_id": 2,
            "content": "你的作品真好!",
            "to_pin": "",
            "create_at": 1554864406
        },
        {
            "pin": "12345678910",
            "avator": "2",
            "nickname": "zy",
            "website": "http://artgallery.com/uid/2",
            "id": 2,
            "works_id": 2,
            "content": "我也这么觉得",
            "to_pin": "15202992577",
            "create_at": 1554864570
        },
        {
            "pin": "12345678911",
            "avator": "3",
            "nickname": "zhang",
            "website": "http://artgallery.com/uid/3",
            "id": 3,
            "works_id": 2,
            "content": "我同意",
            "to_pin": "12345678910",
            "create_at": 1554864780
        },
        {
            "pin": "12345678910",
            "avator": "2",
            "nickname": "zy",
            "website": "http://artgallery.com/uid/2",
            "id": 4,
            "works_id": 2,
            "content": "谢谢大家",
            "to_pin": "",
            "create_at": 1554864894
        }
    ]
}
```
 

## 新增评论
```
POST /works/addComments 有token检测
```
#### 参数列表

application/json格式

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |worksId|int|正整数|是|作品id|
 |toPin| string|0-255|否|只有在回复的时候才使用|
 |content|string|0-255|是|评论内容|

#### http响应体
```
{
    "requestId": "5cb0501e54610",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": []
}
```

## 删除评论
```
DELETE /works/deleteComments 有token检测(只有本人和作品作者可删除)
```
#### 参数列表

application/json格式

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 | id|int|正整数|是|评论id|

#### http响应体
```
{
    "requestId": "5cb0533ea4036",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": []
}
```

## 新增作品点赞
```
POST /works/addLikes 有token检测
```
#### 参数列表

application/json格式

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |worksId|int|正整数|是|无|
 |pin|string|0-255|是|供填写用户记录表用|

#### http响应体
```
{
    "requestId": "5cb053ede0848",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": []
}
```
 
## 删除作品点赞
```
DELETE /works/deleteLikes 有token检测
```
#### 参数列表

application/json格式

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |worksId|int|正整数|是|无|
 |pin|string|0-255|是|供填写用户记录表用|

#### http响应体
```
{
    "requestId": "5cb05442d6e17",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": []
}
```

## 获取用户被点赞详情
```
GET /users/getLikemeDetail 无token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
  |pin|string|0-255|是|目标用户的pin|

#### http响应体
```
```

## 查看用户点赞详情
```
GET /users/getILikeDetail 无token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
  |pin|string|0-255|是|目标用户的pin|
  
## 查看用户被评论详情
```
GET /users/getComments 无token检测
```
#### 参数列表

| 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
  |pin|string|0-255|是|目标用户的pin|
**注**：查询所有to_pin是pin，或者from_pin不是pin且works_id对应的作者是pin的
  
## 修改用户个人资料
```
PUT /users/modifyUserInfo 有token检测
```

#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |avator|string|0-255|否|无|
 |nickname|string|0-255|否|无|
 |sex|int|0,1,2|否|无|
 |birthday|int|正整数|否|无|
 |city|string|0-255|否|无|
 |introduction|string|0-500|否|无|


## 获取用户关注/粉丝列表
```
GET /users/getFollowing 有token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |status|int|0,1|是|无|


## 新增用户关注
***一下增加两条记录,分别是关注和粉丝***
```
POST /users/addFollowing 有token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |friendPin|string|0-255|是|无|
 
## 修改用户关注
***这是那种本身互相关注，取消后还存在单方面关系的情况***
```
PUT /users/modifyFollowing 有token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |friendPin|string|0-255|是|无|
 
## 删除用户关注
***这是本身单方面存在联系，取消后没有关系的情况***
```
DELETE /users/deleteFollowing 有token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |friendPin|string|0-255|是|无|

## 新增作品
```
POST /works/add 有token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |length|int|正整数|是|无|
 |height|int|正整数|是|无|
 |type|int|0-17|是|无|
 |name|string|0-255|是|无|
 |introduction|string|0-255|是|无|
 |instance|string|0-255|是|无|
 |makeAt|int|0-2019|是|无|

## 删除作品
```
DELETE /works/delete 有token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
 |id|int|正整数|是|无|
 
## 修改作品
```
PUT /works/modify 有token检测
```
#### 参数列表

 | 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
  |id|int|正整数|是|作品id|
 |length|int|正整数|是|无|
 |height|int|正整数|是|无|
 |type|int|0-17|是|无|
 |name|string|0-255|是|无|
 |introduction|string|0-255|是|无|
 |instance|string|0-255|是|无|
 |makeAt|int|0-2019|是|无|

## 获取用户token
```
GET /users/token 无token检测 
```
无请求参数,有http头x-artgallery-pin

#### http响应体
```
{
    "requestId": "5cb03bf44e147",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": {
        "accessToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImp0aSI6IjVjYjAzYmY0MWM3ZDEifQ.eyJpc3MiOiJodHRwOlwvXC9hcnRnYWxsZXJ5LmNvbSIsImF1ZCI6Imh0dHA6XC9cL2FwaS5hcnR3b3Jrcy5jb20iLCJqdGkiOiI1Y2IwM2JmNDFjN2QxIiwiaWF0IjoxNTU1MDUzNTU2LCJuYmYiOjE1NTUwNTM1NTYsImV4cCI6MTU1NTA1NzE1Niwic3ViIjoidXNlclRva2VuIiwicGluIjoiMTUyMDI5OTI1NzcifQ.Yd0QYhG7ML20Fj_dohldbWOmY-OqsNxfHzbv8g4pBss",
        "refreshToken": "07d8ea6e09c76116575546a8af67de71"
    }
}
```

## 更新用户token
```
PUT /users/updateToken 无token检测
```
**需要有2个额外的HTTP头：**

x-artgallery-refreshToken

x-artgallery-pin

## 用户登录
```
POST /users/login 无token检测
```

| 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
|phoneNumber|string|11位长度|是|无|
|password|string|0-255|是|无| 

## 用户意见反馈
```
POST /users/feedback 无token检测
```

| 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
 | ----- | -------- | -------- | -------- |----|
|nickname|string|0-255|是|无|
|email|string|0-255|是|无| 


## 验证用户验证码（找回密码时用）
```
get /users/verifyUserCode 无token检测
```
| 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
| ----- | -------- | -------- | -------- |----|
|phoneNumber|string|11位长度|是|无|
|verifyCode|int|4位长度|是|无|

## 获取用户权限
```
GET /users/getRight 有token检测
```

| 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
| ----- | -------- | -------- | -------- |----|
|pin|string|0-255|是|被访问用户的pin|

## 获取短信 
```
GET /users/getSms 无token检测
```
| 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
| ----- | -------- | -------- | -------- |----|
|phoneNumber|string|11位长度|是|无|

#### http响应体
```
{
    "requestId": "5cb042d02093e",
    "errno": 10000,
    "errmsg": "操作成功",
    "data": []
}
```


## 用户上传文件 （用于从服务器获取签名，从而前端直接上传）
```
POST /users/upload 有token检测
```

## 用户下载文件
```
GET /users/download 有token检测
```

| 参数名| 参数类型 | 参数限制 | 是否必传 |备注|
| ----- | -------- | -------- | -------- |----|
|file|string|0-255|是|无|

