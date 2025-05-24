<template>
  <div
    class="
      login
      relative
      sm:flex
      min-h-screen
      items-center
      justify-center
      px-4
      sm:justify-end sm:pr-8
    "
  >
    <div
      class="
        logo
        sm:absolute
        top-[50px]
        left-[60px]
        pt-[100px]
        pb-[30px]
        sm:pt-0 sm:pb-0
        flex
        items-center
        justify-center
      "
    >
      <img
        src="@/assets/images/logo.png"
        alt="stars繁星CMS"
        width="44"
        height="44"
      />
      <h3 class="text-[22px] ml-[8px]">stars繁星CMS</h3>
    </div>

    <div class="w-full max-w-md bg-white mx-auto sm:mx-0 sm:-translate-x-1/4">
      <el-card
        shadow="never"
        class="py-[30px] bg-white shadow-md sm:rounded-lg"
      >
        <el-form
          ref="loginFormRef"
          :model="loginForm"
          :rules="loginData.loginFormRules"
          label-position="left"
          label-width="0px"
          class="login-form"
        >
          <div class="title">
            <h3>管理系统后台登录</h3>
          </div>

          <el-form-item prop="username">
            <el-input
              v-model.trim="loginForm.username"
              type="text"
              size="large"
              :prefix-icon="IconUser"
              auto-complete="off"
              placeholder="账号"
              clearable
              @keyup.enter="handleLogin(loginFormRef)"
            ></el-input>
          </el-form-item>
          <el-form-item prop="password">
            <el-tooltip
              :visible="capsTooltip"
              trigger="click"
              content="大写键已开启"
              placement="right"
              effect="light"
              manual
            >
              <el-input
                v-model.trim="loginForm.password"
                type="password"
                size="large"
                :prefix-icon="IconUnlock"
                show-password
                auto-complete="off"
                placeholder="密码"
                clearable
                @keyup="checkCapslock"
                @blur="capsTooltip = false"
                @keyup.enter="handleLogin(loginFormRef)"
              >
              </el-input>
            </el-tooltip>
          </el-form-item>
          <el-form-item prop="captcha" class="captcha">
            <el-input
              v-model.trim="loginForm.captcha"
              type="captcha"
              size="large"
              auto-complete="off"
              placeholder="验证码"
              clearable
              @keyup.enter="handleLogin(loginFormRef)"
            ></el-input>
            <div v-loading="loginData.captchaLoading" class="captcha-img">
              <img
                :src="loginData.captchaUrl"
                title="点击刷新验证码"
                @click="handleRefreshCaptcha"
              />
            </div>
          </el-form-item>
          <el-form-item>
            <el-button
              :loading="loginData.loading"
              type="primary"
              size="large"
              class="w-full mt-[15px]"
              @click.prevent="handleLogin(loginFormRef)"
            >
              立即登录
            </el-button>
          </el-form-item>
        </el-form>
      </el-card>

      <NTCopyright class="mt-[20px]"></NTCopyright>
    </div>
  </div>
</template>

<script>
import {
  defineComponent,
  onMounted,
  reactive,
  ref,
  defineAsyncComponent,
  nextTick,
} from 'vue'
import useUserStore from '@/store/modules/user'
import { User as IconUser, Unlock as IconUnlock } from '@element-plus/icons-vue'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import md5 from 'js-md5'
import { APP_TITLE } from '@/settings/config/app'

export default defineComponent({
  components: {
    NTCopyright: defineAsyncComponent(() =>
      import('@/layouts/NTCopyright/index.vue')
    ),
  },
  setup() {
    const { $api, $apiCode, $message, router } = useCurrentInstance()
    const userStore = useUserStore()
    const loginFormRef = ref()
    const loginForm = reactive({
      username: '',
      password: '',
      captcha: '',
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
        captcha: [{ required: true, message: '请输入验证码', trigger: 'blur' }],
      },
    })

    // 获取验证码
    const getCaptcha = async () => {
      loginData.captchaLoading = true
      const apiRes = await $api.login.getLoginCaptchaApi()
      const { status, data } = apiRes.data
      if (status === $apiCode.SUCCESS) {
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
    const checkCapslock = ({ key }) => {
      if (!key) return
      capsTooltip.value =
        key !== null && key.length === 1 && key >= 'A' && key <= 'Z'
    }

    // 提交登录
    const handleLogin = async (formEl) => {
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
        return
      }

      const params = {
        username: loginForm.username,
        password: md5(loginForm.password),
        captcha: loginForm.captcha,
        captcha_key: loginData.captchaKey,
      }
      loginData.loading = true
      await userStore
        .login(params)
        .then((apiRes) => {
          const { message } = apiRes.data
          router.replace('/')
          $message({
            type: 'success',
            message,
            duration: 3000,
          })
        })
        .catch((error) => {
          loginData.loading = false
          $message({
            type: 'warning',
            message: error,
            duration: 3000,
          })

          nextTick(() => {
            handleRefreshCaptcha()
          })
        })

      loginData.loading = false
    }
    return {
      loginFormRef,
      loginForm,
      loginData,
      APP_TITLE,
      IconUser,
      IconUnlock,
      handleRefreshCaptcha,
      handleLogin,
      capsTooltip,
      checkCapslock,
    }
  },
})
</script>
<style lang="scss" scoped>
.login {
  height: 100vh;
  box-sizing: border-box;
  background: url('@/assets/images/login-bg.jpg') no-repeat center;
  background-size: cover;
}
.login-form {
  width: 350px;
  margin: auto;

  .title {
    text-align: center;
    margin: 0 auto 40px auto;
    h3 {
      position: relative;
      display: inline-block;
      color: rgba(0, 0, 0, 0.85);
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
        cursor: pointer;
        box-sizing: border-box;
        margin-left: 10px;
      }
    }
  }
}

.copyright {
  background: transparent !important;
}
</style>
