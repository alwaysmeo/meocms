import globals from 'globals'
import pluginJs from '@eslint/js'
import pluginVue from 'eslint-plugin-vue'

export default [
	{ languageOptions: { globals: globals.browser } },
	pluginJs.configs.recommended,
	...pluginVue.configs['flat/essential'],
	{
		rules: {
			eqeqeq: 'warn',
			indent: ['warn', 'tab', { SwitchCase: 1 }],
			'no-const-assign': 'error',
			'no-duplicate-case': 'error',
			'comma-spacing': ['error', { before: false, after: true }],
			'no-unused-vars': ['warn', { vars: 'all', args: 'none' }],
			'keyword-spacing': ['error', { before: true, after: true }],
			'vue/script-indent': ['error', 'tab'],
			'vue/multi-word-component-names': 'off',
			'vue/html-self-closing': ['error', { html: { void: 'always', normal: 'never', component: 'always' } }]
		}
	}
]
