import { createStore } from 'vuex'
import app, { AppProps } from './app'
import user, { UserProps } from './user'
import common, { CommonProps } from './common'

export interface GlobalDataProps {
  app: AppProps;
  user: UserProps;
  common: CommonProps;
}

export default createStore({
  modules: {
    app,
    user,
    common
  }
})
