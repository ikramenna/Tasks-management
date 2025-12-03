@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Connexion</h2>
            <form id="loginForm">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>
            <p class="text-center mt-3">
                Pas de compte ? <a href="{{ route('register') }}">S'inscrire</a>
            </p>
            <div id="message" class="mt-3"></div>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    fetch('/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email, password })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.access_token) {
            localStorage.setItem('jwt_token', data.access_token);
            window.location.href = '/tasks';
        } else {
            const errorMsg = data.message || data.error || 'Identifiants incorrects';
            document.getElementById('message').innerHTML = '<div class="alert alert-danger">' + errorMsg + '</div>';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('message').innerHTML = '<div class="alert alert-danger">Erreur réseau ou serveur indisponible.</div>';
    });
});
</script>
@endsection