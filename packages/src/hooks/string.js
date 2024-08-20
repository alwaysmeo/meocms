export default {
	/** 字符串首字母转大写 */
	useCapitalCase: (str) => {
		return str.charAt(0).toUpperCase() + str.slice(1)
	}
}
