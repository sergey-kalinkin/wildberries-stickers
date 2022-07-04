<template>
    <div class="modal-dialog modal-dialog-centered">
        <div class="custom-modal__content">
            <button type="button" class="custom-modal__close" data-bs-dismiss="modal">
                <svg><use xlink:href="#svg-close"></use></svg>
            </button>

            <p class="h2">Войдите в аккаунт</p>

            <form @submit.prevent="login">
                <div class="custom-modal__text mb-3">
                    <p><a @click.prevent="evtLogin" href="#">ещё нет аккаунта?</a></p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email адрес</label>
                    <input v-model="data.email"
                           type="text"
                           placeholder="ivanov@example.ru"
                           class="form-control"
                           :class="errors.email ? 'is-invalid' : ''"
                           @input="validateEmail">
                    <div class="invalid-feedback">
                        {{ errors.email ? errors.email[0] : '' }}
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <input
                        v-model="data.password"
                        type="password"
                        class="form-control"
                        :class="errors.password ? 'is-invalid' : ''"
                        @input="validatePassword">
                    <div class="invalid-feedback">
                        {{ errors.password ? errors.password[0] : '' }}
                    </div>
                </div>

                <button type="submit" class="custom-modal__btn btn btn-primary btn-lg"
                >
                    Войти
                    <span v-show="isWaiting" class="spinner-grow sr-only" role="status"></span>
                </button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "Login",

    data: ()=>({
        isWaiting: false,
        errors: {},

        data: {
            password: '',
            email: '',
        }
    }),

    methods: {

        evtLogin() {
            this.$emit('loginForm', )
        },

        async login() {

            if(!this.validateData()) return

            //
            this.isWaiting = true;
            const form_data = new FormData()

            for(const key in this.data) {
                form_data.append(key, this.data[key])
            }

            //
            await axios.post('/login', form_data).then((res)=>{

                this.errors = {}
                window.location = res.data   //TODO redirect to home
            })
            .catch((err)=>{
                this.errors = err.response.data.errors
            })

            //
            this.isWaiting = false;
        },

        //
        //validation methods
        validateData()
        {
            //
            return this.validateEmail() && this.validatePassword()
        },

        //TODO repeated methods move to storage
        validateEmail() {
            //
            if(this.data.email.length < 3) {
                this.$set(this.errors, 'email', ['слишком короткий email'])
                return false
            }

            //
            this.errors.email = undefined
            return true;
        },

        validatePassword() {
            //
            if(this.data.password.length < 3) {
                this.$set(this.errors, 'password', ['слишком короткий пароль'])
                return false
            }

            //
            this.errors.password = undefined
            return true;
        },

    }
}
</script>

<style scoped>

</style>
