    $(document).ready(function() {
        $('#myTable').DataTable()

    });

    const imgInput = document.getElementById('inputreference');
    const imgEl = document.getElementById('previewimg');
    imgInput.addEventListener('change', () => {
    if (imgInput.files && imgInput.files[0]) {
    const reader = new FileReader();
    reader.onload = (e) => {
        imgEl.src = e.target.result;
        imgEl.removeAttribute('hidden');
    }
    reader.readAsDataURL(imgInput.files[0]);
    }
    })


