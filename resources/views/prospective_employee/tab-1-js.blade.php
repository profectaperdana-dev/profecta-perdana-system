<script>
    $(document).ready(function() {
        // TAMBAH ANAK
        var j = 0;
        var y = $('#formChild').find('.row').last().find('.indexes').val();
        $('#addChild').on('click', function() {
            j++;
            let rows = ` <div class="row form-group py-2 border border-primary rounded" style="background-color: #3db39d"
                        style="color: black">
                        <input type="hidden" class="indexes" value="${parseInt(y)+1}">
                        <div class="col-md-5 form-group">
                            <label><span class="text-danger">* </span> Urutan Anak / <i> Child Order </i></label>
                            <input type="text" name="" readonly value="Urutan ke-${parseInt(y)+1}"
                                class="form-control  text-capitalize " placeholder="Cth. John Doe" >
                        </div>
                        <div class="col-md-5 form-group">
                            <label><span class="text-danger">* </span> Nama / <i>Name</i></label>
                            <input type="text" name="" class="form-control  text-capitalize "
                                placeholder="Cth. Roger" >
                        </div>
                        <div class="col-md-2 form-group">
                            <label><span class="text-danger">* </span> Jenis Kelamin / <i>Gender</i></label>
                            <select name="" id="" class="form-control">
                                <option value="" selected>-- Pilih / Select --</option>
                                <option value="">Laki-laki / <i>Male</i></option>
                                <option value="">Perempuan / <i>Female</i></option>

                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label><span class="text-danger">* </span> Usia / <i>Age
                                </i></label>
                            <input type="number" name="" value=""
                                class="form-control  text-capitalize " placeholder="45" >
                        </div>
                        <div class="col-md-5 form-group">
                            <label><span class="text-danger">* </span> Pendidikan / <i>Education</i></label>
                            <select name="" id="" class="form-control">
                                <option value="" selected>--Pilih / Select--</option>
                                <option value="Strata-2">Strata-2</option>
                                <option value="Strata-1">Strata-1</option>
                                <option value="SMA/SMK/MA">SMA/SMK/MA</option>
                                <option value="SMP/MTs">SMP/MTs</option>
                                <option value="SD/MI">SD/MI</option>
                            </select>
                        </div>
                        <div class="col-md-5 form-group">
                            <label><span class="text-danger">* </span> Pekerjaan / <i>Proffesion</i></label>
                            <input type="text" name="" value=""
                                class="form-control  text-capitalize " placeholder="Cth. Ministry" >
                        </div>
                         <div class="col-md-1">
                        <button id="remChild" type="button" class="btn form-control text-white btn-sm btn-danger">-</button>
                         </div>
                    </div>`;
            $('#formChild').append(rows);
            y = $('#formChild').find('.row').last().find('.indexes').val();
        });
        $(document).on("click", "#remChild", function() {
            // x = x + 1;
            $(this).parents('.form-group').remove();
            y = $('#formChild').find('.row').last().find('.indexes').val();

        });

        // tambah saudara
        var x = $('#formBrother').find('.row').last().find('.index').val();
        var i = 0;
        $('#addBrother').on('click', function() {
            // console.log(x);

            i++;
            let row = ` <div class="row form-group  py-2 border border-primary rounded"
                    style="color: black;background-color: #eedd86;">
                    <input type="hidden" class="index" value="${parseInt(x)+1}">
                    <div class="col-md-5 form-group">
                        <label><span class="text-danger">* </span> Urutan Saudara / <i> Sibling Order </i></label>
                        <input type="text" name="formBrother[0][status]" readonly value="Urutan ke-${parseInt(x)+1}"
                            class="form-control  text-capitalize " placeholder="Cth. John Doe" >
                    </div>
                    <div class="col-md-5 form-group">
                        <label><span class="text-danger">* </span> Nama / <i>Name</i></label>
                        <input type="text" name="formBrother[0][name]" class="form-control  text-capitalize"
                            placeholder="Cth. Katty" >
                    </div>
                    <div class="col-md-2 form-group">
                        <label><span class="text-danger">* </span> Jenis Kelamin / <i>Gender</i></label>
                        <select name="formBrother[0][gender]" id="" class="form-control">
                            <option value="" selected>--Pilih / Select--</option>
                            <option value="Laki-laki">Laki-laki / <i>Male</i></option>
                            <option value="Perempuan">Perempuan / <i>Female</i></option>

                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label><span class="text-danger">* </span> Usia / <i>Age
                            </i></label>
                        <input type="number" name="formBrother[0][age]" value=""
                            class="form-control  text-capitalize " placeholder="45" >
                    </div>
                    <div class="col-md-5 form-group">
                        <label><span class="text-danger">* </span> Pendidikan / <i>Education</i></label>
                        <select name="formBrother[0][last_education]" id="" class="form-control">
                            <option value="" selected>--Pilih / Select--</option>
                            <option value="Strata-2">Strata-2</option>
                            <option value="Strata-1">Strata-1</option>
                            <option value="SMA/SMK/MA">SMA/SMK/MA</option>
                            <option value="SMP/MTs">SMP/MTs</option>
                            <option value="SD/MI">SD/MI</option>
                        </select>
                    </div>
                    <div class="col-md-5 form-group">
                        <label><span class="text-danger">* </span> Pekerjaan / <i>Proffesion</i></label>
                        <input type="text" name="formBrother[0][job]" value=""
                            class="form-control  text-capitalize " placeholder="Cth. Ministry" >
                    </div>
                    <div class="col-md-1">
                        <button id="remBrother" type="button" class="btn form-control text-white btn-sm btn-danger">-</button>

                    </div>
                </div>  `;
            $('#formBrother').append(row);
            x = $('#formBrother').find('.row').last().find('.index').val();

        });
        $(document).on("click", "#remBrother", function() {
            // x = x + 1;
            $(this).parents('.form-group').remove();
            x = $('#formBrother').find('.row').last().find('.index').val();
        });

        // nama ayah
        $('.namaAyah').on('keyup', function() {
            sessionStorage.setItem("getNameAyah", $(this).val());
        });
        $('.namaAyah').val(sessionStorage.getItem("getNameAyah"));

        // usia Ayah
        $('.usiaAyah').on('keyup', function() {
            sessionStorage.setItem("getAgeAyah", $(this).val());
        });
        $('.usiaAyah').val(sessionStorage.getItem("getAgeAyah"));

        // pendidikan Ayah
        $('.pendidikanAyah').on('change', function() {
            sessionStorage.setItem("getEducationAyah", $(this).val());
        });
        $('.pendidikanAyah').val(sessionStorage.getItem("getEducationAyah"));

        // pekerjaan Ayah
        $('.pekerjaanAyah').on('keyup', function() {
            sessionStorage.setItem("getJobAyah", $(this).val());
        });
        $('.pekerjaanAyah').val(sessionStorage.getItem("getJobAyah"));

        // nama ibu
        $('.namaIbu').on('keyup', function() {
            sessionStorage.setItem("getNameIbu", $(this).val());
        });
        $('.namaIbu').val(sessionStorage.getItem("getNameIbu"));

        // usia ibu
        $('.usiaIbu').on('keyup', function() {
            sessionStorage.setItem("getAgeIbu", $(this).val());
        });
        $('.usiaIbu').val(sessionStorage.getItem("getAgeIbu"));

        // pendidikan ibu
        $('.pendidikanIbu').on('change', function() {
            sessionStorage.setItem("getEducationIbu", $(this).val());
        });
        $('.pendidikanIbu').val(sessionStorage.getItem("getEducationIbu"));

        // pekerjaan ibu
        $('.pekerjaanIbu').on('keyup', function() {
            sessionStorage.setItem("getJobIbu", $(this).val());
        });
        $('.pekerjaanIbu').val(sessionStorage.getItem("getJobIbu"));

        // nama saudara
        $('.namaSaudara').on('keyup', function() {
            sessionStorage.setItem("getNameSaudara", $(this).val());
        });
        $('.namaSaudara').val(sessionStorage.getItem("getNameSaudara"));

        // jenis kelamin saudara
        $('.jenisKelaminSaudara').on('change', function() {
            sessionStorage.setItem("getGenderSaudara", $(this).val());
        });
        $('.jenisKelaminSaudara').val(sessionStorage.getItem("getGenderSaudara"));

        // usia saudara
        $('.usiaSaudara').on('keyup', function() {
            sessionStorage.setItem("getAgeSaudara", $(this).val());
        });
        $('.usiaSaudara').val(sessionStorage.getItem("getAgeSaudara"));

        // pendidikan saudara
        $('.pendidikanSaudara').on('change', function() {
            sessionStorage.setItem("getEducationSaudara", $(this).val());
        });
        $('.pendidikanSaudara').val(sessionStorage.getItem("getEducationSaudara"));

        // pekerjaan saudara
        $('.pekerjaanSaudara').on('keyup', function() {
            sessionStorage.setItem("getJobSaudara", $(this).val());
        });
        $('.pekerjaanSaudara').val(sessionStorage.getItem("getJobSaudara"));
    });
</script>
