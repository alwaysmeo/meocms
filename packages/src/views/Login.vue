<script setup>
	import { message } from 'ant-design-vue'
	import { isEmpty, isEqual, random } from 'radash'
	import hooks from '@hooks'
	import stores from '@stores'
	import accountApi from '@apis/account'
	import commonApi from '@apis/common'
	import i18n from '@language'
	import dayjs from 'dayjs'

	defineOptions({ name: 'Login' })
	const router = useRouter()

	const { t } = i18n.global
	const userInfoStore = stores.useUserInfoStore()

	const state = reactive({
		year: computed(() => (dayjs().format('YYYY') > 2024 ? `2024-${dayjs().format('YYYY')}` : dayjs().format('YYYY'))),
		captcha: null
	})

	const blobsRef = ref()
	class Blob {
		constructor(el) {
			const MIN_SPEED = 1
			const MAX_SPEED = 2
			this.el = el
			const boundingRect = this.el.getBoundingClientRect()
			this.size = boundingRect.width
			this.initialX = random(0, window.innerWidth - this.size)
			this.initialY = random(0, window.innerHeight - this.size)
			this.el.style.top = `${this.initialY}px`
			this.el.style.left = `${this.initialX}px`
			this.vx = random(MIN_SPEED, MAX_SPEED) * (Math.random() > 0.5 ? 1 : -1)
			this.vy = random(MIN_SPEED, MAX_SPEED) * (Math.random() > 0.5 ? 1 : -1)
			this.x = this.initialX
			this.y = this.initialY
		}
		move() {
			this.x += this.vx
			this.y += this.vy
			if (this.x >= window.innerWidth - this.size / 2) {
				this.x = window.innerWidth - this.size / 2
				this.vx *= -1
			}
			if (this.y >= window.innerHeight - this.size / 2) {
				this.y = window.innerHeight - this.size / 2
				this.vy *= -1
			}
			if (this.x <= -this.size / 2) {
				this.x = -this.size / 2
				this.vx *= -1
			}
			if (this.y <= -this.size / 2) {
				this.y = -this.size / 2
				this.vy *= -1
			}
			this.el.style.transform = `translate(${this.x - this.initialX}px, ${this.y - this.initialY}px)`
		}
	}

	onMounted(async () => {
		const blobEls = blobsRef.value.querySelectorAll('.bouncing-blob')
		const blobs = Array.from(blobEls).map((blobEl) => new Blob(blobEl))
		function update() {
			requestAnimationFrame(update)
			blobs.forEach((blob) => {
				blob.move()
			})
		}
		requestAnimationFrame(update)
		if (await hooks.useBotd()) captcha()
	})

	const form = reactive({
		area_list: [],
		data: {
			account: '',
			password: ''
		},
		rules: {
			account: [{ required: true, message: t('meo.form.tip.account'), trigger: 'blur' }],
			password: [{ required: true, message: t('meo.form.tip.password'), trigger: 'blur' }],
			captcha_value: [{ required: true, message: t('meo.form.tip.error.captcha_format'), trigger: 'blur' }]
		}
	})

	async function onFinish() {
		const params = {
			account: form.data.account,
			password: form.data.password
		}
		if (!isEmpty(state.captcha)) {
			Object.assign(params, {
				captcha: {
					key: state.captcha.key,
					value: form.data.captcha_value
				}
			})
		}
		const { code, data } = await accountApi.login(params)
		if (isEqual(code, 200)) {
			message.success(t('meo.form.tip.success.submit_login'))
			await userInfoStore.set(data)
			router.push({ name: 'home' })
		} else {
			form.data.password = ''
			if (state.captcha) {
				form.data.captcha_value = ''
				await captcha()
			}
		}
	}

	async function captcha() {
		const { code, data } = await commonApi.captcha()
		if (isEqual(code, 200)) {
			state.captcha = data
		}
	}
</script>

