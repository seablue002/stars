/*
 * 内存方式缓存存储类
 */
// TODO 暂无运用场景，待后续实现

class Memory {
  setItem() {
    console.log('setItem', this)
  }

  getItem() {
    console.log('getItem', this)
  }

  removeItem() {
    console.log('removeItem', this)
  }

  clear() {
    console.log('clear', this)
  }
}

export default Memory
