document.getElementById('quiz-form')?.addEventListener('submit', function(e) {
    e.preventDefault();

    // Simulation de calcul de note (Tu adapteras avec tes inputs de QCM)
    const scoreObtenu = 85; 

    const data = {
        lesson_id: document.getElementById('lesson_id').value,
        course_id: document.getElementById('course_id').value,
        module_id: document.getElementById('module_id').value,
        score: scoreObtenu
    };

    // Requête AJAX asynchrone avec Fetch API
    fetch('/index.php?action=submit_quiz', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            // Mise à jour dynamique de l'interface sans recharger la page !
            const progressBar = document.getElementById('course-progress-bar');
            const progressText = document.getElementById('course-progress-text');
            
            if (progressBar && progressText) {
                progressBar.style.width = res.progress + '%';
                progressText.innerText = res.progress + '% complété';
            }

            alert("Quiz enregistré ! Validation de la leçon : " + (res.is_lesson_completed ? "OUI" : "NON"));

            if (res.certificate_earned) {
                alert("Félicitations ! Vous avez validé le module. Code Certificat : " + res.certificate_earned);
            }
        } else {
            alert("Erreur : " + res.message);
        }
    })
    .catch(error => console.error('Erreur AJAX:', error));
});