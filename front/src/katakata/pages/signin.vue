<template>
    <div class="signin">
        <h1>
            ログイン
        </h1>
        <form>
            <div class="form-item">
                <label for="">メールアドレス</label>
                <input id="email" type="email" v-model="email">
            </div>
            <div class="form-item">
                <label for="">パスワード</label>
                <input id="password" type="password" v-model="password">
            </div>
            <div class="form-actions">
                <p v-if="error">{{ error }}</p>
                <button @click.prevent="send" :disabled="sendLoading">
                    ログイン
                </button>
                <div v-if="sendLoading">
                    送信中
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import Loading from '~/components/atoms/loading.vue'
const Cookie = process.client ? require('js-cookie') : undefined
export default {
    middleware: 'notAuthenticated',
    data() {
        return {
            email: '',
            password: '',
            sendLoading: false,
            error: '',
        }
    },
    methods: {
        send() {
            this.error = ''
            this.sendLoading = true
            this.$axios.post('https://katakata.local/api/signin', {email: this.email, password: this.password})
            .then(res => {
                this.sendLoading = false
                const auth = {
                    access_token: res.data.access_token,
                    token_type: res.data.token_type,
                    expires_in: res.data.expires_in,
                    user: res.data.user
                }
                this.$store.commit('setAuth', auth)
                Cookie.set('auth', auth)
                this.redirect('/')
                return
            })
            .catch(err => {
                if (err.response.status === 422) {
                    this.error = err.response.data.error
                    this.sendLoading = false
                    return
                }
                this.sendLoading = false
                alert('予期せぬエラー')
            })
        },
        redirect(url) {
            this.$router.push(url)
        },
        asyncData ({ app, error }) {
            return app.$axios.$get('/api/twitter')
            .then(data => {
                return { twitterAuthUrl: data.redirect_url }
            })
            .catch(e => error({ message: e.message, statusCode: e.statusCode }))
        },
    }
    
}
</script>

<style>

</style>