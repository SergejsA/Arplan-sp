<template>
    <div class="container mt-3" style="max-width:600px;margin-bottom:80px;">
        <h1 class="text-center" style="margin-top:50px;">Ienākt</h1>
        <div class="card login-card">
            <div class="card-body">
                <div class="col text-center">
                    <form v-on:submit.prevent="onSubmit">
                        <div class="alert alert-danger" v-if="errors.length">
                            <ul class="mb-0">
                                <li v-for="(error, index) in errors" :key="index">
                                    {{error}}
                                </li>
                            </ul>
                        </div>

                        <div class="form-group text-left">
                            <label for="">E-pasts:</label>
                            <input type="text" class="form-control" placeholder="E-pasts" v-model="email">
                        </div>
                        <div class="form-group text-left">
                            <label for="">Parole:</label>
                            <input type="password" class="form-control" placeholder="Parole" v-model="password">
                        </div>

                        <button class="btn btn-success btn-login mt-2" type="submit">Ienākt</button>
                        <div class="mt-2"><router-link to="/reset-password" class="btn btn-outline-primary mt-2">Aizmirsu paroli</router-link></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:'login',
    props: ['app'],
    data() {
        return{
            email: '',
            password: '',
            errors: []
        }
    },
    methods: {
        onSubmit() {
            this.errors = [];
            if(!this.password){
                this.errors.push("Jāievada parole!");
            }
            if(!this.email){
                this.errors.push("Jāievada e-pasts!");
            }
            if(!this.errors.length){
                const data = {
                    email: this.email,
                    password: this.password
                }
                this.app.req.post('auth/login', data).then(response => {
                    this.app.user = response.data;
                    this.$router.push('/');
                }).catch(error => {
                    this.errors.push("Kļūda ielogojoties");
                });
            }
        }
    }
}
</script>
