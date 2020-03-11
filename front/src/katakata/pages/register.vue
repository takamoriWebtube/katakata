<template>
    <div class="register">
        <div class="box" v-if="!loading">
            <div  v-if="flag">
                <h1>
                    パスワード登録(本登録)
                </h1>
                <form>
                    <div class="form-item">
                        <label for="password">パスワード</label>
                        <input id="password" type="password" v-model="password">
                        <ul v-if="passwordErrors">
                            <li v-for="(error, index) in passwordErrors" :key="index">
                                {{ error }}
                            </li>
                        </ul>
                    </div>
                    <div class="form-item">
                        <label for="repassword">確認用パスワード</label>
                        <input id="repassword" type="password" v-model="rePassword">
                        <ul v-if="rePasswordErrors">
                            <li v-for="(error, index) in rePasswordErrors" :key="index">
                                {{ error }}
                            </li>
                        </ul>
                    </div>
                    <div class="form-actions">
                        <button @click.prevent="send" :disabled="sendLoading">
                            本登録
                        </button>
                        <div v-if="sendLoading">
                            送信中
                        </div>
                    </div>
                </form>
            </div>
            <div v-if="!flag">
                <h1>
                    トークンの期限がきれています。
                </h1>
                <button @click="redirect('/signup')">
                    新規登録画面へ
                </button>
                {{flag}}
            </div>
        </div>
        <div v-if="loading">
            <Loading />
        </div>
    </div>
</template>

<script>
import Loading from '~/components/atoms/loading.vue'
const Cookie = process.client ? require('js-cookie') : undefined
export default {
    data () {
        return {
            flag: false,
            loading: true,
            sendLoading: false,
            password: '',
            rePassword: '',
            passwordErrors: '',
            rePasswordErrors: '',
        }
    },
    components: {
        Loading
    },
    mounted () {
        this.$axios.post('https://katakata.local/api/urltoken', {urltoken: this.$route.query.urltoken})
        .then(res => {
            this.flag = true
            this.loading = false
        })
        .catch(err => {
            this.loading = false
        })
    },
    // asyncData({redirect, query, app }) {
    //     console.log(query.urltoken)
    //     return app.$axios.post('http://back:9000/api/urltoken', {urltoken: query.urltoken})
    //     .then(res => {
    //         console.log(res)
    //         return {
    //             flag: true,
    //             loading: true
    //         }
    //     })
    //     .catch(err => {
    //         console.log(err)
    //         return {
    //             loading: true
    //         }
    //     }) 
    //     // if (process.server) {
    //     //    console.log('takamori')   
    //     // }
        
    // },
    // middleware ({ query, redirect}) {
    //     return axios.post('https://katakata.local/api/urltoken', {urltoken: query.urltoken})
    //     .then(res => {
    //         console.log(res)
    //         return redirect('/signup')
    //     })
    //     .catch(error => {
    //         return redirect('/signin')
    //         console.log(error)
    //     })
    // },
    
    methods: {
        redirect(url) {
            this.$router.push(url)
        },
        
        send() {
            this.sendLoading = true
            this.$axios.post('https://katakata.local/api/register', {urltoken: this.$route.query.urltoken, password: this.password, password_confirmation: this.rePassword})
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
                    this.passwordErrors = err.response.data.errors.password
                    this.rePasswordErrors = err.response.data.errors.password_confirmation
                    this.sendLoading = false
                    return
                }
                this.sendLoading = false
                alert('予期せぬエラー')
            })
        }
        // async asyncData({query}) {
        //     console.log(query)
        //     const response = await this.$axios.post('https://katakata.local/api/urltoken', query.urltoken);
        // }
    }
    
}
</script>

