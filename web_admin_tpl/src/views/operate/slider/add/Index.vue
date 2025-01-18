<template>
  <div class="add-slider PAGE-MAIN-CONTENT">
    <PageHeader :title="hdTitle"></PageHeader>
    <el-card shadow="never" class="box-card">
      <el-row>
        <el-col :span="10" :xs="24" :sm="12" :md="10">
          <el-form
            ref="formRef"
            :rules="!isReadOnly ? formRules : null"
            :model="formMdl"
            v-loading="tbIsLoading"
            label-width="100px">
            <el-form-item prop="name" label="轮播图名称">
              <el-input v-model="formMdl.name" :readonly="isReadOnly" clearable placeholder="请输入轮播图名称"></el-input>
            </el-form-item>
            <el-form-item prop="page_url" label="跳转路径">
              <el-input v-model="formMdl.page_url" :readonly="isReadOnly" clearable placeholder="请输入轮播图跳转路径"></el-input>
            </el-form-item>
            <el-form-item prop="slider" label="图片选择">
              <el-upload
                :on-change="handleChangeSlider"
                :before-remove="handleBeforeRemoveSlider"
                :auto-upload="false"
                :file-list="fileList"
                :show-file-list="false"
                :limit="1">
                <template #tip>
                  <Tips title="允许上传不超过500kb的jpg/png图片"></Tips>
                </template>
                <div
                  v-if="formMdl.slider"
                  class="upload-pic-view">
                  <el-image
                    style="width: 110px; height: 110px"
                    :src="imgUrl"
                    fit="contain"
                    @click="handleChooseFile">
                  </el-image>
                  <el-icon class="icon-close" @click.stop="handleBeforeRemoveSlider"><Close /></el-icon>
                </div>
                <el-icon v-else class="icon-upload"><Plus /></el-icon>
              </el-upload>
            </el-form-item>
            <el-form-item prop="sort" label="排序">
              <el-input v-model="formMdl.sort" :readonly="isReadOnly" placeholder="请输入排序"></el-input>
              <Tips title="数值越小越靠前"></Tips>
            </el-form-item>
            <el-form-item prop="status" label="状态">
              <el-switch v-model="formMdl.status" :disabled="isReadOnly" active-text="正常" inactive-text="停用" :active-value="1" :inactive-value="0"></el-switch>
            </el-form-item>
            <el-form-item prop="remarks" label="备注">
              <el-input type="textarea" v-model="formMdl.remarks" :readonly="isReadOnly" placeholder="请输入备注"></el-input>
            </el-form-item>
            <el-form-item class="form-footer-opt-btns" v-if="['add', 'edit'].includes(mode)">
              <el-button @click="resetForm">重置</el-button>
              <el-button type="primary" :loading="loading" @click="handleSubmit">{{ submitBtnTit }}</el-button>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref, getCurrentInstance, onMounted, nextTick, computed } from 'vue'
