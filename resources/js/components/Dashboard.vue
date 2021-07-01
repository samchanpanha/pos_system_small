<template>
    <div class="container">
         <NavComponent v-bind:role-user="currentUser.role" v-bind:user-name="currentUser.name"></NavComponent>

        <div>
            <div class="bg-light text-right mr-3">
                <span>Time  : </span> <digital-clock :blink="true" :displaySeconds="true" />
            </div>
            <div>
                <multiselect v-model="value"
                             :options="options"
                             :multiple="true"
                             :close-on-select="false"
                             :clear-on-select="false"
                             :preserve-search="true"
                             placeholder="Pick some"
                             label="name"
                             track-by="name"
                             :preselect-first="true"
                             @input="onChange" @close="onTouch" @select="onSelect"
                             >
                    <template slot="selection"
                              slot-scope="{ values, search, isOpen }">
                        <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span>
                    </template>
                </multiselect>
            </div>

            <!--BLOCK FORM SALE-->
            <div class="container">
                <div class="row">
                    <div class="col-7 bg-primary">

                        <div class="row no-gutters">
                            <div class="col-sm-3 " v-for="product in products " @click="addProductToCart(product)" v-bind:key="product.id">
                                <div class="card m-2 mh-100" >
                                    <img :src="'http://localhost:8000/storage/uploads/'+product.image"
                                        class="card-img-top" alt="coca"
                                    />
                                    <div class="card-body">
                                        <h5 class="card-title"> {{product.name}} </h5>
                                        <p class="card-text">{{product.price}} $</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-5 ">
                        <table class="table table-dark text-center">
                            <thead>
                                <th>NAME</th>
                                <th>QTY</th>
                                <th>PRICE</th>
                                <th>TOTAL</th>
                                <th></th>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in cart" >
                                    <td><span>{{product.name}}</span></td>
                                    <td>
                                        <button type="button" class="btn btn-sm-danger bg-danger text-white" @click="onChangeQty(product,'minus')">-</button>
                                        <span>{{qty[index].newQty}}</span>
                                        <button type="button" class="btn btn-sm-success bg-success text-white " @click="onChangeQty(product,'plus')">+</button>
                                    </td>
                                    <td>{{product.price}} $</td>
                                    <td>{{qty[index].amount}} $</td>
                                    <td><button type="button" @click="deleteCart(index)" class='btn btn-danger btn-sm'><i class="fas fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Total</td>
                                    <td colspan="4">{{totalPrice}} $</td>
                                </tr>
                                <tr>
                                    <td colspan="7">
                                        <button type="button"
                                                class="btn btn-lg-primary bg-primary text-white w-100 m-1"
                                                data-toggle="modal" data-target="#trasactionModal" @click="clickToggle">Pay</button>
                                        <button type="button" class="btn btn-lg-danger bg-danger text-white w-100 m-1" @click="cancelTrasaction" >Cancel</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!--END BLOCK FORM SALE-->
            <!--  MODAL -->
            <div class="modal fade bd-example-modal-lg" id="trasactionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Payment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" @submit.prevent="processTransaction()">
                            <div class="modal-body">
                                <div class="form-group" >
                                   <span>Dollar In : <span v-text="usdIn" ></span> ,  Dollar Out <span v-text="usdOut" ></span></span>
                                </div>
                                <div class="form-group">
                                    <label for="">Total USD</label>
                                    <input type="text" class="form-control w-100" disabled v-model="totalPrice"  />
                                </div>
                                <div class="form-group">
                                    <label for="">Total KH</label>
                                    <input type="text" class="form-control w-100" disabled v-model="totalPriceRiel"  />
                                </div>
                                <div class="form-group">
                                    <label for="">Receive USD</label>
                                    <input type="text" class="form-control w-100" v-model="getUSD" @keyup="calculatePayment" />
                                </div>
                                <div class="form-group">
                                    <label for="">Receive KH</label>
                                    <input type="text" class="form-control w-100" v-model="getKH" @keyup="calculatePayment" />
                                </div>
                                <div class="form-group">
                                    <label for="">Change USD</label>
                                    <input type="text" class="form-control w-100" disabled v-model="changeUSD" value="0" />
                                </div>

                                <div class="form-group">
                                    <label for="">Change KH</label>
                                    <input type="text" class="form-control w-100" disabled  v-model="changeKH" value="0" />
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" @click="processTransaction" >Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
<!--            end modal-->


            <div id="printThis">
                                <div class="page-container">
                                    <main class="main-content">
                                        <div id="invoice-app">
                                            <div class="header">
                                                <div>
                                                    <h6>Invoice No 000001 </h6>
                                                    <p>Date:  01-02-2021</p>
                                                </div>
                                                <div>
                                                    <div class="section-spacer">
                                                        <h4 class="company-name">OneTV Cambodia</h4>
                                                        <textarea> រាជធានី, Preah Sisowath Quay, Phnom Penh 12000</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <table class="responsive-table">
                                                <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Item</th>
                                                    <th>Price/unite</th>
                                                    <th>Quantity</th>
                                                    <th>Total</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tr v-for="(product, index) in cart" >
                                                    <td data-label="No">{{product.id}}</td>
                                                    <td data-label="Item"><span>{{product.name}}</span></td>
                                                    <td data-label="Price/unite"><div class="cell-with-input">{{product.price}} $</div></td>
                                                    <td data-label="Quantity"><span>{{qty[index].newQty}}</span></td>
                                                    <td data-label="Total">{{qty[index].amount}} $</td>
                                                    <td class="text-right"></td>
                                                </tr>
                                            </table>

                                        <div class="float-md-right">
                                            <table >
                                                <tr>
                                                    <td>Subtotal</td>
                                                    <td>{{totalPrice}} $</td>
                                                </tr>
                                                <tr>
                                                    <td><div class="cell-with-input">Discount %</div></td>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <td><div class="cell-with-input">Tax %</div></td>
                                                    <td>0</td>
                                                </tr>
                                                <tr class="text-bold">
                                                    <td>Grand Total</td>
                                                    <td>{{totalPrice}} $</td>
                                                </tr>
                                            </table>
                                        </div>

                                        </div>

                                    </main>
                                </div>
                            </div>

                        </div>
                        <!-- Modal Content: ends -->

                    </div>

