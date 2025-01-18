import { CommonProps } from './index'

const mutations = {
  SET_SYSTEM_CONFIG: (state: CommonProps, data: {[key: string]: any}): void => {
    state.config.systemConfig = data
  }
}
export default mutations
