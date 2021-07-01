<template>
    <div class="container">
        <LogoutComponent v-bind:user-name="userName"></LogoutComponent>
            <nav class="navbar navbar-expand-lg navbar-light bg-primary text-black-50">
                <div class="collapse navbar-collapse">
                    <!-- for logged-in user-->
                    <div class="navbar-nav  " v-if="isLoggedIn && roleUser === 'admin'">
                        <router-link to="/" class="nav-item nav-link">Sale</router-link>
                        <router-link to="/users" class="nav-item nav-link">User</router-link>
                        <router-link to="/categories" class="nav-item nav-link">Category</router-link>
                        <router-link to="/stocks" class="nav-item nav-link">Stock</router-link>
                        <router-link to="/imports" class="nav-item nav-link">Import</router-link>
                        <router-link to="/rates" class="nav-item nav-link">Exchange Rate</router-link>
                    </div>
                    <div class="navbar-nav " v-else-if="isLoggedIn && roleUser !== 'admin'">
                        <router-link to="/" class="nav-item nav-link">Sale</router-link>
                    </div>
                </div>
            </nav>
    </div>
</template>

<script>
    import LogoutComponent from "./LogoutComponent";
    export default {
        name: "NavComponent",
        components:{
          LogoutComponent
        },
        props: ['roleUser', 'userName'],
        data() {
            return {
                isLoggedIn: localStorage.getItem('token') != "" ? true : false
            }
        }
    }
</script>

