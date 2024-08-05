<div class="ribbon-wrapper card">
    <div class="card-body">
        <div class="ribbon ribbon-bookmark ribbon-primary">Data Keluarga / <i>Family
                Data</i></div>
        <input type="hidden" value="{{ url()->current() }}" name="link">
        <div class="row font-weight-bold">
            {{-- <input type="hidden" value="{{ url()->current() }}" name="link"> --}}

            {{-- ! ayah --}}
            <div class="form-group row">
                <h5>Orang Tua / <i>Parents</i></h5>
                <hr class="bg-primary">
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Hub. Keluarga / <i> Relation </i></label>
                    <input type="text" name="status" readonly value="ayah"
                        class="form-control text-capitalize  tab-1" placeholder="Ayah / Father" required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Nama / <i>Name</i></label>
                    <input type="text" name="name" value=""
                        class="form-control namaAyah text-capitalize tab-1" placeholder="Cth. John Doe" required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Jenis Kelamin / <i>Gender</i></label>
                    <input type="text" readonly name="gender" value="Laki-laki"
                        class="form-control text-capitalize tab-1" placeholder="Laki-laki / Male" required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Usia / <i>Age
                        </i></label>
                    <input type="number" name="age" value=""
                        class="form-control usiaAyah text-capitalize tab-1" placeholder="Cth. 55" required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Pendidikan / <i>Education</i></label>
                    <select name="last_education" id="" class="form-control pendidikanAyah tab-1" required>
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
                    <input type="text" name="job" value=""
                        class="form-control pekerjaanAyah text-capitalize tab-1" placeholder="Cth. Guru" required>
                </div>
            </div>
            {{-- ! Ibu --}}
            <div class="form-group row">
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Hub. Keluarga / <i> Relation </i></label>
                    <input type="text" name="status" readonly value="ibu"
                        class="form-control text-capitalize tab-1" placeholder="Ibu / Mother" required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Nama / <i>Name</i></label>
                    <input type="text" name="name" value=""
                        class="form-control namaIbu text-capitalize tab-1" placeholder="Cth. Katty" required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Jenis Kelamin / <i>Gender</i></label>
                    <input type="text" readonly name="gender" value="Perempuan"
                        class="form-control text-capitalize tab-1" placeholder="Perempuan / Female" required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Usia / <i>Age
                        </i></label>
                    <input type="number" name="age" value=""
                        class="form-control usiaIbu text-capitalize tab-1" placeholder="Cth. 45" required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Pendidikan / <i>Education</i></label>
                    <select name="last_education" id="" class="form-control pendidikanIbu tab-1" required>
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
                    <input type="text" name="job" value=""
                        class="form-control pekerjaanIbu text-capitalize tab-1" placeholder="Cth. Ministry" required>
                </div>
            </div>

            {{-- ! saudara --}}

            <div class="row form-group">
                <h5>Saudara Kandung & Tiri / <i>Siblings</i></h5>
                <hr class="bg-primary">
            </div>
            <div class="col-md-1 form-group">
                <button class="btn btn-sm btn-primary form-control text-white" id="addBrother"
                    type="button">+</button>
            </div>
            <div id="formBrother">
                <div class="row form-group  py-2 border border-primary rounded"
                    style="color: black;background-color: #eedd86;">
                    <input type="hidden" class="index" value="1">
                    <div class="col-md-5 form-group">
                        <label><span class="text-danger">* </span> Urutan Saudara / <i> Sibling Order </i></label>
                        <input type="text" name="formBrother[0][status]" readonly value="Urutan ke-1"
                            class="form-control text-capitalize tab-1" placeholder="Cth. John Doe" required>
                    </div>
                    <div class="col-md-5 form-group">
                        <label><span class="text-danger">* </span> Nama / <i>Name</i></label>
                        <input type="text" name="formBrother[0][name]"
                            class="form-control namaSaudara text-capitalize tab-1" placeholder="Cth. Katty" required>
                    </div>
                    <div class="col-md-2 form-group">
                        <label><span class="text-danger">* </span> Jenis Kelamin / <i>Gender</i></label>
                        <select name="formBrother[0][gender]" required id=""
                            class="form-control jenisKelaminSaudara tab-1">
                            <option value="" selected>--Pilih / Select--</option>
                            <option value="Laki-laki">Laki-laki / <i>Male</i></option>
                            <option value="Perempuan">Perempuan / <i>Female</i></option>

                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label><span class="text-danger">* </span> Usia / <i>Age
                            </i></label>
                        <input type="number" name="formBrother[0][age]" value=""
                            class="form-control usiaSaudara text-capitalize tab-1" placeholder="45" required>
                    </div>
                    <div class="col-md-5 form-group">
                        <label><span class="text-danger">* </span> Pendidikan / <i>Education</i></label>
                        <select name="formBrother[0][last_education]" id=""
                            class="form-control pendidikanSaudara tab-1" required>
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
                            class="form-control pekerjaanSaudara text-capitalize tab-1" placeholder="Cth. Ministry"
                            required>
                    </div>
                </div>
            </div>

            {{-- ! suami dan istri --}}
            <div id="single" hidden>

                <div class="row form-group">
                    <h5>Keluarga Inti / <i>Main Family</i></h5>
                    <hr class="bg-primary">
                </div>
                {{-- ! --}}

                <div class="form-group row">
                    <div class="col-md-5 form-group">
                        <label><span class="text-danger">* </span> Hub. Keluarga / <i> Relation </i></label>
                        <input type="text" name="-" readonly value="Suami-Istri / Husband-Wife"
                            class="form-control statusSi text-capitalize" placeholder="Suami-Istri / Husband-Wife">
                    </div>
                    <div class="col-md-5 form-group">
                        <label><span class="text-danger">* </span> Nama dfgdgdg / <i>Name</i></label>
                        <input type="text" name="" value="-" class="form-control nSi text-capitalize"
                            placeholder="Cth. Katty">
                    </div>
                    <div class="col-md-2 form-group">
                        <label><span class="text-danger">* </span> Jenis Kelamin / <i>Gender</i></label>
                        <select name="" id="" class="form-control jkSi">
                            <option value="-" selected>--Pilih / Select --</option>
                            <option value="Laki-laki">Laki-laki / <i>Male</i></option>
                            <option value="Perempuan">Perempuan / <i>Female</i></option>

                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label><span class="text-danger">* </span> Usia / <i>Age
                            </i></label>
                        <input type="number" name="" value="0"
                            class="form-control ageSi text-capitalize" placeholder="45">
                    </div>
                    <div class="col-md-5 form-group">
                        <label><span class="text-danger">* </span> Pendidikan / <i>Education</i></label>
                        <select name="" id="" class="form-control eduSi">
                            <option value="-" selected>--Pilih / Select--</option>
                            <option value="Strata-2">Strata-2</option>
                            <option value="Strata-1">Strata-1</option>
                            <option value="SMA/SMK/MA">SMA/SMK/MA</option>
                            <option value="SMP/MTs">SMP/MTs</option>
                            <option value="SD/MI">SD/MI</option>
                        </select>
                    </div>
                    <div class="col-md-5 form-group">
                        <label><span class="text-danger">* </span> Pekerjaan / <i>Proffesion</i></label>
                        <input type="text" name="" value="-"
                            class="form-control jobSi text-capitalize" placeholder="Cth. Ministry">
                    </div>
                </div>
                <div class="col-md-1 form-group">
                    <button class="btn btn-sm btn-primary form-control text-white" id="addChild"
                        type="button">+</button>
                </div>
                <div id="formChild">
                    <div class="row form-group py-2 border border-primary rounded" style="background-color: #3db39d"
                        style="color: black">
                        <input type="hidden" class="indexes" value="1">

                        <div class="col-md-5 form-group">
                            <label><span class="text-danger">* </span> Urutan Anak / <i> Child Order </i></label>
                            <input type="text" name="formChild[0][status]" readonly value="Urutan ke-1"
                                class="form-control statusCh text-capitalize" placeholder="Cth. John Doe">
                        </div>
                        <div class="col-md-5 form-group">
                            <label><span class="text-danger">* </span> Nama / <i>Name</i></label>
                            <input type="text" name="formChild[0][name]" value="-"
                                class="form-control nCh text-capitalize" placeholder="Cth. Roger">
                        </div>
                        <div class="col-md-2 form-group">
                            <label><span class="text-danger">* </span> Jenis Kelamin / <i>Gender</i></label>
                            <select name="formChild[0][gender]" id="" class="form-control jkCh">
                                <option value="-" selected>-- Pilih / Select --</option>
                                <option value="Laki-laki">Laki-laki / <i>Male</i></option>
                                <option value="Perempuan">Perempuan / <i>Female</i></option>

                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label><span class="text-danger">* </span> Usia / <i>Age
                                </i></label>
                            <input type="number" name="formChild[0][age]" value="0"
                                class="form-control ageCh text-capitalize" placeholder="45">
                        </div>
                        <div class="col-md-5 form-group">
                            <label><span class="text-danger">* </span> Pendidikan / <i>Education</i></label>
                            <select name="formChild[0][last_education]" id="" class="form-control eduCh">
                                <option value="-" selected>--Pilih / Select--</option>
                                <option value="Strata-2">Strata-2</option>
                                <option value="Strata-1">Strata-1</option>
                                <option value="SMA/SMK/MA">SMA/SMK/MA</option>
                                <option value="SMP/MTs">SMP/MTs</option>
                                <option value="SD/MI">SD/MI</option>
                            </select>
                        </div>
                        <div class="col-md-5 form-group">
                            <label><span class="text-danger">* </span> Pekerjaan / <i>Proffesion</i></label>
                            <input type="text" name="formChild[0][job]" value="-"
                                class="form-control jobCh text-capitalize" placeholder="Cth. Ministry">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