</template>

<script>
    import NavComponent from "../layouts/NavComponent";
    import DigitalClock from "vue-digital-clock";
    import Multiselect from 'vue-multiselect';
    import InvoiceComponent from "./InvoiceComponent";

    export default {
        components:{
            NavComponent,
            DigitalClock,
            Multiselect,
            InvoiceComponent
        },
        data() {
            return {
                employees: {},
                currentUser: {},
                token: localStorage.getItem('token'),
                value: [],
                options: [],
                categories: [],
                products: {},
                cart: [],
                totalPrice: 0,
                totalPriceRiel: 0,
                error: false,
                qty: [],
                stockIn: 0,
                successMsg: '',
                usdIn: 0,
                usdOut: 0,
                getUSD: 0,
                getKH: 0,
                changeUSD: 0,
                changeKH: 0,
                i: 0

            }
        },
        methods: {
            categoriesList(){
                axios.get('api/categories').then((response) => {
                    const data = response.data
                    data.forEach((x) => {
                        const {id, name} = x
                        this.options.push({
                            name: name,
                            id: id
                        })
                    });
                }).catch((errors) => {
                    console.log(errors)
                })
            },
            onChange (value) {
                this.value = value
                // if (value.indexOf('Reset me!') !== -1) this.value = []
                if(this.value.length > 0){
                    console.log(this.value)
                    axios.post('api/products/show-products',this.value)
                    .then((response) => {
                        this.products = response.data
                    }).catch((error) =>{
                        console.log(error)
                    });
                } else {
                    this.getAllProducts();
                    console.log('sorry')
                }
            },
            onSelect (option) {
                //if (option === 'Disable me!') this.isDisabled = true
            },
            onTouch () {
                //this.isTouched = true
            },
            getAllProducts(){
                axios.get('api/products').then((response) => {
                    this.products = response.data
                }).catch((errors) => {
                    console.log(errors)
                })
            },
            addProductToCart(array){
                let product_id = array.id;

                let obj = this.cart.find(o => o.id === array.id);
                if(obj !== undefined) {
                    this.onChangeQty(array, 'plus');
                }else{
                    this.qty.push({id: array.id, newQty:1, amount: array.price});
                    this.cart.push(array);
                    this.totalAmount();
                }
            },
               onChangeQty(action, type){
                let updateQty =  this.qty.find(x => x.id === action.id);
                let response =   this.checkStock(action.id)
                response.then((res) => {
                    const inStock = res.data[0]
                    if(type == "plus"){
                        updateQty.newQty =  updateQty.newQty + 1;
                        if(updateQty.newQty > inStock.qty){
                            updateQty.newQty =  updateQty.newQty - 1;
                            Swal.fire(
                                `this product no in stock now!!`,
                                '',
                                'warning'
                            )
                        }
                        updateQty.amount =  updateQty.newQty * action.price;
                    } else {

                        if(updateQty.newQty === 1){
                            updateQty.newQty = 1;
                        } else {
                            updateQty.newQty =  updateQty.newQty - 1;
                        }
                        updateQty.amount =  updateQty.newQty * action.price;
                    }
                    this.totalAmount()
                }).catch(error => console.log(error));
            },
            arrayRemove(array, key, value) {
                /*  how to use
                const newData = remove(data, "id", "88");
                const newData2 = remove(data, "name", "You are awesome!"); */
                const index = array.findIndex(obj => obj[key] === value);
                return index >= 0 ? [
                    ...array.slice(0, index),
                    ...array.slice(index + 1)
                ] : array;
            },
            totalAmount(){
               let total = 0;
               if(this.qty.length > 0){
                   this.qty.forEach((x) => {
                      total += Number(x.amount);
                   });
               }
               this.totalPrice = Number(total).toFixed(2);
            },
            deleteCart(index){
                this.cart.splice(index,1)
                this.qty.splice(index, 1)
                this.totalAmount();
            },
            cancelTrasaction(){
                this.cart = [];
                this.qty = [];
                this.totalPrice = 0;
            },
            processTransaction(){
                let getReceiveTotalUSD = this.getUSD;
                let getReceiveTotalKH = (this.getKH > 0 ) ? Number(Number(this.getKH) / Number(this.usdOut)).toFixed(2) : 0;
                let checkSuccessPay = ( Number(Number(getReceiveTotalUSD) + Number(getReceiveTotalKH) ).toFixed(2) === this.totalPrice ) ? true : false;

                if(checkSuccessPay){
                    // alert('Your Transaction pressing');
                    this.insertInvoice();
                } else {
                    alert('your pay not full total ');
                }

            },
            insertInvoice(){
                let invoice = {
                    total: this.totalPrice,
                    receive_usd: this.getUSD,
                    receive_riel: this.getKH,
                    total_profit: this.totalPriceRiel,
                    user_id: this.currentUser.id,
                    productDetails: this.cart,
                    detailsQty: this.qty
                };
                var config = {
                    method: 'post',
                    url: 'api/invoices',
                    headers: {
                        'Accept': 'application/json'
                    },
                    data : invoice
                };
                axios(config)
                    .then((response) => {
                        Swal.fire(
                            `${response.data.massage}`,
                            'You clicked the button!',
                            'success'
                        ).then(()=>{
                            this.cancelTrasaction()
                        })
                    }).then(()=>{
                        this.printInvoice();
                    })
                    .catch( (error) =>{
                        console.log(error);
                    });
            },
            getRates(){
                axios.get('api/rates')
                    .then((response) =>{
                        response.data.forEach((x) => {
                            this.usdIn = x.usd_in;
                            this.usdOut = x.usd_out;
                        });
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            },
            calculatePayment(){
                let chUSD = this.getUSD - this.totalPrice;

                if (chUSD > 0) {

                    this.getKH = 0;
                    this.changeUSD = Number(chUSD).toFixed(2);
                    this.changeKH = Number(chUSD * this.usdIn).toFixed(0);

                } else {

                    let getUSDTOKH = this.getUSD * this.usdOut;
                    let payKH = this.totalPriceRiel - getUSDTOKH;
                    let chKH = this.getKH - payKH;
                    this.changeKH = Number(chKH).toFixed(0);

                    if (this.getKH == 0) {

                        this.changeUSD = Number(this.getUSD - this.totalPrice).toFixed(2);

                    } else {
                        this.changeUSD = Number(chKH / this.usdOut).toFixed(2);
                    }
                }
                if(this.getUSD === ""){
                    this.getUSD = 0;
                } else {
                    this.getUSD = parseFloat(this.getUSD);
                }
                if(this.getKH === ""){
                    this.getKH = 0;
                } else {
                    this.getKH = parseInt(this.getKH);
                }

            },
            clickToggle(){
                if(this.totalPrice > 0){
                    this.totalPriceRiel = Number(Number(this.totalPrice) * Number(this.usdOut)).toFixed(0);
                } else {
                    this.totalPriceRiel = 0;
                }
                this.calculatePayment();
            },
            checkStock(id){
                return axios.get('api/products/'+id);
            },
            goToPageInvoice(){
                // this.$router.push('/invoice')
            },
            printInvoice() {
                document.getElementById("printThis").style.display = "block";
                this.printElement(document.getElementById("printThis"));
                document.getElementById("printThis").style.display = "none";
            },
            printElement(elem) {
                var domClone = elem.cloneNode(true);

                var $printSection = document.getElementById("printSection");

                if (!$printSection) {
                    var $printSection = document.createElement("div");
                    $printSection.id = "printSection";
                    document.body.appendChild($printSection);
                }

                $printSection.innerHTML = "";
                $printSection.appendChild(domClone);
                window.print();
            }
        },
        mounted() {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            this.categoriesList();
            this.getAllProducts();
            this.getRates();
            axios.get('api/user').then((response) => {
                this.currentUser = response.data
            }).catch((errors) => {
                console.log(errors)
            })
        }
    }
</script>

<style>
    .row {margin-bottom:2rem;}
    .col, .col-sm-3 {text-align: center;color:purple; padding: 15px; }
    .modal-lg{
        width: 500px;
    }
    #invoiceModal{
        width: 1080px;
        height: auto;
    }
    @media screen {
        #printSection {
            display: none;
        }
    }

    @media print {
        body * {
            visibility:hidden;
        }
        #printSection, #printSection * {
            visibility:visible;
        }
        #printSection {
            position:absolute;
            left:0;
            top:0;
        }
    }
    #printThis{
        display: none;
    }

</style>
