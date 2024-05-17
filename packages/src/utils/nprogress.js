import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

NProgress.configure({
    speed: 800,
    trickleSpeed: 300,
    minimum: 0.1,
    easing: 'ease',
    showSpinner: false,
    parent: 'body'
})

export default NProgress
