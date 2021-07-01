<template>
    <div class="container">
        <NavComponent v-bind:role-user="currentUser.role" v-bind:user-name="currentUser.name"></NavComponent>
        <div class="row justify-content-center">
            <div class="col-md-10">

            </div>
        </div>
        <br>
        <div>
            <div class="container text-left p-3">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" @click="checkshowButton">
                    Add New Category
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
                            <div class="modal-body ">
                                <form>
                                    <div class="form-row ">
                                        <div class="form-group">
                                            <label for="inputUserName">Category Name</label>
                                            <input type="text" class="form-control" id="inputUserName" name="name" placeholder="Category Name" v-model="formData.name">
                                            <p class="text-danger" v-text="errors.name"></p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <div v-if="buttonType">
                                    <button type="button" class="btn btn-primary" @click="updateCate" >Save changes</button>
                                </div>
                                <div v-else>
                                    <button type="button" class="btn btn-primary" @click="insertCate" >Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container -->
            <!--BLOCK FORM SALE-->
            <div>
                <table class="table" id="user-table">
                    <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                    <tr v-for="category in categories" v-bind:key="category.id">
                        <td>{{category.id}}</td>
                        <td>{{category.name}}</td>
                        <td>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click="editCate(category.id)"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-danger" @click="deleteCate(category.id)"><i class="far fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <input type="hidden" v-model="id" />
            <!--END BLOCK FORM SALE-->
        </div>
    </div>
</template>

<script>
    import NavComponent from "../layouts/NavComponent";
    export default {
        components:{
            NavComponent
        },
        data() {
            return {
                categories: {},
                currentUser: {},
                formData:{
                    name:''
                },
                id:'',
                buttonType:false,
                token: localStorage.getItem('token'),
                errors:{}
            }
        },
        methods: {
            categoriesList(){
                axios.get('api/categories').then((response) => {
                   this.categories = response.data
                }).catch((errors) => {
                    console.log(errors)
                })
            },
            insertCate(){
                axios.post(`api/categories`,this.formData).then((response) => {
                    this.categoriesList();
                    Swal.fire(
                        'Successfully Save!',
                        'You clicked the button!',
                        'success'
                    )
                }).catch((errors) => {
                    console.log(errors)
                })
            },
            editCate(id){
                this.buttonType = true
                axios.get(`api/categories/${id}`).then((response) => {
                    console.log(response.data)
                    const {id,name} = response.data
                    this.formData.name = name
                    this.id = id
                }).catch((errors) => {
                    console.log(errors)
                })
            },
            updateCate(){
                axios.put(`api/categories/${this.id}`, this.formData).then((response) => {
                    this.categoriesList();
                    Swal.fire(
                        'Successfully Save!',
                        'You clicked the button!',
                        'success'
                    )
                }).catch((errors) => {
                    console.log(errors)
                })
            },
            deleteCate(id){
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
                        axios.delete(`api/categories/${id}`).then((response) => {
                            this.categoriesList();
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        }).catch((errors) => {
                            console.log(errors)
                        })
                    }
                })
            },
            checkshowButton(){
                this.buttonType = false;
            }

        },
        mounted() {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            this.categoriesList();
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
