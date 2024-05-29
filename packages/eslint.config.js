import globals from 'globals'
import pluginJs from '@eslint/js'
import pluginVue from 'eslint-plugin-vue'
import parserVue from 'vue-eslint-parser'
import aotuImportGlobals from './.eslintrc-auto-import.js'

export default [
	// eslint 默认推荐规则
	pluginJs.configs.recommended,
	// vue3 基础推荐规则
	...pluginVue.configs['flat/essential'],
	// eslint 配置
	{
		languageOptions: {
			globals: {
				...globals.browser,
				...globals.es2021,
				...globals.node,
				...aotuImportGlobals['globals']
			},
			ecmaVersion: 'latest',
			sourceType: 'module',
			parser: parserVue
		}
	},
	{
		files: ['src/**/*.js'],
		rules: {
			eqeqeq: 'error',
			indent: ['error', 'tab', { SwitchCase: 1 }],
			'no-const-assign': 'error',
			'no-duplicate-case': 'error',
			'no-unused-vars': ['error', { vars: 'all', args: 'none' }],
			'comma-spacing': ['error', { before: false, after: true }],
			'keyword-spacing': ['error', { before: true, after: true }]
		}
	},
	{
		files: ['src/**/*.vue'],
		rules: {
			indent: 'off',
			'vue/script-indent': ['error', 'tab', { baseIndent: 1 }],
			'vue/multi-word-component-names': 'off',
			'vue/no-parsing-error': 'off',
			'vue/html-self-closing': ['error', { html: { void: 'always', normal: 'never', component: 'always' } }]
		}
	}
]
