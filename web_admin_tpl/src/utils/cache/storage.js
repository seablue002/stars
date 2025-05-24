import Cache from './lib/Cache'

export const localStorage = new Cache(window.localStorage, 'LOCAL_STORAGE_')

export const sessionStorage = new Cache(
  window.sessionStorage,
  'SESSION_STORAGE_'
)
