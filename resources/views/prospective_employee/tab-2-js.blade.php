<script>
    $(document).ready(function() {
        // nama sekolah 1
        $('.namaSekolah_1').on('keyup', function() {
            sessionStorage.setItem("getNamaSekolah1", $(this).val());
        });
        $('.namaSekolah_1').val(sessionStorage.getItem("getNamaSekolah1"));

        // jurusan sekolah 1
        $('.jurusanSekolah_1').on('keyup', function() {
            sessionStorage.setItem("getJurusanSekolah1", $(this).val());
        });
        $('.jurusanSekolah_1').val(sessionStorage.getItem("getJurusanSekolah1"));

        // alamat sekolah 1
        $('.alamatSekolah_1').on('keyup', function() {
            sessionStorage.setItem("getAlamatSekolah1", $(this).val());
        });
        $('.alamatSekolah_1').val(sessionStorage.getItem("getAlamatSekolah1"));

        // tahun lulus sekolah 1
        $('.tahunLulusSekolah_1').on('keyup', function() {
            sessionStorage.setItem("getTahunLulusSekolah1", $(this).val());
        });
        $('.tahunLulusSekolah_1').val(sessionStorage.getItem("getTahunLulusSekolah1"));

        // nilai rata-rata sekolah 1
        $('.nilaiRataRataSekolah_1').on('keyup', function() {
            sessionStorage.setItem("getNilaiRataRataSekolah1", $(this).val());
        });
        $('.nilaiRataRataSekolah_1').val(sessionStorage.getItem("getNilaiRataRataSekolah1"));

        // nama sekolah 2
        $('.namaSekolah_2').on('keyup', function() {
            sessionStorage.setItem("getNamaSekolah2", $(this).val());
        });
        $('.namaSekolah_2').val(sessionStorage.getItem("getNamaSekolah2"));

        // jurusan sekolah 2
        $('.jurusanSekolah_2').on('keyup', function() {
            sessionStorage.setItem("getJurusanSekolah2", $(this).val());
        });
        $('.jurusanSekolah_2').val(sessionStorage.getItem("getJurusanSekolah2"));

        // alamat sekolah 2
        $('.alamatSekolah_2').on('keyup', function() {
            sessionStorage.setItem("getAlamatSekolah2", $(this).val());
        });
        $('.alamatSekolah_2').val(sessionStorage.getItem("getAlamatSekolah2"));

        // tahun lulus sekolah 2
        $('.tahunLulusSekolah_2').on('keyup', function() {
            sessionStorage.setItem("getTahunLulusSekolah2", $(this).val());
        });
        $('.tahunLulusSekolah_2').val(sessionStorage.getItem("getTahunLulusSekolah2"));

        // nilai rata-rata sekolah 2
        $('.nilaiRataRataSekolah_2').on('keyup', function() {
            sessionStorage.setItem("getNilaiRataRataSekolah2", $(this).val());
        });
        $('.nilaiRataRataSekolah_2').val(sessionStorage.getItem("getNilaiRataRataSekolah2"));

        // nama sekolah 3
        $('.namaSekolah_3').on('keyup', function() {
            sessionStorage.setItem("getNamaSekolah3", $(this).val());
        });
        $('.namaSekolah_3').val(sessionStorage.getItem("getNamaSekolah3"));

        // jurusan sekolah 3
        $('.jurusanSekolah_3').on('keyup', function() {
            sessionStorage.setItem("getJurusanSekolah3", $(this).val());
        });
        $('.jurusanSekolah_3').val(sessionStorage.getItem("getJurusanSekolah3"));

        // alamat sekolah 3
        $('.alamatSekolah_3').on('keyup', function() {
            sessionStorage.setItem("getAlamatSekolah3", $(this).val());
        });
        $('.alamatSekolah_3').val(sessionStorage.getItem("getAlamatSekolah3"));

        // tahun lulus sekolah 3
        $('.tahunLulusSekolah_3').on('keyup', function() {
            sessionStorage.setItem("getTahunLulusSekolah3", $(this).val());
        });
        $('.tahunLulusSekolah_3').val(sessionStorage.getItem("getTahunLulusSekolah3"));

        // nilai rata-rata sekolah 3
        $('.nilaiRataRataSekolah_3').on('keyup', function() {
            sessionStorage.setItem("getNilaiRataRataSekolah3", $(this).val());
        });
        $('.nilaiRataRataSekolah_3').val(sessionStorage.getItem("getNilaiRataRataSekolah3"));
    });
