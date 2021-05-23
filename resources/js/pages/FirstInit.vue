<template>
    <div class="container mt-3" style="max-width:600px;margin-bottom:80px;">
        <h1 class="text-center" style="margin-top:50px;">Stundu uzskaites sistēmas pirmais lietotājs</h1>
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
                            <label for="firma-name">Firmas nosaukums:</label>
                            <input type="text" class="form-control" id="firma-name" v-model="firma">
                        </div>
                        <div class="form-group text-left">
                            <label for="new-name">Vārds:</label>
                            <input type="text" class="form-control" id="new-name" v-model="vards">
                        </div>
                        <div class="form-group text-left">
                            <label for="new_surname">Uzvārds:</label>
                            <input type="text" class="form-control" id="new-surname" v-model="uzvards">
                        </div>
                        <div class="form-group text-left">
                            <label for="new-email">E-pasts:</label>
                            <input type="email" class="form-control" id="new-email" v-model="email">
                        </div>
                        <div class="form-group text-left">
                            <label for="new-password">Parole:</label>
                            <input type="password" class="form-control" id="new-password" v-model="password">
                        </div>
                        <div class="form-group text-left">
                            <label for="again-password">Atkārtoti parole:</label>
                            <input type="password" class="form-control" id="again-password" v-model="passwordAgain">
                        </div>

                        <button class="btn btn-success btn-login mt-2" type="submit">Izveidot</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'firstInit',
    props: ['app'],
    data(){
        return {
            errors: [],
            vards: '',
            uzvards: '',
            email: '',
            password: '',
            passwordAgain: '',
            firma: ''
        }
    },
    methods: {
        onSubmit(){
            this.errors = [];
            if(this.firma == ''){
                this.errors.push('Firmas nosaukums jāievada');
            }
            if(this.vards == ''){
                this.errors.push('Vārds jāieraksta');
            }
            if(this.uzvards == ''){
                this.errors.push('Uzvārds jāieraksta');
            }
            if(this.email == ''){
                this.errors.push('E-pasts jāieraksta');
            }
            if(this.password == ''){
                this.errors.push('Parole jāieraksta');
            }
            if(this.passwordAgain == ''){
                this.errors.push('Atkārtotā parole jāieraksta');
            }
            if(this.passwordAgain != this.password){
                this.errors.push('Paroles nesakrīt');
            }
            if(!this.errors.length){
                const data = {
                    vards: this.vards,
                    uzvards: this.uzvards,
                    email: this.email,
                    parole: this.password,
                    firma: this.firma
                };
                this.app.req.post('settings/firstuser', data).then(response => {
                    this.app.firma = this.firma;
                    this.$router.push('/login');
                }).catch(error => {
                    this.errors.push(error.response.data.error);
                });
            }
        }
    }
}
</script>
