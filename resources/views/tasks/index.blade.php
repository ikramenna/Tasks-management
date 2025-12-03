<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index</title>
</head>
<body>
    @extends('layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Mes Tâches</h2>
        <button class="btn btn-primary" id="openCreateModal">+ Nouvelle tâche</button>
    </div>

    <div id="message"></div>
    <div id="tasksList">
        <!-- Les tâches seront affichées ici -->
    </div>
</div>

<!-- Modal de création (simple) -->
<div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Créer une tâche</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="taskTitle" class="form-control" placeholder="Titre de la tâche" required>
                <textarea id="taskDescription" class="form-control mt-2" placeholder="Description (optionnelle)"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="saveTask">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function () {
    const token = localStorage.getItem('jwt_token');
    const messageDiv = document.getElementById('message');
    const tasksList = document.getElementById('tasksList');
    const modal = new bootstrap.Modal(document.getElementById('taskModal'));

    if (!token) {
        window.location.href = '/login'; // ou '/register'
        return;
    }

    // Fonction pour charger les tâches
    async function loadTasks() {
        try {
            const res = await fetch('/api/tasks', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            });

            if (!res.ok) {
                throw new Error('Impossible de charger les tâches');
            }

            const tasks = await res.json();
            tasksList.innerHTML = tasks.length
                ? `
                    <div class="list-group">
                        ${tasks.map(t => `
                            <div class="list-group-item">
                                <h5>${t.title || 'Sans titre'}</h5>
                                <p class="text-muted">${t.description || ''}</p>
                                <small class="text-muted">ID: ${t.id}</small>
                            </div>
                        `).join('')}
                    </div>
                `
                : '<p class="text-muted">Aucune tâche pour le moment.</p>';
        } catch (err) {
            console.error(err);
            tasksList.innerHTML = '<div class="alert alert-warning">Erreur lors du chargement des tâches.</div>';
        }
    }

    // Ouvrir le modal
    document.getElementById('openCreateModal').addEventListener('click', () => {
        document.getElementById('taskTitle').value = '';
        document.getElementById('taskDescription').value = '';
        modal.show();
    });

    // Sauvegarder une nouvelle tâche
    document.getElementById('saveTask').addEventListener('click', async () => {
        const title = document.getElementById('taskTitle').value.trim();
        if (!title) return alert('Le titre est requis.');

        try {
            const res = await fetch('/api/tasks', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ title, description: document.getElementById('taskDescription').value })
            });

            if (res.ok) {
                modal.hide();
                loadTasks(); // Recharger la liste
            } else {
                const err = await res.json();
                alert('Erreur : ' + (err.message || 'Impossible de créer la tâche'));
            }
        } catch (error) {
            console.error(error);
            alert('Erreur réseau');
        }
    });

    // Charger les tâches au démarrage
    loadTasks();
});
</script>
@endsection
</body>
</html>