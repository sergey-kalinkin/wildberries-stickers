<template>

    <div>
        <a data-bs-toggle="modal" data-bs-target="#modal-authorization_widget" href="#" class="header__lk-button h6">Войти</a>

        <div id="modal-authorization_widget" class="custom-modal modal fade">

        <Login @loginForm="isLogin=false" v-show="isLogin"></Login>
        <Register @registerForm="isLogin=true" v-show="!isLogin"></Register>

        </div>
    </div>
</template>

<script>
import Login from "./Login";
import Register from "./Register";

export default {
    name: "AuthorizationWidget",

    components: {
        Login,
        Register
    },

    data: ()=>({
        isLogin: 1,

    }),

    methods: {

    }
}
</script>

<style scoped>

</style>
