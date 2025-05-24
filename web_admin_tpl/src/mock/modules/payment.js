import { mock } from 'mockjs'

const paymentMock = () => {
  mock(/\/api\/v1\/payment/, 'post', (options) => {
    console.log('/api/v1/payment接口请求参数', JSON.parse(options.body))

    return {
      code: 200,
      message: '转账成功',
    }
  })
}

export default paymentMock
