<template>
    <div class="container-fluid m-0 p-0 px-5 mt-4">
        <spinner v-if="loading"></spinner>
        <div class="container" v-else>
            <h2>Lietotāji</h2>
            <div class="row mb-3">
                <div class="col">
                    <button class="btn btn-success d-block ml-auto" @click="openNewModal">+ Pievienot jaunu</button>
                </div>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">#</th>
                        <th scope="col">Vārds</th>
                        <th scope="col">Uzvārds</th>
                        <th scope="col">E-pasts</th>
                        <th scope="col">Tips</th>
                        <th scope="col">Pēdējā ielogošanās</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(lietotajs, i) in lietotaji" :key="i">
                        <td scope="row" :class="{'bg-danger':lietotajs.tips == 'deactive','bg-success': lietotajs.tips != 'deactive'}"></td>
                        <td>{{i+1}}</td>
                        <td>{{ lietotajs.vards }}</td>
                        <td>{{ lietotajs.uzvards }}</td>
                        <td>{{ lietotajs.email }}</td>
                        <td>{{ lietotajs.tips == 'system_admin' ? "system admin" : lietotajs.tips }}</td>
                        <td>{{ lietotajs.last_login }}</td>
                        <td>
                            <button class="btn btn-outline-secondary" @click="editUserModal(lietotajs.id, i)"><span><i class="far fa-edit"></i></span></button>
                            <button class="btn btn-outline-danger" @click="deleteUserModal(lietotajs.id)"><span><i class="fas fa-trash-alt"></i></span></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Jauns lietotājs</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-2" v-if="errors.length">
                            <ul class="mb-0">
                                <li v-for="(error, index) in errors" :key="index">
                                    {{error}}
                                </li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="new-name" class="col-form-label">Vārds:</label>
                            <input type="text" class="form-control" id="new-name" v-model="vards">
                        </div>
                        <div class="mb-3">
                            <label for="new-surname" class="col-form-label">Uzvārds:</label>
                            <input type="text" class="form-control" id="new-surname" v-model="uzvards">
                        </div>
                        <div class="mb-3">
                            <label for="new-email" class="col-form-label">E-pasts:</label>
                            <input type="email" class="form-control" id="new-email" v-model="email">
                        </div>
                        <div class="mb-3">
                            <label for="new-password" class="col-form-label">Parole:</label>
                            <input type="password" class="form-control" id="new-password" v-model="password">
                        </div>
                        <div class="mb-3">
                            <label for="new-tips" class="col-form-label">Tips:</label>
                            <select class="form-control" id="new-tips" v-model="tips">
                                <option value='user'>user</option>
                                <option value='admin'>admin</option>
                                <option value='system_admin'>system admin</option>
                                <option value='deactive'>neaktīvs</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                        <button type="button" class="btn btn-success" @click="newUserCreate">Pievienot</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Rediģēt lietotāju</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-2" v-if="errors.length">
                            <ul class="mb-0">
                                <li v-for="(error, index) in errors" :key="index">
                                    {{error}}
                                </li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="edit-name" class="col-form-label">Vārds:</label>
                            <input type="text" class="form-control" id="edit-name" v-model="vards">
                        </div>
                        <div class="mb-3">
                            <label for="edit-surname" class="col-form-label">Uzvārds:</label>
                            <input type="text" class="form-control" id="edit-surname" v-model="uzvards">
                        </div>
                        <div class="mb-3">
                            <label for="edit-email" class="col-form-label">E-pasts:</label>
                            <input type="email" class="form-control" id="edit-email" v-model="email">
                        </div>
                        <div class="mb-3">
                            <label for="edit-password" class="col-form-label">Parole:</label>
                            <input type="password" class="form-control" id="edit-password" v-model="password">
                        </div>
                        <div class="mb-3">
                            <label for="edit-tips" class="col-form-label">Tips:</label>
                            <select class="form-control" id="edit-tips" v-model="tips">
                                <option value='user'>user</option>
                                <option value='admin'>admin</option>
                                <option value='system_admin'>system admin</option>
                                <option value='deactive'>neaktīvs</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                        <button type="button" class="btn btn-success" @click="editUserSubmit">Saglabāt</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Dzēst lietotāju</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>Vai tiešām vēlaties neatgriezeniski dzēst šo lietotāju?</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                        <button type="button" class="btn btn-danger" @click="deleteUser">Dzēst</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'home',
    props: ['app'],
    data(){
        return {
            loading: false,
            lietotaji: [],
            vards: '',
            uzvards: '',
            email: '',
            password: '',
            tips: 'user',
            errors: [],
            deleteId: 0,
            editId: 0,
            editIndex: 0,
        }
    },
    mounted() {
        this.init();
    },
    methods: {
        init() {
            if(this.app.user == null){
                this.$router.push('/login');
            }
            this.loading = true;
            this.app.req.get('lietotaji/getAll').then(response => {
                this.lietotaji = response.data.lietotaji;
                this.loading = false;
            });
        },
        openNewModal(){
            this.errors = [];
            this.vards = '';
            this.uzvards = '';
            this.email = '';
            this.password = '';
            this.tips = 'user';
            $("#newUserModal").modal('show');
        },
        newUserCreate(){
            this.errors = [];
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
            if(!this.errors.length){
                const data = {
                    vards: this.vards,
                    uzvards: this.uzvards,
                    email: this.email,
                    parole: this.password,
                    tips: this.tips
                };
                this.app.req.post('lietotaji/create', data).then(response => {
                    this.lietotaji = response.data.lietotaji;
                    $("#newUserModal").modal('hide');
                }).catch(error => {
                    this.errors.push(error.response.data.error);
                });
            }
        },
        deleteUser(){
            const data = {
                id: this.deleteId
            };
            this.app.req.post('lietotaji/delete', data).then(response => {
                this.lietotaji = response.data.lietotaji;
                $("#deleteUserModal").modal('hide');
            });
        },
        deleteUserModal(id){
            this.deleteId = id;
            $("#deleteUserModal").modal('show');
        },
        editUserModal(id, index){
            this.errors = [];
            this.editId = id;
            this.editIndex = index;
            this.vards = this.lietotaji[index].vards;
            this.uzvards = this.lietotaji[index].uzvards;
            this.email = this.lietotaji[index].email;
            this.password = '';
            this.tips = this.lietotaji[index].tips;
            $("#editUserModal").modal('show');
        },
        editUserSubmit(){
            this.errors = [];
            if(this.vards == ''){
                this.errors.push('Vārds jāieraksta');
            }
            if(this.uzvards == ''){
                this.errors.push('Uzvārds jāieraksta');
            }
            if(this.email == ''){
                this.errors.push('E-pasts jāieraksta');
            }
            if(!this.errors.length){
                const data = {
                    id: this.editId,
                    vards: this.vards,
                    uzvards: this.uzvards,
                    email: this.email,
                    parole: this.password,
                    tips: this.tips
                };
                this.app.req.post('lietotaji/update', data).then(response => {
                    this.lietotaji = response.data.lietotaji;
                    $("#editUserModal").modal('hide');
                }).catch(error => {
                    this.errors.push(error.response.data.error);
                });
            }
        }
    }
}
</script>
