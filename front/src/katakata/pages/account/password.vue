<template>
    <div class="account">
        <h1>パスワード</h1>
        <button @click="logout">
          Logout
        </button>
    </div>
</template>

<script>
const Cookie = process.client ? require('js-cookie') : undefined
export default {
    middleware: 'authenticated',
    methods: {
        logout () {
            Cookie.remove('auth')
            this.$store.commit('setAuth', null)
            this.$router.push('/')
        }
    }
    
}
</script>