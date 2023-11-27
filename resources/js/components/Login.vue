<template>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Login (Compomente Vue)</div>
                <div class="card-body">
                    <form method="POST" action="" @submit.prevent="login($event)">
                        <input type="hidden" name="_token" :value="csrf_token">
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="" required autocomplete="email" autofocus v-model="email">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Senha</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" v-model="password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                    <label class="form-check-label" for="remember">
                                        Mantenha-me conectado
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                                    <a class="btn btn-link" href="">
                                        Esqueci a senha
                                    </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props: ['csrf_token'], // Semelhante ao data, mas para receber dados de outro componente

        data(){ // o data está em um formato diferente pois o Vue não aceita o formato de objeto
            return {
                email: '',
                password: '',
            }
        },
        methods: {
            login(event) {
                let url = 'http://localhost:8000/api/login'
                let config ={
                    method: 'POST',
                    body: new URLSearchParams({
                        'email': this.email,
                        'password': this.password,
                    })
                }
                fetch(url, config)
                .then(response => response.json()) // then serve para pegar a resposta da requisição
                .then(data => { // o segundo then serve para pegar os dados da resposta
                    if(data.token){
                        document.cookie = `token=${data.token}; SameSite=Lax`;
                    }
                    event.target.submit() // dando prosseguimento ao envio do formulário
                })
            }
        }
    }
    // a propriedade com o - é convertida para camelCase
</script>
