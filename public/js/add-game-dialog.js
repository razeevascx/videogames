if (!('commandForElement' in HTMLButtonElement.prototype)) {
    document.querySelectorAll('[command="show-modal"]').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById(button.getAttribute('commandfor')).showModal();
        });
    });
}
const dialogParams = new URLSearchParams(window.location.search);
if (dialogParams.get('add') === '1') {
    document.getElementById('add-game-dialog').showModal();
} else if (dialogParams.get('search') === '1') {
    document.getElementById('search-game-dialog').showModal();
}