<template>
	<div>
		<div class="bouncing-blobs-container">
			<div class="bouncing-blobs-glass"></div>
			<div ref="blobsRef" class="bouncing-blobs">
				<div class="bouncing-blob bouncing-blob-blue"></div>
				<div class="bouncing-blob bouncing-blob-purple"></div>
				<div class="bouncing-blob bouncing-blob-blue"></div>
				<div class="bouncing-blob bouncing-blob-blue"></div>
				<div class="bouncing-blob bouncing-blob-white"></div>
				<div class="bouncing-blob bouncing-blob-purple"></div>
				<div class="bouncing-blob bouncing-blob-purple"></div>
				<div class="bouncing-blob bouncing-blob-pink"></div>
				<div class="bouncing-blob bouncing-blob-purple"></div>
				<div class="bouncing-blob bouncing-blob-pink"></div>
				<div class="bouncing-blob bouncing-blob-blue"></div>
			</div>
		</div>
		<div class="login-container">
			<div class="title">
				<p>Meo</p>
				<p>Content Management System</p>
			</div>
			<a-form :model="form.data" :rules="form.rules" autocomplete="off" @finish="onFinish">
				<a-form-item class="form-item" name="account">
					<a-input v-model:value="form.data.account" :placeholder="$t('meo.form.tip.account')">
						<template #prefix>
							<ant-user-outlined />
						</template>
					</a-input>
				</a-form-item>
				<a-form-item class="form-item" name="password">
					<a-input-password v-model:value="form.data.password" :placeholder="$t('meo.form.tip.password')">
						<template #prefix>
							<ant-lock-outlined />
						</template>
					</a-input-password>
				</a-form-item>
				<a-form-item v-if="state.captcha" class="form-item" name="captcha_value">
					<div class="captcha-container">
						<a-input v-model:value="form.data.captcha_value" :placeholder="$t('meo.form.tip.account')" :maxlength="6">
							<template #prefix>
								<ant-safety-certificate-outlined />
							</template>
						</a-input>
						<div class="captcha" @click="captcha()">
							<img :src="state.captcha.img" alt="captcha" />
						</div>
					</div>
				</a-form-item>
				<a-form-item>
					<a-button class="submit-container" type="primary" html-type="submit" block>
						{{ $t('meo.form.submit') }}
					</a-button>
				</a-form-item>
			</a-form>
		</div>
		<div class="footer-container">© Copyright {{ state.year }} {{ $t('meo.project_name') }} 粤ICP备2022083294号-2</div>
	</div>
</template>

<style lang="scss" scoped>
	.bouncing-blobs-container {
		position: fixed;
		z-index: -1;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		.bouncing-blobs-glass {
			position: absolute;
			z-index: 2;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			backdrop-filter: blur(140px);
			-webkit-backdrop-filter: blur(140px);
			pointer-events: none;
		}
		.bouncing-blobs {
			position: absolute;
			z-index: 1;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			.bouncing-blob {
				width: 32vw;
				aspect-ratio: 1;
				border-radius: 50%;
				will-change: transform;
				position: absolute;
				z-index: 1;
				top: 0;
				left: 0;
				transform-origin: left top;
				&-blue {
					background: #00a6ffa5;
				}
				&-white {
					background: #ffffff;
					z-index: 2;
					width: 15vw;
				}
				&-purple {
					background: #9180ff;
				}
				&-pink {
					background: #ff6acb84;
				}
			}
		}
	}

	.login-container {
		margin: 20vh auto 0 auto;
		padding: 20px;
		max-width: 380px;
		.title {
			margin-bottom: 30px;
			text-align: center;
			font-weight: 700;
			color: white;
			font-size: 22px;
			text-shadow: 0 0 10px #9180ff;
		}
		.form-item {
			:deep(input),
			:deep(:where(.css-dev-only-do-not-override-19iuou).ant-input-affix-wrapper) {
				border-top: none;
				border-left: none;
				border-right: none;
				border-bottom-color: #ffffff7f;
				background-color: transparent;
				border-radius: 0;
				color: white;
				&::placeholder {
					color: #ffffff4c;
				}
			}
		}
		.captcha-container {
			display: grid;
			grid-template-columns: 1fr 100px;
			gap: 10px;
			.captcha {
				cursor: pointer;
				img {
					width: 100%;
					height: 100%;
					border-radius: 4px;
				}
			}
		}
		.submit-container {
			button {
				margin-top: 20px;
				border: none;
				display: block;
				width: 100%;
				height: 40px;
				font-size: 14px;
				color: #ffffff;
				border-radius: 20px;
				backdrop-filter: blur(10px);
				background-color: #ffffff4d;
				box-shadow: 0 0 6px #ffffff4d;
				transition: all 0.3s;
				&:hover {
					backdrop-filter: blur(0);
					background-color: #dbe4ff4d;
					box-shadow: 0 0 18px #55cfff4d;
				}
			}
		}
	}

	.footer-container {
		font-size: 12px;
		color: white;
		text-align: center;
		position: fixed;
		bottom: 30px;
		left: 50%;
		transform: translateX(-50%);
		text-shadow: 0 0 6px #9180ff;
	}

	@media (max-width: 1200px) {
		.bouncing-blobs-container .bouncing-blobs-glass {
			backdrop-filter: blur(120px);
			-webkit-backdrop-filter: blur(120px);
		}
	}

	@media (max-width: 500px) {
		.bouncing-blobs-container {
			.bouncing-blobs-glass {
				backdrop-filter: blur(90px);
				-webkit-backdrop-filter: blur(90px);
			}
			.bouncing-blobs {
				.bouncing-blob {
					width: 60vw;
					&-white {
						width: 30vw;
					}
				}
			}
		}
	}
</style>
