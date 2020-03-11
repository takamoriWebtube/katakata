<template>
    <ul class="account-links">
        <li>
            <nuxt-link to="/account/profile">プロフィール変更</nuxt-link>
        </li>
        <li>
            <nuxt-link to="/account/password">パスワード変更</nuxt-link>
        </li>
            <li>
            <nuxt-link to="/account/email">メールアドレス変更</nuxt-link>
        </li>
        <li>
            <nuxt-link to="/account/delete">退会手続き</nuxt-link>
        </li>
    </ul>
</template>
<style lang="scss">
.account-links {
    display: flex;
    li {
        list-style: none;
        margin-right: 8px;
    }
}
</style>