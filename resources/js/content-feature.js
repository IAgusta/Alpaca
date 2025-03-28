document.addEventListener("DOMContentLoaded", function () {
    // Existing code for handling exercise forms
    document.querySelectorAll(".exercise-form").forEach(form => {
        form.querySelector(".submit-answer").addEventListener("click", function () {
            const selectedAnswer = form.querySelector('input[name^="answer-"]:checked');
            const explanationDiv = form.nextElementSibling; // Target the sibling div
            const iconSpan = explanationDiv ? explanationDiv.querySelector(".icon") : null;
            const explanationTextSpan = explanationDiv ? explanationDiv.querySelector(".explanation-text") : null;

            // Debugging: Log the selected answer value
            console.log("Selected Answer Value:", selectedAnswer ? selectedAnswer.value : "No answer selected");

            if (!selectedAnswer) {
                alert("Please select an answer.");
                return;
            }

            if (!explanationDiv || !iconSpan || !explanationTextSpan) {
                console.error("Explanation elements not found.");
                return;
            }

            // Check if the selected answer is correct
            const isCorrect = selectedAnswer.value.trim() === "1"; // Ensure it matches the value in your HTML
            explanationDiv.classList.remove("hidden");

            // Set explanation text
            if (form.dataset.explanation) {
                explanationTextSpan.innerHTML = form.dataset.explanation;
            } else {
                explanationTextSpan.innerHTML = isCorrect ? "Great job! You got it right." : "Oops! That's not correct.";
            }

            // Change text color and icon
            explanationDiv.classList.remove("text-red-500", "text-green-500");
            if (isCorrect) {
                explanationDiv.classList.add("text-green-500");
                iconSpan.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Correct!';
            } else {
                explanationDiv.classList.add("text-red-500");
                iconSpan.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Wrong!';
            }
        });
    });
});