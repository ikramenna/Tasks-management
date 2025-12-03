@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Inscription</h2>
            <form id="registerForm">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="8">
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-success w-100">S'inscrire</button>
            </form>

            {{-- Optionnel : seulement si vous avez une route login --}}
            @if (Route::has('login'))
                <p class="text-center mt-3">
                    Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
                </p>
            @endif

            <div id="message" class="mt-3"></div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const messageDiv = document.getElementById('message');
    messageDiv.innerHTML = '';

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const password_confirmation = document.getElementById('password_confirmation').value;

    if (password !== password_confirmation) {
        messageDiv.innerHTML = '<div class="alert alert-danger">Les mots de passe ne correspondent pas.</div>';
        return;
    }

    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name, email, password, password_confirmation })
        });

        const data = await response.json();

        if (!response.ok) {
            // Gérer les erreurs de validation ou serveur
            let errorMsg = 'Une erreur est survenue lors de l\'inscription.';
            if (data.errors?.email) {
                errorMsg = data.errors.email[0];
            } else if (data.message) {
                errorMsg = data.message;
            }
            messageDiv.innerHTML = `<div class="alert alert-danger">${errorMsg}</div>`;
            return;
        }

        // Sauvegarder le token
        localStorage.setItem('jwt_token', data.access_token);

        // Rediriger vers la page des tâches
        window.location.href = '/tasks';
    } catch (error) {
        console.error('Erreur réseau :', error);
        messageDiv.innerHTML = '<div class="alert alert-danger">Erreur de connexion au serveur.</div>';
    }
});
</script>
@endsection