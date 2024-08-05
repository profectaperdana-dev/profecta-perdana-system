<script>
    $(document).ready(function() {

        // nama
        $('.nama').on('keyup', function() {
            sessionStorage.setItem("getName", $(this).val());
        });
        $('.nama').val(sessionStorage.getItem("getName"));

        // jenis kelamin
        $('.form-check-input-jk').on('click', function() {
            sessionStorage.setItem("getJk", $(this).val());
        });
        $('.form-check-input-jk').each(function() {
            if ($(this).val() == sessionStorage.getItem("getJk")) {
                $(this).prop('checked', true);
            }
        });

        // tempat lahir
        $('.tempat_lahir').on('keyup', function() {
            sessionStorage.setItem("getTempatLahir", $(this).val());
        });
        $('.tempat_lahir').val(sessionStorage.getItem("getTempatLahir"));

        // tanggal lahir
        $('.tanggal_lahir').on('change', function() {
            sessionStorage.setItem("getTanggalLahir", $(this).val());
        });
        $('.tanggal_lahir').val(sessionStorage.getItem("getTanggalLahir"));

        // tinggi
        $('.tinggi').on('keyup', function() {
            sessionStorage.setItem("getTinggi", $(this).val());
        });
        $('.tinggi').val(sessionStorage.getItem("getTinggi"));

        // berat
        $('.berat').on('keyup', function() {
            sessionStorage.setItem("getBerat", $(this).val());
        });
        $('.berat').val(sessionStorage.getItem("getBerat"));

        // ktp
        $('.ktp').on('keyup', function() {
            sessionStorage.setItem("getKtp", $(this).val());
        });
        $('.ktp').val(sessionStorage.getItem("getKtp"));

        // sim A
        if ($('#cek1').is(':checked')) {
            $('.cek1-form').attr('hidden', false);
            $('.cek1-form').attr('required', true);
        } else {
            $('.cek1-form').attr('hidden', true);
            $('.cek1-form').attr('required', false);
            $('.cek1-form').val('-');
        }
        $('#cek1').on('click', function() {
            var cekA = $(this).prop('checked');
            if (cekA == true) {
                $('.cek1-form').attr('hidden', false);
                $('.cek1-form').attr('required', true);
            } else {
                $('.cek1-form').attr('hidden', true);
                $('.cek1-form').attr('required', false);
                $('.cek1-form').val('-');
            }
        });

        // sim B
        if ($('#cek2').is(':checked')) {
            $('.cek2-form').attr('hidden', false);
            $('.cek2-form').attr('required', true);
        } else {
            $('.cek2-form').attr('hidden', true);
            $('.cek2-form').attr('required', false);
            $('.cek2-form').val('-');
        }
        $('#cek2').on('click', function() {
            var cekB = $(this).prop('checked');
            if (cekB == true) {
                $('.cek2-form').attr('hidden', false);
                $('.cek2-form').attr('required', true);
            } else {
                $('.cek2-form').attr('hidden', true);
                $('.cek2-form').attr('required', false);
                $('.cek2-form').val('-');
            }
        });

        // sim C
        if ($('#cek3').is(':checked')) {
            $('.cek3-form').attr('hidden', false);
            $('.cek3-form').attr('required', true);
        } else {
            $('.cek3-form').attr('hidden', true);
            $('.cek3-form').attr('required', false);
            $('.cek3-form').val('-');
        }
        $('#cek3').on('click', function() {
            var cekC = $(this).prop('checked');
            if (cekC == true) {
                $('.cek3-form').attr('hidden', false);
                $('.cek3-form').attr('required', true);
            } else {
                $('.cek3-form').attr('hidden', true);
                $('.cek3-form').attr('required', false);
                $('.cek3-form').val('-');
            }
        });

        //alamat ktp
        $('.alamatKtp').on('keyup', function() {
            sessionStorage.setItem("getalamatKtp", $(this).val());
        });
        $('.alamatKtp').val(sessionStorage.getItem("getalamatKtp"));

        //alamat skrg
        $('.alamatskrg').on('keyup', function() {
            sessionStorage.setItem("getalamatskrg", $(this).val());
        });
        $('.alamatskrg').val(sessionStorage.getItem("getalamatskrg"));


        $('.form-check-input-jk').on('click', function() {
            sessionStorage.setItem("getJk", $(this).val());
        });
        $('.form-check-input-jk').each(function() {
            if ($(this).val() == sessionStorage.getItem("getJk")) {
                $(this).prop('checked', true);
            }
        });

        // rumah
        $('.cek-rumah').each(function() {
            if ($(this).val() == sessionStorage.getItem("getRumah")) {
                $(this).prop('checked', true);

                if ($(this).val() == 'other') {
                    $('.form-rumah').attr('hidden', false);
                    $('.form-rumah').attr('required', true);
                    $('.form-rumah').on('keyup', function() {
                        sessionStorage.setItem("getFormRumah", $('.form-rumah').val());
                    });
                    $('.form-rumah').val(sessionStorage.getItem("getFormRumah"));

                } else {
                    $('.form-rumah').attr('hidden', true);
                    $('.form-rumah').attr('required', false);
                    $('.form-rumah').val('-');
                }
            }
        });
        $('.cek-rumah').click(function() {
            sessionStorage.setItem("getRumah", $(this).val());
            var checked = $(this).val();
            if (checked == 'other') {
                $('.form-rumah').attr('hidden', false);
                $('.form-rumah').attr('required', true);
                $('.form-rumah').on('keyup', function() {
                    sessionStorage.setItem("getFormRumah", $('.form-rumah').val());
                });
            } else {
                $('.form-rumah').attr('hidden', true);
                $('.form-rumah').attr('required', false);
                $('.form-rumah').val('-');
            }
        });

        // kendaraan
        $('.cek-kendaraan').each(function() {
            if ($(this).val() == sessionStorage.getItem("getKendaraan")) {
                $(this).prop('checked', true);
                var cekVehicle = $(this).prop('checked');
                if ($(cekVehicle == true)) {
                    $('.form-kendaraan').attr('hidden', false);
                    $('.form-kendaraan').attr('required', true);
                    $('.form-kendaraan').on('keyup', function() {
                        sessionStorage.setItem("getFormKendaraan", $('.form-kendaraan').val());
                    });
                    $('.form-kendaraan').val(sessionStorage.getItem("getFormKendaraan"));

                } else {
                    $('.form-kendaraan').attr('hidden', true);
                    $('.form-kendaraan').attr('required', false);
                    $('.form-kendaraan').val('-');
                }
            }
        });
        $('.cek-kendaraan').click(function() {
            sessionStorage.setItem("getKendaraan", $(this).val());
            var checked = $(this).prop('checked');
            if (checked == true) {
                $('.form-kendaraan').attr('hidden', false);
                $('.form-kendaraan').attr('required', true);
                $('.form-kendaraan').on('keyup', function() {
                    sessionStorage.setItem("getFormKendaraan", $('.form-kendaraan').val());
                });
            } else {
                $('.form-kendaraan').attr('hidden', true);
                $('.form-kendaraan').attr('required', false);
                $('.form-kendaraan').val('-');
            }
        });

        // phone
        $('.phone').on('keyup', function() {
            sessionStorage.setItem("getPhone", $(this).val());
        });
        $('.phone').val(sessionStorage.getItem("getPhone"));

        // phone2
        $('.phone2').on('keyup', function() {
            sessionStorage.setItem("getPhone2", $(this).val());
        });
        $('.phone2').val(sessionStorage.getItem("getPhone2"));

        // email
        $('.email').on('keyup', function() {
            sessionStorage.setItem("getEmail", $(this).val());
        });
        $('.email').val(sessionStorage.getItem("getEmail"));

        // relation
        $('.relation').on('change', function() {
            sessionStorage.setItem("getRelation", $(this).val());
        });
        $('.relation').val(sessionStorage.getItem("getRelation"));

        // e_contact
        $('.e_contact').on('keyup', function() {
            sessionStorage.setItem("getE_contact", $(this).val());
        });
        $('.e_contact').val(sessionStorage.getItem("getE_contact"));

        $('.form-check-input-marital').on('click', function() {
            sessionStorage.setItem("getMarital", $(this).val());
            console.log(sessionStorage.getItem("getMarital"));
            if ('married' == sessionStorage.getItem("getMarital")) {

                $('#single').attr('hidden', false);

            } else {
                $('#single').attr('hidden', true);

            }
        })
        // each function
        $('.form-check-input-marital').each(function() {
            if ($(this).val() == sessionStorage.getItem("getMarital")) {
                $(this).prop('checked', true);
            }

            if ('married' == sessionStorage.getItem("getMarital")) {
                $('#single').attr('hidden', false);

            }

        });
    });
</script>
