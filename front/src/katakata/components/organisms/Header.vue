<template>
    <header class="header">
        <ul class="header__links">
            <li>
                <nuxt-link to="/">トップ</nuxt-link>
            </li>
            <li>
                <nuxt-link to="/account/profile">アカウント</nuxt-link>
            </li>
            <li>
                <nuxt-link to="/signin">サインイン</nuxt-link>
            </li>
            <li>
                <nuxt-link to="/signup">新規アカウント登録</nuxt-link>
            </li>
            <li>
                <nuxt-link to="/plan">ポイント購入</nuxt-link>
            </li>
        </ul>
    </header>
</template>

<style lang="scss">
.header__links {
    display: flex;
    li {
        list-style: none;
        margin-right: 8px;
    }
}
</style>
