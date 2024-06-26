<script setup>
	import { iconUser, iconLock } from '@opentiny/vue-icon'
	import { useMessage } from '@hooks/useMessage'
	import { isEqual } from 'radash'
	import i18n from '@language'
	import dayjs from 'dayjs'

	defineOptions({ name: 'Login' })
	const { t } = i18n.global

	const MIN_SPEED = 1
	const MAX_SPEED = 2
	function randomNumber(min, max) {
		return Math.random() * (max - min) + min
	}
	class Blob {
		constructor(el) {
			this.el = el
			const boundingRect = this.el.getBoundingClientRect()
			this.size = boundingRect.width
			this.initialX = randomNumber(0, window.innerWidth - this.size)
			this.initialY = randomNumber(0, window.innerHeight - this.size)
			this.el.style.top = `${this.initialY}px`
			this.el.style.left = `${this.initialX}px`
			this.vx = randomNumber(MIN_SPEED, MAX_SPEED) * (Math.random() > 0.5 ? 1 : -1)
			this.vy = randomNumber(MIN_SPEED, MAX_SPEED) * (Math.random() > 0.5 ? 1 : -1)
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
	const blobsRef = ref()
	function initBlobs() {
		const blobEls = blobsRef.value.querySelectorAll('.bouncing-blob')
		const blobs = Array.from(blobEls).map((blobEl) => new Blob(blobEl))

		function update() {
			requestAnimationFrame(update)
			blobs.forEach((blob) => {
				blob.move()
			})
		}

		requestAnimationFrame(update)
	}

	const year = computed(() =>
		dayjs().format('YYYY') > 2024 ? `2024-${dayjs().format('YYYY')}` : dayjs().format('YYYY')
	)

	onMounted(() => {
		initBlobs()
	})

	const formRef = ref()
	const form = reactive({
		area_list: [],
		data: {
			account: '',
			password: '',
			remember: false
		},
		rules: {
			account: [{ required: true, message: t('meo.form.tip.account'), trigger: 'blur' }],
			password: [{ required: true, message: t('meo.form.tip.password'), trigger: 'blur' }]
		}
	})
	async function submit() {
		await formRef.value.validate(async (valid) => {
			if (!valid) return
			// const { code } = await messageApi.create({
			// 	...form.data,
			// 	images: isEmpty(form.data.images) ? undefined : JSON.stringify(form.data.images),
			// 	video: isEmpty(form.data.video) ? undefined : JSON.stringify(form.data.video),
			// 	userId: user_info.userId
			// })
			if (isEqual(code, 200)) {
				useMessage('提交成功', 'success')
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
					<tiny-input v-model="form.data.account" :prefix-icon="iconUser()" :placeholder="$t('meo.form.tip.account')" />
				</tiny-form-item>
				<tiny-form-item class="form-item" prop="password">
					<tiny-input
						v-model="form.data.password"
						:prefix-icon="iconLock()"
						:placeholder="$t('meo.form.tip.password')"
						type="password"
						show-password
					/>
				</tiny-form-item>
				<tiny-form-item class="remember-container" prop="remember">
					<tiny-checkbox v-model="form.data.remember" name="tiny-checkbox">
						{{ $t('meo.form.tip.remember') }}
					</tiny-checkbox>
				</tiny-form-item>
			</tiny-form>
			<div class="submit-container">
				<tiny-button @click="submit">{{ $t('meo.form.tip.submit') }}</tiny-button>
			</div>
		</div>
		<div class="footer-container">© Copyright {{ year }} {{ $t('meo.project_name') }} 粤ICP备2022083294号-2</div>
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
		.remember-container {
			text-align: right;
			:deep(.tiny-checkbox .tiny-checkbox__inner) {
				text-align: left;
			}
			:deep(.tiny-checkbox__label) {
				color: #ffffff4c;
			}
		}
		.submit-container {
			button {
				border: none;
				background-color: #ffffff4d;
				backdrop-filter: blur(10px);
				display: block;
				width: 100%;
				height: 40px;
				border-radius: 20px;
				box-shadow: 0 0 6px #ffffff4d;
				font-size: 14px;
				color: #ffffff;
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
