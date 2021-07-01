<template>
    <div class="container">
        <NavComponent v-bind:role-user="currentUser.role" v-bind:user-name="currentUser.name"></NavComponent>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h5 class="text-center">User List</h5>
            </div>
        </div>
        <div class="container text-left p-3">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Add New User
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Dead Center Modal</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                               <div class="form-row">
                                    <div class="form-group">
                                        <label for="inputUserName">User Name</label>
                                        <input type="text" class="form-control" id="inputUserName" name="name" placeholder="User Name" v-model="formData.name">
                                        <p class="text-danger" v-text="errors.name"></p>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Email</label>
                                        <input type="email" class="form-control" id="inputEmail4" name="email" placeholder="Email" v-model="formData.email">
                                        <p class="text-danger" v-text="errors.email"></p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Password</label>
                                        <input type="password" class="form-control" id="inputPassword4" name="password" placeholder="Password" v-model="formData.password">
                                        <p class="text-danger" v-text="errors.password"></p>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Password Confirmation</label>
                                        <input type="password" class="form-control" id="inputPasswordConfirm" name="password_confirmation" placeholder="Password Confirm" v-model="formData.password_confirmation">
                                        <p class="text-danger" v-text="errors.password_confirmation"></p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                    <label for="role">Role</label>
                                    <select id="role" class="form-control" name="role" v-model="formData.role" @change="onChange($event)">
                                        <option value="admin">admin</option>
                                        <option value="sale">sale</option>
                                    </select>
                                        <p class="text-danger" v-text="errors.role"></p>
                                    </div>
                                </div>
                      </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <div v-if="buttonType">
                                <button type="button" class="btn btn-primary" @click="userUpdate" >Save changes</button>
                            </div>
                            <div v-else>
                                <button type="button" class="btn btn-primary" @click="registerUser" >Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container -->
        <br>
        <div>
            <table class="table" id="user-table">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <tr v-for="user in users" v-bind:key="user.id">
                        <td>{{user.id}}</td>
                        <td>{{user.name}}</td>
                        <td>{{user.email}}</td>
                        <td>{{user.role}}</td>
                        <td>{{customFormatDate(user.created_at)}}</td>
                        <td>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click="userEdit(user.id)"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-danger" @click="deleteUser(user.id)"><i class="far fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        <input type="hidden" v-model="userId">
        </div>
    </div>
</template>

<script>
    import NavComponent from "../layouts/NavComponent";
    import moment  from 'moment';
    export default {
        name: "UserComponent",
        components:{
            NavComponent
        },
        data() {
            return {
                users: {},
                currentUser: {},
                token: localStorage.getItem('token'),
                formData: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    role:'admin'
                },
                sort:'',
                userId:'',
                errors: {},
                buttonType:false
            }
        },
        methods: {
            userList(){
                axios.get('api/staff').then((response) =>{
                    this.users = response.data
                    // console.log(this.users);
                }).catch((error) =>{
                    console.log(error)
                })
            },
            customFormatDate(date) {
                return moment(date).format('YYYY-MM-DD')
            },
            registerUser(){
                this.buttonType = false
                axios.post('api/register', this.formData).then((response) => {
                    console.log(response.data)
                    this.formData.name = this.formData.email = this.formData.password = this.formData.password_confirmation = ''
                    this.formData.role = 'admin'
                    this.errors = {}
                    this.userList();
                    Swal.fire(
                        'Successfully Save!',
                        'You clicked the button!',
                        'success'
                    )
                    // this.$router.push('/login')
                    // this.$toaster.success('Account created successfully, now you can login!')
                }).catch((errors) => {
                    this.errors = errors.response.data.errors
                    console.log(errors.response.data.errors)
                });
            },
            userEdit(id) {
                this.buttonType = true
                axios.get(`api/staff/${id}`).then((response) => {
                    console.log(response.data)
                    const {id,name,email,role} = response.data
                    this.formData.name = name
                    this.formData.email = email
                    this.formData.password = this.formData.password_confirmation = ''
                    this.formData.role = role
                    this.userId = id
                    this.errors = {}
                }).catch((errors) => {
                    this.errors = errors.response.data.errors
                    console.log(errors.response.data.errors)
                });
            },
            userUpdate(){
                axios.post('api/staff/'+this.userId, this.formData).then((response) => {
                    console.log(response.data)
                    this.formData.name = this.formData.email = this.formData.password = this.formData.password_confirmation = ''
                    this.formData.role = 'sale'
                    this.errors = {}
                    this.buttonType = false
                    this.userList();
                    Swal.fire(
                        'Successfully Save!',
                        'You clicked the button!',
                        'success'
                    )
                    // this.$router.push('/login')
                    // this.$toaster.success('Account created successfully, now you can login!')
                }).catch((errors) => {
                    this.errors = errors.response.data.errors
                    console.log(errors.response.data.errors)
                });
            },
            deleteUser(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete('api/staff/'+id).then((response) => {
                            this.userList();
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        }).catch((errors) => {
                            console.log(errors.response.data.errors)
                        });
                    }
                })
            },
            onChange(event) {
                this.formData.role = event.target.value
            }

        },
        mounted() {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            this.userList();
            axios.get('api/user').then((response) => {
                this.currentUser = response.data
            }).catch((errors) => {
                console.log(errors)
            })
        }
    }
</script>

<style>
    .modal.show  {
        display:flex!important;
        flex-direction:column;
        justify-content:center;
        align-content:center;
        align-items: flex-start;
    }

</style>
