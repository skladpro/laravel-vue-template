@extends('layouts.main')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="height: 90vh;">
        <form action="{{route('auth.store')}}" method="POST" class="w-50">
            @csrf
            <div class="text-center mb-3">
                <h1>Авторизация</h1>
            </div>

            <div class="form-floating mb-4">
                <input type="email" id="login" name="login" class="form-control" placeholder="Email" required
                       minlength="10"/>
                <label class="form-label" for="loginName">Логин</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" id="pass" name="password" class="form-control" placeholder="Пароль" required
                       minlength="6"/>
                <label class="form-label" for="loginPassword">Пароль</label>
            </div>

            <button type="submit" class="btn btn-primary btn-block mb-4">Войти</button>
        </form>
    </div>


    <script>
        const forms = document.querySelectorAll('form')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    </script>
@endsection
