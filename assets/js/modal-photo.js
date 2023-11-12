const base = document.querySelector('.container-photo-item-modal').getAttribute('data-url');
document.querySelectorAll('.user-photo-item img').forEach(item => {
    item.addEventListener('click', () => {
        modalPhoto(item);

    });
});

function modalPhoto(img) {
    console.log(img.src);
    document.querySelector('.container-photo-item-modal').style.display = "flex";
    document.getElementById('img').src = img.src;
    setTimeout(() => {
        document.addEventListener('click', removeModal);
    }, 500)

}

function removeModal() {
    document.querySelector('.container-photo-item-modal').style.display = "none";
    document.removeEventListener('click', removeModal);
}