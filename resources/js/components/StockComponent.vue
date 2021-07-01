<template>
    <div class="container">
        <NavComponent v-bind:role-user="currentUser.role" v-bind:user-name="currentUser.name"></NavComponent>
        <div class="row justify-content-center">
        <div class="container">
            <!--BLOCK FORM SALE-->
            <table class="table table-responsive-xl">
                <thead>
                    <th>#</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id">
                        <td>{{product.id}}</td>
                        <td>
                            <img :src="'http://localhost:8000/storage/uploads/'+product.image"
                                        class="card-img-top" :alt="product.name" :width="200" :height="200" />
                        </td>
                        <td>{{product.name}}</td>
                        <td>{{product.qty}}</td>
                        <td>{{product.price}}</td>
                        <td>{{setStatus(product.qty)}}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <!--END BLOCK FORM SALE-->
        </div>
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
                employees: {},
                currentUser: {},
                token: localStorage.getItem('token'),
                products: {}
            }
        },
        methods: {
            getAllProducts(){
                axios.get('api/products').then((response) => {
                    this.products = response.data
                }).catch((errors) => {
                    console.log(errors)
                })
            },
            setStatus(qty){
                return qty <= 0 ? 'stock out' : 'in stock';
            }
        },
        mounted() {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            this.getAllProducts();
            axios.get('api/user').then((response) => {
                this.currentUser = response.data
            }).catch((errors) => {
                console.log(errors)
            })
        }
    }
</script>
