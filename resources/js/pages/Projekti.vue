<template>
    <div class="container-fluid m-0 p-0 px-5 mt-4">
        <spinner v-if="loading"></spinner>
        <div class="container" v-else>
            <div class="row">
                <div class="col-6">
                    <h2 class="mb-2">Projekta darbi</h2>
                    <div class="row" v-for="(job, i) in defaultJobs" :key="i">
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    {{ job.name }}
                                </div>
                                <div class="col">
                                    <button class="btn btn-sm mx-2 btn-outline-danger" @click="deleteDefaultJob(i)"><span><i class="fas fa-trash-alt"></i></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <div class="row">
                                <div class="col-8"><input type="text" class="form-control" v-model="newDefault"></div>
                                <div class="col-4"><button class="btn btn-success" @click="newDefaultSubmit">Pievienot</button></div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <h2 class="mb-2">Vispārīgie darbi</h2>
                    <div class="row" v-for="(job, i) in parastiJobs" :key="i">
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    {{ job.name }}
                                </div>
                                <div class="col">
                                    <button class="btn btn-sm mx-2 btn-outline-danger" @click="deleteParastiJob(i)"><span><i class="fas fa-trash-alt"></i></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <div class="row">
                                <div class="col-8">
                                    <input type="text" class="form-control" v-model="newParasti">
                                </div>
                                <div class="col-4">
                                    <button class="btn btn-success" @click="newParastiSubmit">Pievienot</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h2 style="margin-top:50px;">Projekti</h2>
            <div class="row mb-3">
                <div class="col">
                    <button class="btn btn-success d-block ml-auto" @click="openNewModal">+ Pievienot jaunu</button>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col d-flex align-items-center justify-content-end">
                    <label class="mr-4 col-form-label">Meklēt:</label><input type="text" v-model="filter" class="form-control" style="max-width:200px;">
                </div>
            </div>

            <table class="table table-striped table-hover" v-if="render">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">#</th>
                        <th scope="col">Nosaukums</th>
                        <th scope="col">Status</th>
                        <th scope="col">Pievienoja</th>
                        <th scope="col">Darbi</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <span class="d-none">{{projektiC}}</span>
                    <tr v-for="(projekts, i) in projekti" :key="i" :style="{'display':!projekts.nosaukums.includes(filter) ? 'none' : 'auto'}">
                        <td scope="row" :class="{'bg-danger':projekts.stat == 'closed','bg-success': projekts.stat == 'active'}"></td>
                        <td>{{i+1}}</td>
                        <td>{{ projekts.nosaukums }}</td>
                        <td>{{ projekts.stat }}</td>
                        <td>{{ projekts.user.vards + ' ' + projekts.user.uzvards }}</td>
                        <td>{{ projekts.darbi }}</td>
                        <td>
                            <button class="btn btn-outline-secondary" @click="editProjectModal(projekts.id, i)"><span><i class="far fa-edit"></i></span></button>
                            <button class="btn btn-outline-danger" @click="deleteProjectModal(projekts.id, i)"><span><i class="fas fa-trash-alt"></i></span></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="newProjectModal" tabindex="-1" aria-labelledby="newProjectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Jauns Projekts</h5>
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
                            <label for="new-nosaukums" class="col-form-label">Nosaukums:</label>
                            <input type="text" class="form-control" id="new-nosaukums" v-model="nosaukums">
                        </div>
                        <div class="mb-3">
                            <label for="new-darbi" class="col-form-label">Darbi:</label>
                            <textarea class="form-control" id="new-darbi" v-model="darbi" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                        <button type="button" class="btn btn-success" @click="newProjectCreate">Pievienot</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Rediģēt projektu</h5>
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
                            <label for="edit-nosaukums" class="col-form-label">Nosaukums:</label>
                            <input type="text" class="form-control" id="edit-nosaukums" v-model="nosaukums">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Izveidoja: <b>{{ projekti.length ? projekti[editIndex].user.vards + ' ' + projekti[editIndex].user.uzvards : '' }}</b></label>
                        </div>
                        <div class="mb-3">
                            <label for="edit-stat" class="col-form-label">Statuss:</label>
                            <select class="form-control" id="edit-stat" v-model="status">
                                <option value='active'>aktīvs</option>
                                <option value='closed'>noslēgts</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-darbi" class="col-form-label">Darbi:</label>
                            <textarea class="form-control" id="edit-darbi" v-model="darbi" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                        <button type="button" class="btn btn-success" @click="editProjectSubmit">Saglabāt</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Dzēst Projektu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>Vai tiešām vēlaties neatgriezeniski dzēst šo projektu?</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                        <button type="button" class="btn btn-danger" @click="deleteProject">Dzēst</button>
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
            projekti: [],
            nosaukums: '',
            status: '',
            darbi: '',
            errors: [],
            deleteId: 0,
            deleteIndex: 0,
            editId: 0,
            editIndex: 0,
            defaultJobs: [],
            parastiJobs: [],
            newDefault: '',
            newParasti: '',
            filter: 'Pirmais',
            render: true
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
            this.app.req.get('projekti/getAll').then(response => {
                this.projekti = response.data.projekti;
                this.defaultJobs = response.data.default;
                this.parastiJobs = response.data.parasti;
                console.log(this.projekti);
                this.loading = false;
            });
        },
        openNewModal(){
            this.errors = [];
            this.nosaukums = '';
            this.status = '';
            this.darbi = this.defaultJobs.map(function(job){
                return job.name;
            }).join("\n");
            $("#newProjectModal").modal('show');
        },
        newProjectCreate(){
            this.errors = [];
            if(this.nosaukums == ''){
                this.errors.push('Nosaukums jāieraksta');
            }
            if(this.darbi == ''){
                this.errors.push('Darbi jāieraksta');
            }
            if(!this.errors.length){
                const data = {
                    nosaukums: this.nosaukums,
                    darbi: this.darbi
                };
                this.app.req.post('projekti/create', data).then(response => {
                    this.projekti = response.data.projekti;
                    console.log(this.projekti);
                    $("#newProjectModal").modal('hide');
                }).catch(error => {
                    this.errors.push(error.response.data.error);
                });
            }
        },
        deleteProject(){
            const data = {
                id: this.deleteId
            };
            this.app.req.post('projekti/delete', data).then(response => {
                this.projekti = response.data.projekti;
                this.projekti.splice(this.deleteIndex, 1);
                $("#deleteProjectModal").modal('hide');
            });
        },
        deleteProjectModal(id, index){
            this.deleteId = id;
            this.deleteIndex = index;
            $("#deleteProjectModal").modal('show');
        },
        editProjectModal(id, index){
            this.errors = [];
            this.editId = id;
            this.editIndex = index;
            this.nosaukums = this.projekti[index].nosaukums;
            this.status = this.projekti[index].stat;
            this.darbi = this.projekti[index].darbi.split(';').join("\n");
            $("#editProjectModal").modal('show');
        },
        editProjectSubmit(){
            this.errors = [];
            if(this.nosakums == ''){
                this.errors.push('Nosaukums jāieraksta');
            }
            if(this.darbi == ''){
                this.errors.push('Darbi jāieraksta');
            }
            if(!this.errors.length){
                const data = {
                    id: this.editId,
                    nosaukums: this.nosaukums,
                    darbi: this.darbi,
                    status: this.status,
                };
                this.app.req.post('projekti/update', data).then(response => {
                    this.projekti = response.data.projekti;
                    $("#editProjectModal").modal('hide');
                }).catch(error => {
                    this.errors.push(error.response.data.error);
                });
            }
        },
        newDefaultSubmit(){
            if(this.newDefault != ''){
                const data = {
                    name: this.newDefault
                };
                this.app.req.post('projekti/newdefault', data).then(response => {
                    this.newDefault = '';
                    this.defaultJobs = response.data.default;
                });
            }
        },
        newParastiSubmit(){
            if(this.newParasti != ''){
                const data = {
                    name: this.newParasti
                };
                this.app.req.post('projekti/newparasti', data).then(response => {
                    this.newParasti = '';
                    this.parastiJobs = response.data.parasti;
                });
            }
        },
        deleteParastiJob(index){
            const data = {
                id: this.parastiJobs[index].id
            };
            this.app.req.post('projekti/deleteparasti', data).then(response => {
                this.parastiJobs.splice(index, 1);
            });
        },
        deleteDefaultJob(index){
            const data = {
                id: this.defaultJobs[index].id
            };
            this.app.req.post('projekti/deletedefault', data).then(response => {
                this.defaultJobs.splice(index, 1);
            });
        },
    },
    computed: {
        projektiC(){
            this.filter;
            this.render = false;
            this.$nextTick(()=>{
                this.render = true;
            })
            return this.projekti;
        }
    }
}
</script>