import { useRoute } from 'vue-router'
import PageHeader from '@/components/business/PageHeader.vue'
import type { UploadFile } from 'element-plus/es/components/upload/src/upload.d'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import useFixKeepAliveListRefresh from '@/hooks/useFixKeepAliveListRefresh'
import { HTTP_CONFIG, RESOURCE_PATH } from '@/config/http'
import sliderRules from '@/validator/slider'
import {
  SliderProps,
  addSliderApi,
  SliderDetailProps,
  sliderDetailApi,
  EditSliderProps,
  editSliderApi
} from '@/api/slider'
export default defineComponent({
  components: {
    PageHeader
  },
  setup () {
    const route = useRoute()
    const { proxy } = (getCurrentInstance() as any)
    useAutoMainContentHeight()
    const { isNeedRefreshLstPage } = useFixKeepAliveListRefresh.recode()
    // 表单
    const formRef = ref()
    const tbIsLoading = ref(false)
    const loading = ref(false)
    const submitBtnTit = computed(() => {
      return mode.value === 'edit' ? '更新' : '添加'
    })
    const isReadOnly = computed(() => {
      return ['detail'].includes(mode.value)
    })
    const formMdl = ref<SliderProps>({
      id: '',
      name: '',
      slider: '',
      page_url: '',
      sort: null,
      status: 1,
      remarks: ''
    })

    const handleSubmit = async () => {
      const validateStatus = await new Promise((resolve) => {
        formRef.value.validate((valid: any) => {
          if (valid) {
            resolve(true)
          } else {
            resolve(false)
            return false
          }
        })
      })

      if (!validateStatus || loading.value) {
        return false
      }

      const data: SliderProps | EditSliderProps = {
        name: formMdl.value.name,
        slider: formMdl.value.slider,
        page_url: formMdl.value.page_url,
        sort: formMdl.value.sort,
        status: formMdl.value.status,
        remarks: formMdl.value.remarks
      }
      const formData = new FormData()
      for (const attr in data) {
        formData.append(attr, data[attr])
      }
      if (mode.value === 'add') {
        loading.value = true
        await addSlider(formData as any)
        loading.value = false
      } else if (mode.value === 'edit') {
        formData.append('id', formMdl.value.id as string)
        loading.value = true
        await editSlider((formData as any))
        loading.value = false
      }
    }

    // 添加
    const addSlider = async (data: SliderProps) => {
      const { status, message } = await addSliderApi(data)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        resetForm()
        isNeedRefreshLstPage.value = true
        proxy.message.success({
          message: '添加成功',
          duration: 3000
        })
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }

    // 编辑
    const editSlider = async (data: EditSliderProps) => {
      const { status, message } = await editSliderApi(data)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        isNeedRefreshLstPage.value = true
        proxy.message.success({
          message: '编辑成功',
          duration: 3000
        })
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }

    // 详情
    const getDetail = async (id: any) => {
      const params: SliderDetailProps = {
        id
      }
      tbIsLoading.value = true
      const { status, data, message } = await sliderDetailApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        formMdl.value = {
          ...data
        }
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
      tbIsLoading.value = false
    }
    // 页面所处模式
    const mode = computed(() => {
      let mode = ''
      if (/\/add$/.test(route.path)) {
        mode = 'add'
      } else if (/\/edit$/.test(route.path)) {
        mode = 'edit'
      } else if (/\/detail$/.test(route.path)) {
        mode = 'detail'
      }
      return mode
    })
    const id = computed(() => {
      return route.query.id
    })
    const hdTitle = computed(() => {
      let tit = ''
      switch (mode.value) {
        case 'add':
          tit = '添加轮播图'
          break
        case 'edit':
          tit = '编辑轮播图'
          break
        case 'detail':
          tit = '轮播图详情'
          break
      }
      return tit
    })
    onMounted(() => {
      nextTick(() => {
        if (id.value && ['edit', 'detail'].includes(mode.value)) {
          formMdl.value.id = id.value as string
          getDetail(id.value)
        }
      })
    })

    // 轮播图片
    const fileList = ref<UploadFile[]>([])
    const imgUrl = computed(() => {
      let url = ''
      if ((formMdl.value.slider as any) instanceof File) {
        const windowURL = window.URL || window.webkitURL
        url = windowURL.createObjectURL(formMdl.value.slider as any)
      } else if ((typeof formMdl.value.slider) === 'string') {
        url = RESOURCE_PATH + formMdl.value.slider
      }
      return url
    })
    const handleChooseFile = (e: Event) => {
      if (formMdl.value.slider) {
        proxy.message({
          type: 'warning',
          message: '目前只能上传一张图片, 如需上传可先删除',
          duration: 1000
        })
        e.stopPropagation()
      }
    }
    const handleChangeSlider = (file: UploadFile) => {
      // 校验图片size大小
      const sizeValide = 1024 * 500
      // 校验类型
      const typeValide = ['image/jpeg', 'image/png']
      if ((file.size as any) > sizeValide) {
        proxy.message({
          message: '图片大小不能超过500KB',
          type: 'warning'
        })
        fileList.value = []
        return
      }
      if (!typeValide.includes((file.raw as any).type)) {
        proxy.message({
          message: '图片类型值允许 JPG、PNG',
          type: 'warning'
        })
        fileList.value = []
        return
      }
      formMdl.value.slider = (file.raw as any)
      fileList.value = [file]
    }
    const handleBeforeRemoveSlider = () => {
      proxy.$confirm('确定移除图片吗', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(async () => {
        formMdl.value.slider = ''
        fileList.value = []
      }).catch(() => {
        return false
      })
      return false
    }

    // 重置
    const resetForm = () => {
      formRef.value.resetFields()
    }

    return {
      mode,
      hdTitle,
      formRules: sliderRules,
      formRef,
      tbIsLoading,
      loading,
      submitBtnTit,
      isReadOnly,
      formMdl,
      handleSubmit,
      resetForm,
      getDetail,
      fileList,
      imgUrl,
      handleChooseFile,
      handleChangeSlider,
      handleBeforeRemoveSlider
    }
  }
})
</script>
<style lang="scss" scoped>
.add-slider {
  :deep(.el-upload ){
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    &:hover {
      border-color: #409EFF;
    }
    .icon-upload {
      font-size: 28px;
      color: #8c939d;
      width: 110px;
      height: 110px;
      line-height: 110px;
      text-align: center;
    }
  }
}
</style>
