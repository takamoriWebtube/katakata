<template>
    <div class="account">
        <Header />
        <AccountLinks />
        <h1>プロフィール</h1>
        <button @click="logout">
          Logout
        </button>
    </div>
</template>

<script>
import Header from '~/components/organisms/Header.vue'
import AccountLinks from '~/components/organisms/AccountLinks.vue'
const Cookie = process.client ? require('js-cookie') : undefined
export default {
    middleware: 'authenticated',
    components: {
        Header,
        AccountLinks
    },
    methods: {
        logout () {
            Cookie.remove('auth')
            this.$store.commit('setAuth', null)
            this.$router.push('/')
        }
    }
    
}
</script>