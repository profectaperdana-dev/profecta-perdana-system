    $(document).ready(function() {
        $('#myTable').DataTable()

    const imgInput = document.getElementById('inputreference');
    const imgEl = document.getElementById('previewimg');
    const previewLabel = document.getElementById('previewLabel');
    imgInput.addEventListener('change', () => {
    if (imgInput.files && imgInput.files[0]) {
    const reader = new FileReader();
    reader.onload = (e) => {
        imgEl.src = e.target.result;
        imgEl.removeAttribute('hidden');
        previewLabel.removeAttribute('hidden');
    }
    reader.readAsDataURL(imgInput.files[0]);
    }
    });

    let eventLoc = document.getElementById('coorGenerate');
    let coor = document.getElementById('coor');

    const options = {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
      };

      eventLoc.addEventListener ("click", getLocation, false);
    function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(showPosition, function() {}, options);
    } else {
        coor.innerHTML = "Geolocation is not supported by this browser.";
    }
    }
    function showPosition(position) {
        eventLoc.setAttribute('hidden', 'true');
        coor.removeAttribute('hidden');
        coor.setAttribute('readonly', 'true');
        coor.value = position.coords.latitude + ", " + position.coords.longitude;
    }



    });




