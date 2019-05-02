<template>
<div>
    <el-button v-if="showtime===null" class="captcha-button" @click="send">获取</el-button>
    <el-button v-else class="captcha-button">
      {{showtime}}
    </el-button>    
</div>
</template>

<script>
import { setTimeout, clearTimeout } from 'timers';
import axios from 'axios';

export default {
    name: 'TimerBtn',
    props: {
        phone: {
            type: String,
            required: true
        }
    },
    data () {
        return {
            timeCounter: null,
            showtime: null
        }
    },
    methods: {
        countDownText (s) {
            this.showtime = `${s}s后重新获取`;
        },
        countDown (times) {
            const self = this;
            const interval = 1000;
            let count = 0;
            self.timeCounter = setTimeout(countDownStart, interval);
            function countDownStart() {
                if (self.timeCounter === null) {
                    return false;
                }
                count++;
                self.countDownText(times - count + 1);
                if (count > times) {
                    clearTimeout(self.timeCounter);
                    self.showtime = null;
                } else {
                    self.timeCounter = setTimeout(countDownStart, interval);
                }
            }
        },
        send () {
            if (this.phone === '') {
                this.$message({
                    message:'请填写完手机号再进行验证码操作',
                    type: 'warning'
                });
                return false;
            } else {
                axios.get('/api/users/getUserDetail',{
                    params: {
                        phoneNumber: this.phone
                    }
                }).then(response => {
                   if (response.status === 200) {
                       if (response.data.errno === 20001) {
                           this.$message({
                               message: '参数有误',
                               type: 'warning'
                           });
                           return false;
                       } else if(response.data.data.count > 0) {
                           this.$message({
                               message: '该手机号已注册过，请更换手机号',
                               type: 'warning'
                           });
                           return false;
                       } else {
                           axios.get('api/users/getSms',{
                               params: {
                                   phoneNumber: this.phone
                               }
                           }).then(response => {
                               if (response.status === 200) {
                                   if (response.data.errno === 10000) {
                                       this.countDown(60);
                                    } else {
                                        this.$message({
                                        message: '参数有误',
                                        type: 'warning'
                                    });
                                    return false;
                                   }  
                               } else {
                                   this.$message.error('服务器请求错误');
                                   return false;
                               }
                           })
                       }
                   } else {
                       this.$message.error('服务器请求错误');
                       return false;
                   }
                });
            }
        }
    }
}
</script>

<style scoped>

</style>
