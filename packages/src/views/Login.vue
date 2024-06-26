<script setup>
	import { IconUser, IconLock, IconHelpful } from '@opentiny/vue-icon'
	import { useBotd } from '@hooks/useFingerprintjs'
	import { useMessage } from '@hooks/useMessage'
	import { isEqual, random } from 'radash'
	import accountApi from '@apis/account'
	import i18n from '@language'
	import dayjs from 'dayjs'

	defineOptions({ name: 'Login' })
	const { t } = i18n.global

	const MIN_SPEED = 1
	const MAX_SPEED = 2

	const blobsRef = ref()
	class Blob {
		constructor(el) {
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
		state.bot = await useBotd()
	})

	const state = reactive({
		year: computed(() => (dayjs().format('YYYY') > 2024 ? `2024-${dayjs().format('YYYY')}` : dayjs().format('YYYY'))),
		bot: false
	})

	const formRef = ref()
	const form = reactive({
		area_list: [],
		data: {
			account: '',
			password: ''
		},
		rules: {
			account: [{ required: true, message: t('meo.form.error_tip.account'), trigger: 'blur' }],
			password: [{ required: true, message: t('meo.form.error_tip.password'), trigger: 'blur' }]
		}
	})

	async function submit() {
		await formRef.value.validate(async (valid) => {
			if (!valid) return
			const { code } = await accountApi.login({
				account: form.data.account,
				password: form.data.password
			})
			if (isEqual(code, 200)) {
				useMessage(t('meo.form.success_tip.submit'), 'success')
			}
		})
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
			<tiny-form
				ref="formRef"
				:model="form.data"
				:rules="form.rules"
				label-width="0"
				validate-type="text"
				validate-position="left-start"
			>
				<tiny-form-item class="form-item" prop="account">
					<tiny-input
						v-model="form.data.account"
						:prefix-icon="IconUser()"
						:placeholder="$t('meo.form.error_tip.account')"
					/>
				</tiny-form-item>
				<tiny-form-item class="form-item" prop="password">
					<tiny-input
						v-model="form.data.password"
						:prefix-icon="IconLock()"
						:placeholder="$t('meo.form.error_tip.password')"
						type="password"
						show-password
					/>
				</tiny-form-item>
				<tiny-form-item class="form-item form-item-vcode" prop="vcode">
					<tiny-input
						v-model="form.data.vcode"
						:prefix-icon="IconHelpful()"
						:placeholder="$t('meo.form.error_tip.vcode')"
					/>
					<div>1231312312</div>
				</tiny-form-item>
			</tiny-form>
			<div class="submit-container">
				<tiny-button @click="submit">{{ $t('meo.form.submit') }}</tiny-button>
			</div>
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
			:deep(input) {
				--ti-input-bg-color: transparent;
				border-top: none;
				border-left: none;
				border-right: none;
				border-bottom-color: #ffffff7f;
				border-radius: 0;
				color: white;
				&::placeholder {
					color: #ffffff4c;
				}
			}
			:deep(.tiny-svg) {
				font-size: 16px;
				fill: #ffffff99;
			}
			:deep(.tiny-form-item__error) {
				padding-left: 30px;
			}
		}
		.form-item-vcode {
			:deep(.tiny-form-item__content-muti-children) {
				display: grid;
				grid-template-columns: 1fr 100px;
				gap: 10px;
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
		:deep(.tiny-form-item.is-error .tiny-input__inner) {
			background-color: transparent;
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
