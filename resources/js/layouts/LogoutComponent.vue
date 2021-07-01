<template>
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-4 text-center">

            </div>
            <div class="col-4 text-center">
                <h1>
                    <a class="blog-header-logo text-dark" href="#">POS SYSEM DEMO</a>
                </h1>
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center">
                <div class="dropdown">
                    <button class="btn  dropdown-toggle"
                            type="button" id="dropdownMenuButton"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                        {{userName}}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item" @click="logout">Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
    export default {
        name: "LogoutComponent",
        props: ['userName'],
        data() {
            return {
                token: localStorage.getItem('token')
            }
        },
        methods: {
            logout(){
                axios.post('api/logout').then((response) => {
                    localStorage.removeItem('token')
                    this.$router.push('/login')
                }).catch((errors) => {
                    console.log(errors)
                })
            }
        },
        mounted() {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        }
    }
</script>

