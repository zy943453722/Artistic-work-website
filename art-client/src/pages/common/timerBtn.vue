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
            
            this.countDown(60);
        }
    }
}
</script>

<style scoped>

</style>
