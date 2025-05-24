import { mock } from 'mockjs'

const poemMock = () => {
  // 诗歌列表模拟接口
  const poemList = []
  const count = 100
  for (let i = 0; i < count; i += 1) {
    const poem = [
      {
        title: '山居秋暝',
        author: '王维',
        dynasty: '唐',
        category: 5,
      },
      {
        title: '绝句',
        author: '杜甫',
        dynasty: '唐',
        category: 5,
      },
      {
        title: '春晓',
        author: '孟浩然',
        dynasty: '唐',
        category: 2,
      },
      {
        title: '小池',
        author: '杨万里',
        dynasty: '宋',
        category: 5,
      },
      {
        title: '采薇',
        author: '佚名',
        dynasty: '先秦',
        category: 2,
      },
    ]
    const extraInfo = {
      'publishStatus|1': [0, 1, 2],
      createTime: mock('@datetime'),
    }

    // 列表中随机生成index，选取诗歌
    const randomIndex = Math.floor(Math.random() * (poem.length - 1))
    poemList.push(
      mock({
        ...poem[randomIndex],
        cover: `https://picsum.photos/300?random=${i}`,
        ...extraInfo,
      })
    )
  }
  mock(/\/api\/v1\/poem\/list/, 'post', (options) => {
    const {
      title = '',
      category = '',
      page = 1,
      pageSize = 10,
    } = JSON.parse(options.body)

    const mockList = poemList
      .filter((item) => {
        // 标题搜索
        return !(title && item.title.indexOf(title) === -1)
      })
      .filter((item) => {
        // 分类搜索
        return !(category && item.category !== category)
      })

    const pageList = mockList.filter((item, index) => {
      return index < page * pageSize && index >= (page - 1) * pageSize
    })

    const total = mockList.length
    return {
      code: 200,
      message: '请求成功',
      data: {
        list: pageList,
        total,
      },
    }
  })

  // 诗歌分类模拟接口
  const poemCategoryList = [
    {
      id: 1,
      name: '叙事诗',
    },
    {
      id: 2,
      name: '抒情诗',
    },
    {
      id: 3,
      name: '送别诗',
    },
    {
      id: 4,
      name: '边塞诗',
    },
    {
      id: 5,
      name: '山水田园诗',
    },
    {
      id: 6,
      name: '怀古诗',
    },
  ]
  mock(/\/api\/v1\/poem\/categoryList/, 'post', (options) => {
    const { name = '' } = JSON.parse(options.body)
    const mockList = poemCategoryList.filter((item) => {
      return !(name && item.name.indexOf(name) === -1)
    })

    return {
      code: 200,
      message: '请求成功',
      data: mockList,
    }
  })

  // 诗歌新增模拟接口
  mock(/\/api\/v1\/poem\/create/, 'post', (options) => {
    const params = JSON.parse(options.body)
    console.log('诗歌新增接口参数接收：', params)
    return {
      code: 200,
      message: '新增成功',
    }
  })
}

export default poemMock
