document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.login-link').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.container').style.display = 'none';
            document.querySelector('.container1').style.display = 'block';
        });

        document.querySelector('.register-link').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.container').style.display = 'block';
            document.querySelector('.container1').style.display = 'none';
        });

        document.querySelector('.container').style.display = 'block';
        document.querySelector('.container1').style.display = 'none';

    });

function closeNewDeckForm() {
    document.getElementById('newForm').style.display = 'none';
    document.getElementById('modalOverlay').style.display = 'none';
    document.querySelector('.card-container').style.display = 'block';
}

function openNewDeckForm() {
    document.getElementById('newForm').style.display = 'flex';
    document.getElementById('modalOverlay').style.display = 'block';
    document.querySelector('.card-container').style.display = 'block';
}

function openCard() {
    window.location.href = 'detail.php';
}

function createCard() {
    window.location.href = 'newdeck.php';
}

function goBack() {
    window.location.href = 'cards.php';
}

function goBackDetails() {
    window.location.href = 'detail.php';
}

function startReview() {
    window.location.href = 'flash.php';
}

document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.flash-question')) {
        let cards = window.cards || [];
        let current = 0;

        function showQuestion() {
            if (current < cards.length) {
                document.querySelector('.flash-question').textContent = cards[current].card_name;
                document.querySelector('.answer').textContent = cards[current].answer;
                document.querySelector('.flash-answer-box').classList.remove('show');
                document.querySelector('.answer').style.display = "none";
                document.querySelector('.flash-showanswer-btn').style.display = "inline-block";
                document.querySelector('.flash-difficulty').style.display = "block";
            } else {
                document.querySelector('.flash-question').textContent = "No more questions!";
                document.querySelector('.answer').textContent = "";
                document.querySelector('.flash-answer-box').classList.remove('show');
                document.querySelector('.flash-difficulty').style.display = "none";
                document.querySelector('.flash-showanswer-btn').style.display = "none";
            }
        }

        showQuestion();

        document.querySelector('.flash-showanswer-btn').onclick = function() {
            document.querySelector('.flash-answer-box').classList.add('show');
            document.querySelector('.answer').style.display = "block";
            this.style.display = "none";
        };

        document.querySelectorAll('.flash-btn').forEach(btn => {
            btn.onclick = function() {
                current++;
                showQuestion();
            };
        });
    }
});
