<template>
    <div>
        <p>ツイッターでログインしています。</p>
    </div>
</template>
<script>
const Cookie = process.client ? require('js-cookie') : undefined
export default {
    middleware: 'notAuthenticated',
    data() {
        return {
            token: '',
            user: '',
        }
    },
    mounted () {
      this.$axios.$get('https://katakata.local/api/oauth/twitter/callback', { params: this.$route.query })
      .then(res => {
        // console.log(res.access_token)
        // alert()
          const auth = {
              access_token: res.access_token,
              token_type: res.token_type,
              expires_in: res.expires_in,
              user: res.user
          }
          this.$store.commit('setAuth', auth)
          Cookie.set('auth', auth)
          this.redirect('/')
          return
      })
      .catch(err => {
          alert('予期せぬエラー')
      })
    },
    methods: {
      redirect(url) {
        this.$router.push(url)
      },
    }
    
}
</script>>
