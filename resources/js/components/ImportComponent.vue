<template>
    <div class="container">
        <NavComponent v-bind:role-user="currentUser.role" v-bind:user-name="currentUser.name"></NavComponent>
        <div class="row justify-content-center">
            <div class="col-md-10">

            </div>
        </div>
        <br>
        <div>
            <!--BLOCK FORM SALE-->
            <div class="card-body">
                <div v-if="success != ''" class="alert alert-success">
                    {{success}}
                </div>
                <form @submit="formSubmit" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" id="name" v-model="form.name" />
                        <p class="text-danger" v-text="errors.name"></p>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" id="category_id" v-model="form.category_id" @change="cateChange($event)" >
                            <option v-for="cate in categories" v-bind:key="cate.id" :value="cate.id"> {{cate.name}} </option>
                        </select>
                        <p class="text-danger" v-text="errors.category_id"></p>
                    </div>
                    <div class="form-group">
                        <label for="qty">Qty</label>
                        <input type="text" class="form-control" id="qty" v-model="form.qty" />
                        <p class="text-danger" v-text="errors.qty"></p>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" class="form-control" id="price" v-model="form.price" />
                        <p class="text-danger" v-text="errors.price"></p>
                    </div>
                    <div class="form-group">
                        <label for="expired_date">Expired Date</label>
                        <input type="date" class="form-control" id="expired_date"  v-model="form.expired_date"  @change="customeFormatDate" />
                        <p class="text-danger" v-text="errors.expired_date"></p>
                    </div>
                    <div class="form-group">
                        <label for="file">Picture</label>
                        <input type="file" class="form-control" id="file" v-on:change="onChange">
                    </div>
                    <br/>
                    <button class="btn btn-primary btn-block">Save</button>
                </form>
            </div>
            <!--END BLOCK FORM SALE-->
        </div>
    </div>
</template>
<style>

</style>
<script>
    import NavComponent from "../layouts/NavComponent";
    import moment from "moment"
    export default {
        components:{
            NavComponent
        },
        data() {
            return {
                form: {
                    category_id: '',
                    name: '',
                    qty: '',
                    price: '',
                    image: '',
                    expired_date: ''
                },
                currentUser: {},
                token: localStorage.getItem('token'),
                name: '',
                file: '',
                success: '',
                errors: {},
                categories: {}
            }
        },
        methods: {
            onChange(e) {
                this.file = e.target.files[0];
            },
            formSubmit(e) {
                e.preventDefault();
                let existingObj = this;

                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                let data = new FormData();
                data.append('file', this.file);
                data.append('name',this.form.name);
                data.append('category_id',this.form.category_id);
                data.append('qty',this.form.qty);
                data.append('price',this.form.price);
                data.append('expired_date',this.form.expired_date);
                data.append('image', 'originalName');

                axios.post('/api/products', data, config)
                    .then(function (res) {
                        existingObj.success = res.data.success;
                    })
                    .catch(function (err) {
                        existingObj.output = err;
                    });
            },
            cateChange(event){
                this.form.category_id = event.target.value
            },
            categoriesList(){
                axios.get('api/categories').then((response) => {
                    this.categories = response.data
                }).catch((errors) => {
                    console.log(errors)
                })
            },
            customeFormatDate(){
                if(this.form.expired_date != ''){
                    var tod = new Date(this.form.expired_date);
                    this.form.expired_date = moment(tod).format('YYYY-MM-DD');
                }
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
