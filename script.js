document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
        const answer = button.nextElementSibling;
        answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
    });
});

function toggleMenu() {
    var menu = document.querySelector("nav ul");
    if (menu.style.display === "flow") {
        menu.style.display = "none";
    } else {
        menu.style.display = "flow";
    }
}
