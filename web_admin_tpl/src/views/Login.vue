<template>
  <div class="login">
    <el-form
      ref="loginFormRef"
      :model="loginForm"
      :rules="loginData.loginFormRules"
      label-position="left"
      label-width="0px"
      class="login-container">
      <div class="title">
        <h3>
          <img alt="logo" class="logo" src="~@/assets/images/logo.png">
          {{ APP_NAME }}
        </h3>
      </div>

      <el-form-item prop="username">
        <el-input type="text" size="large" :prefix-icon="IconUser" v-model.trim="loginForm.username" auto-complete="off" placeholder="账号" @keyup.enter="handleLogin(loginFormRef)"></el-input>
      </el-form-item>
      <el-form-item prop="password">
        <el-tooltip
        :visible="capsTooltip"
        trigger="click"
        content="大写键已开启"
        placement="right"
        effect="light"
        manual>
          <el-input
            type="password"
            size="large"
            :prefix-icon="IconUnlock"
            show-password v-model.trim="loginForm.password"
            auto-complete="off"
            placeholder="密码"
            @keyup="checkCapslock"
            @blur="capsTooltip = false"
            @keyup.enter="handleLogin(loginFormRef)">
          </el-input>
        </el-tooltip>
      </el-form-item>
      <el-form-item prop="captcha" class="captcha">
        <el-input type="captcha" size="large" v-model.trim="loginForm.captcha" auto-complete="off" placeholder="验证码" @keyup.enter="handleLogin(loginFormRef)"></el-input>
        <div class="captcha-img" v-loading="loginData.captchaLoading">
          <img :src="loginData.captchaUrl" @click="handleRefreshCaptcha" title="点击刷新验证码">
        </div>
      </el-form-item>
      <el-form-item>
        <el-button class="login-btn" type="primary" size="large" @click.prevent="handleLogin(loginFormRef)" :loading="loginData.loading">
          登 录
        </el-button>
    </el-form-item>
    </el-form>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, reactive, ref, getCurrentInstance } from 'vue'
import { useStore } from 'vuex'
import { User as IconUser, Unlock as IconUnlock } from '@element-plus/icons-vue'
import type { ElForm } from 'element-plus'
import md5 from 'js-md5'
import { HTTP_CONFIG } from '@/config/http'
import { APP_NAME } from '@/config/app'
import {
  LoginProps,
  getCaptchaApi
} from '@/api/adminUser'

type FormInstance = InstanceType<typeof ElForm>
export default defineComponent({
  setup () {
    const { proxy } = (getCurrentInstance() as any)
    const store = useStore()
    const loginFormRef = ref<FormInstance>()
    const loginForm = reactive<LoginProps>({
      username: '',
      password: '',
      captcha: ''
    })
    const loginData = reactive({
      // 重定向页面
      redirectUrl: '',
      loading: false,
      captchaUrl: '',
      captchaKey: '',
      captchaLoading: false,
      loginFormRules: {
        username: [{ required: true, message: '请输入账号', trigger: 'blur' }],
        password: [{ required: true, message: '请输入密码', trigger: 'blur' }],
        captcha: [{ required: true, message: '请输入验证码', trigger: 'blur' }]
      },
      messageBoxInstance: null
    })

    // 获取验证码
    const getCaptcha = async () => {
      loginData.captchaLoading = true
      const { status, data } = await getCaptchaApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        loginData.captchaUrl = data.base64
        loginData.captchaKey = data.key
      }
      loginData.captchaLoading = false
    }

    // 刷新验证码
    const handleRefreshCaptcha = () => {
      if (loginData.captchaLoading) {
        return
      }
      getCaptcha()
    }

    onMounted(() => {
      getCaptcha()
    })

    const capsTooltip = ref(false)
    const checkCapslock = ({ key }: KeyboardEvent) => {
      capsTooltip.value = key !== null && key.length === 1 && (key >= 'A' && key <= 'Z')
    }

    // 提交登录
    const loginAct = async (data: LoginProps) => store.dispatch('user/loginAct', data)
    const handleLogin = async (formEl: FormInstance | undefined) => {
      const validateStatus = await new Promise((resolve) => {
        if (!formEl) return
        formEl.validate((valid) => {
          if (valid) {
            resolve(true)
          } else {
            resolve(false)
          }
        })
      })
      if (!validateStatus || loginData.loading) {
        return false
      }

      const params = {
        username: loginForm.username,
        password: md5(loginForm.password),
        captcha: loginForm.captcha,
        captcha_key: loginData.captchaKey
      }
      loginData.loading = true
      const { status, message } = await loginAct(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        proxy.message({
          type: 'success',
          message,
          duration: 3000
        })
      } else {
        proxy.message({
          type: 'warning',
          message,
          duration: 3000
        })
      }
      loginData.loading = false
    }
    return {
      loginFormRef,
      loginForm,
      loginData,
      APP_NAME,
      IconUser,
      IconUnlock,
      handleRefreshCaptcha,
      handleLogin,
      capsTooltip,
      checkCapslock
    }
  }
})
</script>
<style lang="scss" scoped>
  .login{
    height: 100vh;
    padding-top: 210px;
    display: flex;
    justify-content: center;
    box-sizing: border-box;
    background: url('~@/assets/images/login-bg.svg') no-repeat center;
    background-size: cover
  }
  .login-container {
    width: 330px;
    background-color: transparent;

    .title {
      text-align: center;
      margin: 0 auto 40px auto;
      h3 {
        position: relative;
        display: inline-block;
        color: rgba(0,0,0,.85);
        font-weight: 600;
        font-size: 26px;
        margin: 0;
        .logo {
          height: 44px;
          margin-right: 6px;
          vertical-align: middle;
        }
      }
    }

    .login-btn {
      width: 100%;
    }

    .captcha {
      :deep(.el-form-item__content) {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
      }
      .captcha-img {
        display: flex;
        align-items: center;
        img {
          min-width: 80px;
          max-height: 38px;
          cursor: pointer;
          box-sizing: border-box;
          margin-left: 10px;
        }
      }
    }
  }
</style>
