<template>
  <div>
    <div class="loading-wrapper" v-show="showLoading">
      <Loading></Loading>
    </div>
    <div class="login-wrapper" v-show="!showLoading">
      <img :src="imgUrl" alt="" height="100%">
      <div class="login">
        <p class="title">Easy-Admin</p>
        <el-form
          :model="ruleForm"
          status-icon
          :rules="rules"
          ref="ruleForm"
          label-width="0"
          class="demo-ruleForm"
          @submit.native.prevent
        >
          <el-form-item prop="username">
            <el-input v-model="ruleForm.username" auto-complete="off" placeholder="请输入用户名"></el-input>
          </el-form-item>
          <el-form-item prop="pwd">
            <el-input type="password" v-model="ruleForm.pwd" auto-complete="off" placeholder="输入密码"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" native-type="submit"  @click="login('ruleForm')" style="width:100%;">登录</el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script>
import { mapMutations } from 'vuex'
import api from '@/api.js'
import Loading from '@/components/loading/Loading.vue'
export default {
  name: 'Login',
  components: {
    Loading
  },
  data() {
    let checkName = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入用户名'))
      } else {
        callback()
      }
    }
    let validatePass = (rule, value, callback) => {
      if (value === "") {
        callback(new Error("请输入密码"))
      } else {
        callback()
      }
    }
    return {
      showLoading: true,
      imgUrl: require('@/assets/images/bg-img-1.jpg'),
      ruleForm: {
        username: '',
        pwd: ''
      },
      rules: {
        username: [{ validator: checkName, trigger: 'change' }],
        pwd: [{ validator: validatePass, trigger: 'change' }],
      }
    }
  },
  mounted () {
    let bgImg = new Image()
    bgImg.src = this.imgUrl
    bgImg.onerror = () => {
      console.log('img onerror')
    }
    bgImg.onload = () => { // 图片加载成功后 去除loading
      this.showLoading = false
    }
  },
  methods: {
    ...mapMutations({
      bindLogin: 'BIND_LOGIN',
    }),
    login(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
            api.userLogin(this.ruleForm).then(resposon=>{
                let data=resposon.data
                if(data.status==0)
                {
                    this.$message({
                      message: data.msg,
                      duration: 3000,
                      type: 'error'
                    })
                    return
                }
                this.bindLogin(data.data)
                this.$message({
                  message: '恭喜，登录成功。',
                  duration: 1000,
                  type: 'success',
                  onClose:()=>{
                    this.$router.push({
                        path: '/'
                    })
                  }
                })
            }).catch(resposon=>{
                this.$message({
                  message: resposon.data.msg,
                  duration: 3000,
                  type: 'error'
                })
            })
        }
      })
    }
  }
}
</script>
<style scoped>
.loading-wrapper {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  background: #aedff8;
  display: flex;
  align-items: center;
  justify-content: center;
}
.login-wrapper {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
}
.login-wrapper img {
  position: absolute;
  z-index: 1;
  text-align: center;
}
.login {
  max-width: 340px;
  margin: 60px auto;
  background: #fff;
  padding: 20px 40px;
  border-radius: 10px;
  position: relative;
  z-index: 9;
}
.title {
  font-size: 26px;
  line-height: 50px;
  font-weight: bold;
  margin: 10px;
  text-align: center;
}
#login-form>input {
  width: 100%;
  height: 34px;
  display: block;
  margin-top: 26px;
  background: #fff;
  color: #333;
  border: 1px solid #7da9c7;
  outline: none;
  text-indent: 20px;
  font-size: 14px;
}
#login-form>button {
  width: 100%;
  height: 34px;
  display: block;
  margin-top: 26px;
  background: #1ab2ff;
  color: #fff;
  border: 1px solid #1ab2ff;
  outline: none;
  border-radius: 100px;
  text-align: center;
  font-size: 16px;
  cursor: pointer;
}
.register {
  margin-top: 10px;
  font-size: 14px;
  line-height: 22px;
  text-indent: 8px;
  color: #1ab2ff;
  cursor: pointer;
  display: inline-block;
}
.register:hover {
  color: #2c2fd6;
}
@media (max-width: 768px) {
  .login {
    max-width: 260px;
    margin: 60px auto;
    background: #fff;
    padding: 20px 20px;
    border-radius: 10px;
    position: relative;
    z-index: 9;
  }
}
</style>
