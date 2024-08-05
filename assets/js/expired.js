// session-expiration.js
console.log('test');
// Fungsi untuk mendeteksi berakhirnya sesi
function checkSessionExpiration() {
    // Ganti 'YOUR_SESSION_EXPIRATION_TIME' dengan waktu berakhirnya sesi yang diatur di Laravel (dalam menit)
    var sessionExpirationTime = 30; 
    var lastActivity = parseInt(localStorage.getItem('lastActivity'));
    var currentTime = Math.floor(Date.now() / 1000);
    var elapsedSeconds = currentTime - lastActivity;

    if (elapsedSeconds >= sessionExpirationTime * 60) {
        // Sesinya berakhir, tampilkan SweetAlert popup
        swal("Your session has expired", "Please Relogin", "error", {
            button: {
                text: "Relog",
                value: true,
                visible: true,
                className: "btn-primary",
                closeModal: true,
            },
                closeOnClickOutside: false, // Tambahkan opsi ini untuk mencegah menutup SweetAlert dengan mengklik latar belakang

        }).then((value) => {
            if (value) {
                // Arahkan ke halaman awal
                window.location.href = "/";
            }
        });
    }
}

// Fungsi untuk memperbarui timestamp aktivitas terakhir pada local storage
function updateLastActivityTimestamp() {
    localStorage.setItem('lastActivity', Math.floor(Date.now() / 1000));
}

// Perbarui timestamp aktivitas terakhir ketika pengguna berinteraksi dengan halaman
document.addEventListener("DOMContentLoaded", function () {
    updateLastActivityTimestamp();
});

document.addEventListener("click", function () {
    updateLastActivityTimestamp();
});

document.addEventListener("keydown", function () {
    updateLastActivityTimestamp();
});

// Periksa berakhirnya sesi secara berkala
setInterval(checkSessionExpiration, 1000); // Periksa setiap satu menit (sesuaikan jika diperlukan)
