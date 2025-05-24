<template>
  <div class="page-container page-container--bg">
    <el-card v-loading="loadding" shadow="never" class="border-0">
      <el-tabs v-model="activeTabName" type="card">
        <el-tab-pane label="基础信息" name="base">
          <BaseForm ref="baseFormRef"></BaseForm>
        </el-tab-pane>
        <el-tab-pane label="其他信息" name="extend">
          <ExtendForm ref="extendFormRef"></ExtendForm>
        </el-tab-pane>
      </el-tabs>

      <div
        v-if="isCreateMode || isEditMode"
        class="mt-[24px] mb-[48px] ml-[100px]"
      >
        <el-button @click="handleReset">重置</el-button>
        <el-button type="primary" @click="handleSubmit">提交</el-button>
      </div>
    </el-card>
  </div>
</template>
<script>
import { ref, computed, onMounted, nextTick, onActivated } from 'vue'
import useAddPage from '@/hooks/business/useAddPage'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import useCachedPageJudgmentRefresh from '@/hooks/useCachedPageJudgmentRefresh'
import { formateResourceUrl } from '@/utils/other/common'
import BaseForm from './components/BaseForm.vue'
import ExtendForm from './components/ExtendForm.vue'

export default {
  name: 'AddInfo',
  components: {
    BaseForm,
    ExtendForm,
  },
  setup(props) {
    const { isCreateMode, isEditMode, isDetailMode } = useAddPage(props)
    const { $api, $apiCode, $message, route } = useCurrentInstance()
    const { isNeedRefreshPage, pageRefreshRecode } =
      useCachedPageJudgmentRefresh()
    pageRefreshRecode()

    // tab
    const activeTabName = ref('base')

    // 表单
    const baseFormRef = ref(null)
    const extendFormRef = ref(null)
    const formMdlCommon = ref({
      id: '',
    })
    const formMdl = computed(() => {
      return {
        ...formMdlCommon.value,
        ...baseFormRef.value.formMdl,
        ...extendFormRef.value.formMdl,
      }
    })

    const infoId = route.query.id

    onMounted(() => {
      if (infoId && (isEditMode.value || isDetailMode.value)) {
        formMdlCommon.value.id = infoId
      }
    })
    onActivated(() => {
      if (infoId && (isEditMode.value || isDetailMode.value)) {
        formMdlCommon.value.id = infoId
      }
    })

    const loadding = ref(false)

    const handleReset = () => {
      baseFormRef.value.handleReset()
      extendFormRef.value.handleReset()
    }

    const handleSubmit = async () => {
      const validateBaseForm = await new Promise((resolve) => {
        baseFormRef.value.formRef.validate(resolve)
      })

      const validateExtendForm = await new Promise((resolve) => {
        baseFormRef.value.formRef.validate(resolve)
      })

      if (!validateBaseForm || !validateExtendForm) {
        return
      }

      const data = {
        column_id: formMdl.value.column_id,
        title: formMdl.value.title,
        sub_title: formMdl.value.sub_title,
        content: formMdl.value.content,
        label: formMdl.value.label,
        title_url: formMdl.value.title_url,
        cover: formMdl.value.cover,
        publish_time: formMdl.value.publish_time,
      }

      const formData = new FormData()
      // 普通数据组装
      Object.keys(data).forEach((attr) => {
        formData.append(attr, data[attr])
      })

      // 图片集数据组装
      formMdl.value.images.forEach((image, index) => {
        formData.append(`images[${index}]`, image)
      })

      if (isCreateMode.value) {
        loadding.value = true
        await addInfo(formData)
        loadding.value = false
      } else if (isEditMode.value) {
        formData.append('id', formMdl.value.id)
        loadding.value = true
        await editInfo(formData)
        loadding.value = false
      }
    }

    // 添加
    const addInfo = async (params) => {
      const apiRes = await $api.info.addInfoApi(params)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        handleReset()
        isNeedRefreshPage.value = true
        $message.success({
          message,
          duration: 3000,
        })
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 编辑
    const editInfo = async (params) => {
      const apiRes = await $api.info.editInfoApi(params)
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        baseFormRef.value.resetSelectedFiles()
        // 回显cover、images
        const { cover_url: coverUrl, images } = data
        // cover封面
        baseFormRef.value.formMdl.cover = coverUrl
        const coverFullPath = formateResourceUrl(coverUrl)
        baseFormRef.value.fileList = [
          {
            id: coverFullPath,
            name: coverFullPath,
            url: coverFullPath,
            isRemote: true,
          },
        ]

        // 图片集合
        if (images && images.length) {
          baseFormRef.value.picFileList = images.map((image) => {
            const picFullPath = formateResourceUrl(image.url)
            return {
              id: picFullPath,
              name: picFullPath,
              url: picFullPath,
              isRemote: true,
            }
          })
        }

        isNeedRefreshPage.value = true

        $message.success({
          message,
          duration: 3000,
        })
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 详情
    const getDetail = async (id) => {
      const params = {
        id,
      }
      const apiRes = await $api.info.infoDetailApi(params)
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        const {
          label_ids: labelIds,
          cover_url: coverUrl,
          images,
          ...extra
        } = data

        baseFormRef.value.formMdl = {
          ...baseFormRef.value.formMdl,
          ...extra,
          cover: coverUrl,
        }

        if (coverUrl) {
          const coverFullPath = formateResourceUrl(coverUrl)
          baseFormRef.value.fileList = [
            {
              id: coverFullPath,
              name: coverFullPath,
              url: coverFullPath,
              isRemote: true,
            },
          ]
        }

        baseFormRef.value.editorRef.setHtml(data.content)

        // 图片集合
        baseFormRef.value.picFileList = images.map((image) => {
          const picFullPath = formateResourceUrl(image.url)
          return {
            id: picFullPath,
            name: picFullPath,
            url: picFullPath,
            isRemote: true,
          }
        })

        const labelIdsNumber = labelIds.map((label) => {
          return Number(label)
        })
        extendFormRef.value.labelTreeRef.setCheckedKeys(labelIdsNumber)

        const labellInaccuracyCheckedIds =
          extendFormRef.value.labelTreeRef.getCheckedKeys()
        // 识别出tree组件不精准选中的label标签ids
        const labellErrorCheckedIds = labellInaccuracyCheckedIds.filter(
          (labelId) => !labelIdsNumber.includes(labelId)
        )

        // 取消不精准选中的label标签选中状态
        labellErrorCheckedIds.forEach((labelId) => {
          extendFormRef.value.labelTreeRef.setChecked(labelId, false)
        })
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    onMounted(async () => {
      loadding.value = true
      await extendFormRef.value.getLabelBaseInfoList()
      nextTick(async () => {
        if (
          formMdlCommon.value.id &&
          (isEditMode.value || isDetailMode.value)
        ) {
          await getDetail(formMdlCommon.value.id)
        }
      })
      loadding.value = false
    })

    return {
      activeTabName,
      isCreateMode,
      isEditMode,
      baseFormRef,
      extendFormRef,
      formMdl,
      loadding,
      handleReset,
      handleSubmit,
    }
  },
}
</script>
<style lang="scss" scoped></style>
