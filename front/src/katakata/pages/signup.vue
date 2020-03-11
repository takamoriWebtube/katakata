<template>
    <div class="signin">
        <div class="box" v-if="!message">
            <h1>
                無料アカウント登録
            </h1>
            <form>
                <div class="form-item">
                    <label for="username">ユーザー名</label>
                    <input v-model="name" id="username" type="text">
                    <ul v-if="nameErrors">
                        <li v-for="(error, index) in nameErrors" :key="index">
                            {{ error }}
                        </li>
                    </ul>
                </div>
                
                <div class="form-item">
                    <label for="email">メールアドレス</label>
                    <input v-model="email" id="email" type="email">
                    <ul v-if="emailErrors">
                        <li v-for="(error, index) in emailErrors" :key="index">
                            {{ error }}
                        </li>
                    </ul>
                </div>
                <div class="form-actions">
                    <button @click.prevent="send" :disabled="sendLoading">
                        ログイン
                    </button>
                    <div v-if="sendLoading">
                        送信中
                    </div>
                </div>
            </form>
        </div>
        <div class="sns">
            <button @click.prevent="twitterLogin">twitterでログイン</button>
        </div>
        <div class="box" v-if="message">
            {{ message }}
        </div>
    </div>
</template>

<script>
export default {
  middleware: 'notAuthenticated',
  data() {
      return {
          name: '',
          email: '',
          nameErrors: '',
          emailErrors: '',
          message: '',
          sendLoading: false,
      }
  },
  methods: {
      send() {
        this.nameErrors = ''
        this.emailErrors = ''
        this.sendLoading = true
        this.$axios.post('https://katakata.local/api/signup', {name: this.name, email: this.email})
        .then(res => {
            this.sendLoading = false
            this.message = res.data.message
            return
        })
        .catch(err => {
            if (err.response.status === 422) {
                this.nameErrors = err.response.data.errors.name
                this.emailErrors = err.response.data.errors.email
                this.sendLoading = false
                return
            }
            this.sendLoading = false
            alert('予期せぬエラー')
        })
      },
      twitterLogin(sns) {
              this.$axios.get('https://katakata.local/api/oauth/twitter')
              .then(res => {
                  window.location.href = res.data.redirect_url
              })
              .catch(err => {
                  alert('予期せぬエラー')
              })
      }
    //   async firstRegister (context, payload) {
    //     context.commit('setApiStatus', null)
    //     context.commit('setErrorMessages', null)
    //     const response = await axios.post('/api/register/first', payload)
    //     if (response.status === OK) {
    //         context.commit('setApiStatus', true)
    //         context.commit('setFirstRegister', payload)
    //         context.commit('addRegisterProgress', 2)
    //         return
    //     }
    //     context.commit('setApiStatus', false)
    //     if (response.status === UNPROCESSABLE_ENTITY) {
    //         //バリデーションエラー
    //         context.commit('setErrorMessages', response.data.errors)
    //     } else {
    //         context.commit('error/setCode', response.status, { root: true })
    //     }
    // },
  }
}
</script>

<style>

</style>