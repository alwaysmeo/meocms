module.exports = {
	env: {
		browser: true,
		es2021: true,
		commonjs: true,
		'vue/setup-compiler-macros': true // vue3写法校验
	},
	extends: ['eslint:recommended', 'plugin:vue/vue3-essential', './.eslintrc-auto-import.json'],
	overrides: [
		{
			files: ['*.vue'],
			rules: {
				indent: 'off',
				'vue/script-indent': 'off'
			}
		}
	],
	parserOptions: {
		ecmaVersion: 'latest',
		sourceType: 'module'
	},
	plugins: ['vue'],
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
		'vue/html-self-closing': ['error', { html: { void: 'always', normal: 'never', component: 'always' } }],
		'vue/no-dupe-keys': ['off']
	}
}
