<script setup>
	import { useMessage } from '@hooks/useMessage'
	import { isEqual } from 'radash'

	defineOptions({ name: 'Login' })
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
			if (this.x >= window.innerWidth - this.size) {
				this.x = window.innerWidth - this.size
				this.vx *= -1
			}
			if (this.y >= window.innerHeight - this.size) {
				this.y = window.innerHeight - this.size
				this.vy *= -1
			}
			if (this.x <= 0) {
				this.x = 0
				this.vx *= -1
			}
			if (this.y <= 0) {
				this.y = 0
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
			account: [{ required: true, message: '请输入邮箱账号', trigger: 'blur' }],
			password: [{ required: true, message: '请输入账号密码', trigger: 'blur' }]
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
			</div>
		</div>
		<div class="login-container">
			<tiny-form
				ref="formRef"
				:model="form.data"
				:rules="form.rules"
				label-width="70px"
				validate-type="tip"
				validate-position="right"
			>
				<tiny-form-item class="form-item" label="账号：" prop="account">
					<tiny-input v-model="form.data.account" placeholder="请输入邮箱账号" />
				</tiny-form-item>
				<tiny-form-item class="form-item" label="密码：" prop="password">
					<tiny-input v-model="form.data.password" placeholder="请输入账号密码" />
				</tiny-form-item>
				<tiny-form-item class="remember-container" prop="remember">
					<tiny-checkbox v-model="form.data.remember" name="tiny-checkbox">记住我</tiny-checkbox>
				</tiny-form-item>
			</tiny-form>
			<div class="submit-container">
				<tiny-button @click="submit">提交</tiny-button>
			</div>
		</div>
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
		.form-item {
			:deep(input) {
				--ti-input-bg-color: transparent;
				border-top: none;
				border-left: none;
				border-right: none;
			}
		}
		.remember-container {
			text-align: right;
		}
		.submit-container {
			button {
				border: none;
				background-color: #ffffff4d;
				backdrop-filter: blur(10px);
				display: block;
				width: 100%;
			}
		}
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
