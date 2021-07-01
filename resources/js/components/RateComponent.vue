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
                                            <label for="inputUserName">USD IN</label>
                                            <input type="text" class="form-control" id="inputUserName" name="name" placeholder="Category Name" v-model="formData.usd_in">
                                            <p class="text-danger" v-text="errors.usd_in"></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputOut">USD OUT</label>
                                            <input type="text" class="form-control" id="inputOut" name="name" placeholder="Category Name" v-model="formData.usd_out">
                                            <p class="text-danger" v-text="errors.usd_out"></p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <div v-if="buttonType">
                                    <button type="button" class="btn btn-primary" @click="updateRate" >Save changes</button>
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
                    <th>USD IN</th>
                    <th>USD OUT</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                    <tr v-for="rate in rates" v-bind:key="rate.id">
                        <td>{{rate.usd_in}}</td>
                        <td>{{rate.usd_out}}</td>
                        <td>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click="editRate(rate.id)">
                                <i class="fas fa-edit"></i>
                            </button>
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
                rates: {},
                currentUser: {},
                token: localStorage.getItem('token'),
                buttonType: true,
                formData: {
                    usd_in:'',
                    usd_out:''
                },
                id:'',
                errors:{}
            }
        },
        methods: {
            rateList(){
                axios.get('api/rates').then((response) => {
                    this.rates = response.data
                }).catch((errors) => {
                    console.log(errors)
                })
            },
            editRate(id){
                this.buttonType = true
                axios.get(`api/rates/${id}`).then((response) => {
                    console.log(response.data)
                    const {id,usd_in,usd_out} = response.data
                    this.formData.usd_in = usd_in
                    this.formData.usd_out = usd_out
                    this.id = id
                }).catch((errors) => {
                    console.log(errors)
                })
            },
            updateRate(){
                axios.put(`api/rates/${this.id}`, this.formData).then((response) => {
                    Swal.fire(
                        'Successfully Save!',
                        'You clicked the button!',
                        'success'
                    )
                    this.rateList();
                }).catch((errors) => {
                    console.log(errors)
                })
            },
        },
        mounted() {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            this.rateList();
            axios.get('api/user').then((response) => {
                this.currentUser = response.data
            }).catch((errors) => {
                console.log(errors)
            })
        }
    }
</script>