</script>
<script>
    $(document).ready(function() {
        // nama perusahaan 1
        $('.namaPerusahaan_1').on('keyup', function() {
            sessionStorage.setItem("getNamaPerusahaan1", $(this).val());
        });
        $('.namaPerusahaan_1').val(sessionStorage.getItem("getNamaPerusahaan1"));

        // jabatan perusahaan 1
        $('.jabatanPerusahaan_1').on('keyup', function() {
            sessionStorage.setItem("getJabatanPerusahaan1", $(this).val());
        });
        $('.jabatanPerusahaan_1').val(sessionStorage.getItem("getJabatanPerusahaan1"));

        // bulan masuk perusahaan 1
        $('.bulanMasukPerusahaan_1').on('keyup', function() {
            sessionStorage.setItem("getBulanMasukPerusahaan1", $(this).val());
        });
        $('.bulanMasukPerusahaan_1').val(sessionStorage.getItem("getBulanMasukPerusahaan1"));

        // tahun masuk perusahaan 1
        $('.tahunMasukPerusahaan_1').on('keyup', function() {
            sessionStorage.setItem("getTahunMasukPerusahaan1", $(this).val());
        });
        $('.tahunMasukPerusahaan_1').val(sessionStorage.getItem("getTahunMasukPerusahaan1"));

        // gaji perusahaan 1
        $('.gajiPerusahaan_1').on('keyup', function() {
            sessionStorage.setItem("getGajiPerusahaan1", $(this).val());
        });
        $('.gajiPerusahaan_1').val(sessionStorage.getItem("getGajiPerusahaan1"));
        $('.gajiPerusahaan_1').on('keyup', function() {
            var selection = window.getSelection().toString();
            if (selection !== '') {
                return;
            }
            // When the arrow keys are pressed, abort.
            if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                return;
            }
            var $this = $(this);
            // Get the value.
            var input = $this.val();
            var input = input.replace(/[\D\s\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            $this.val(function() {
                return (input === 0) ? "" : input.toLocaleString("id-ID");
            });
            $this.next().val(input);
        });

        // alasan keluar perusahaan 1
        $('.alasanKeluarPerusahaan_1').on('keyup', function() {
            sessionStorage.setItem("getAlasanKeluarPerusahaan1", $(this).val());
        });
        $('.alasanKeluarPerusahaan_1').val(sessionStorage.getItem("getAlasanKeluarPerusahaan1"));


        // nama perusahaan 2
        $('.namaPerusahaan_2').on('keyup', function() {
            sessionStorage.setItem("getNamaPerusahaan2", $(this).val());
        });
        $('.namaPerusahaan_2').val(sessionStorage.getItem("getNamaPerusahaan2"));

        // jabatan perusahaan 2
        $('.jabatanPerusahaan_2').on('keyup', function() {
            sessionStorage.setItem("getJabatanPerusahaan2", $(this).val());
        });
        $('.jabatanPerusahaan_2').val(sessionStorage.getItem("getJabatanPerusahaan2"));

        // bulan masuk perusahaan 2
        $('.bulanMasukPerusahaan_2').on('keyup', function() {
            sessionStorage.setItem("getBulanMasukPerusahaan2", $(this).val());
        });
        $('.bulanMasukPerusahaan_2').val(sessionStorage.getItem("getBulanMasukPerusahaan2"));

        // tahun masuk perusahaan 2
        $('.tahunMasukPerusahaan_2').on('keyup', function() {
            sessionStorage.setItem("getTahunMasukPerusahaan2", $(this).val());
        });
        $('.tahunMasukPerusahaan_2').val(sessionStorage.getItem("getTahunMasukPerusahaan2"));

        // gaji perusahaan 2
        $('.gajiPerusahaan_2').on('keyup', function() {
            sessionStorage.setItem("getGajiPerusahaan2", $(this).val());
        });
        $('.gajiPerusahaan_2').val(sessionStorage.getItem("getGajiPerusahaan2"));
        $('.gajiPerusahaan_2').on('keyup', function() {
            var selection = window.getSelection().toString();
            if (selection !== '') {
                return;
            }
            // When the arrow keys are pressed, abort.
            if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                return;
            }
            var $this = $(this);
            // Get the value.
            var input = $this.val();
            var input = input.replace(/[\D\s\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            $this.val(function() {
                return (input === 0) ? "" : input.toLocaleString("id-ID");
            });
            $this.next().val(input);
        });
        // alasan keluar perusahaan 2
        $('.alasanKeluarPerusahaan_2').on('keyup', function() {
            sessionStorage.setItem("getAlasanKeluarPerusahaan2", $(this).val());
        });
        $('.alasanKeluarPerusahaan_2').val(sessionStorage.getItem("getAlasanKeluarPerusahaan2"));


        // nama perusahaan 3
        $('.namaPerusahaan_3').on('keyup', function() {
            sessionStorage.setItem("getNamaPerusahaan3", $(this).val());
        });
        $('.namaPerusahaan_3').val(sessionStorage.getItem("getNamaPerusahaan3"));

        // jabatan perusahaan 3
        $('.jabatanPerusahaan_3').on('keyup', function() {
            sessionStorage.setItem("getJabatanPerusahaan3", $(this).val());
        });
        $('.jabatanPerusahaan_3').val(sessionStorage.getItem("getJabatanPerusahaan3"));

        // bulan masuk perusahaan 3
        $('.bulanMasukPerusahaan_3').on('keyup', function() {
            sessionStorage.setItem("getBulanMasukPerusahaan3", $(this).val());
        });
        $('.bulanMasukPerusahaan_3').val(sessionStorage.getItem("getBulanMasukPerusahaan3"));

        // tahun masuk perusahaan 3
        $('.tahunMasukPerusahaan_3').on('keyup', function() {
            sessionStorage.setItem("getTahunMasukPerusahaan3", $(this).val());
        });
        $('.tahunMasukPerusahaan_3').val(sessionStorage.getItem("getTahunMasukPerusahaan3"));

        // gaji perusahaan 3
        $('.gajiPerusahaan_3').on('keyup', function() {
            sessionStorage.setItem("getGajiPerusahaan3", $(this).val());
        });
        $('.gajiPerusahaan_3').val(sessionStorage.getItem("getGajiPerusahaan3"));
        $('.gajiPerusahaan_3').on('keyup', function() {
            var selection = window.getSelection().toString();
            if (selection !== '') {
                return;
            }
            // When the arrow keys are pressed, abort.
            if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                return;
            }
            var $this = $(this);
            // Get the value.
            var input = $this.val();
            var input = input.replace(/[\D\s\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            $this.val(function() {
                return (input === 0) ? "" : input.toLocaleString("id-ID");
            });
            $this.next().val(input);
        });
        // alasan keluar perusahaan 3
        $('.alasanKeluarPerusahaan_3').on('keyup', function() {
            sessionStorage.setItem("getAlasanKeluarPerusahaan3", $(this).val());
        });
        $('.alasanKeluarPerusahaan_3').val(sessionStorage.getItem("getAlasanKeluarPerusahaan3"));
    });
</script>
