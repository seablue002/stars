module.exports = {
  root: true,
  plugins: ['prettier'],
  extends: [
    'plugin:vue/vue3-recommended',
    'airbnb-base',
    'plugin:prettier/recommended',
  ],
  rules: {
    'import/no-cycle': 'off',
    'vue/multi-word-component-names': [
      'error',
      {
        // 允许index命名组件名称无需多单词组合
        ignores: ['index'],
      },
    ],
    'vue/attribute-hyphenation': ['off'],
    'prettier/prettier': ['error', { endOfLine: 'auto' }],
    'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
    indent: 'error',
    'no-param-reassign': ['off'],
    'import/prefer-default-export': ['off'],
    'no-use-before-define': ['off'],
    'vue/v-on-event-hyphenation': ['off'],
  },
  parserOptions: {
    parser: '@babel/eslint-parser',
    requireConfigFile: false,
  },
  settings: {
    // https://www.npmjs.com/package/eslint-import-resolver-vite
    'import/resolver': {
      alias: {
        map: [
          ['@', './src'],
          ['#ASSETS', './src/assets'],
          ['#LAYOUTS', './src/layouts'],
        ],
      },
    },
  },
}
