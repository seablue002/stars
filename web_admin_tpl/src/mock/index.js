import Mock from 'mockjs'

const startMock = () => {
  Mock.setup({
    timeout: '300-500',
  })
  const mockFiles = import.meta.glob('./modules/*.js', { eager: true })
  Object.entries(mockFiles).forEach(([, module]) => {
    module.default()
  })
}

const initMock = (debug) => {
  if (debug === false) {
    return
  }

  startMock()
}

export default initMock
