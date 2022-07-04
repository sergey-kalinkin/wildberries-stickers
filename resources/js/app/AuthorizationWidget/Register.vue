<template>
    <div class="modal-dialog modal-dialog-centered">
        <div class="custom-modal__content">
            <button type="button" class="custom-modal__close" data-bs-dismiss="modal">
                <svg><use xlink:href="#svg-close"></use></svg>
            </button>

            <p class="h2">Создайте новый аккаунт</p>

            <form @submit.prevent="register">
                <div class="custom-modal__text">
                    <p><a @click.prevent="evtRegister" href="#">уже есть аккаунт?</a></p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Имя</label>
                    <input v-model="data.name"
                           type="text"
                           placeholder="ivan"
                           class="form-control"
                           :class="errors.name ? 'is-invalid' : ''"
                           @input="validateName">
                    <div class="invalid-feedback">
                        {{ errors.name ? errors.name[0] : '' }}
                    </div>
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

                <div class="mb-3">
                    <label class="form-label">Подтвердите пароль</label>
                    <input v-model="data.password_confirmation" type="password" class="form-control" :class="['', 'is-valid', 'is-invalid'][validateRepeatPassword()]">
                    <div class="invalid-feedback">
                        Пароли не совпадают
                    </div>
                </div>

                <button type="submit" class="custom-modal__btn btn btn-primary btn-lg"
                >
                    Зарегистрироваться
                    <span v-show="isWaiting" class="spinner-grow sr-only" role="status"></span>
                </button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "Register",

    data: ()=>({
        isWaiting: false,
        errors: {},

        data: {
            password: '',
            email: '',
            name: '',
            password_confirmation: '',
        }
    }),

    methods: {

        evtRegister() {
            this.$emit('registerForm', )
        },

        async register() {

            if(!this.validateData()) return

            //
            this.isWaiting = true;
            const form_data = new FormData()

            for(const key in this.data) {
                form_data.append(key, this.data[key])
            }

            //
            await axios.post('/register', form_data).then((res)=>{

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
            return this.validateName() && this.validateEmail() && this.validatePassword() && this.validateRepeatPassword() === 1
        },

        validateName() {
            //
            if(this.data.name.length < 2) {
                this.$set(this.errors, 'name', ['слишком короткое имя'])
                return false
            }

            //
            this.errors.name = undefined
            return true;
        },

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

        validateRepeatPassword() {
            //
            if(!this.data.password.length || !this.data.password_confirmation.length)
                return 0

            //
            return this.data.password === this.data.password_confirmation ? 1 : 2;
        }
    }
}
</script>

<style scoped>

</style>
