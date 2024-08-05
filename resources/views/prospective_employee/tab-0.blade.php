<div class="ribbon-wrapper card">
    <div class="card-body">
        <div class="ribbon ribbon-bookmark ribbon-primary">Data Pribadi / <i>Personal
                Data</i></div>
        <input type="hidden" value="{{ url()->current() }}" name="link">
        <div class="row font-weight-bold">
            <input type="hidden" value="{{ url()->current() }}" name="link">


            <div class="form-group row">
                {{-- ! nama --}}
                <div class="col-md-6 form-group">
                    <label><span class="text-danger">* </span> Nama Lengkap / <i>Full
                            Name</i></label>
                    <input type="text" name="name" value="" class="form-control nama text-capitalize tab-0"
                        placeholder="Cth. John Doe" required>
                </div>
                {{-- ! jenis kelamin --}}
                <div class="col-md-6 form-group">
                    <label><span class="text-danger">* </span>Jenis Kelamin / <i>Choose
                            Gender</i></label><br>
                    <input required type="radio" class="form-check-input-jk tab-0" name="gender" value="male">
                    Laki-laki / <i>Male</i>
                    <br>
                    <input required type="radio" class="form-check-input-jk tab-0" name="gender" value="female">
                    Wanita / <i>Female</i>
                </div>

                {{-- ! Tempat Lahir --}}
                <div class="col-md-6 form-group">
                    <label><span class="text-danger">* </span>Tempat Lahir / <i>Place of
                            Birth</i></label>
                    </label>
                    <input type="text" value="" name="place_of_birth"
                        class="form-control  tab-0 text-capitalize tempat_lahir" placeholder="Cth. Palembang" required>
                </div>

                {{-- ! Tanggal Lahir --}}
                <div class="col-md-6 form-group">
                    <label><span class="text-danger">* </span>Tanggal Lahir / <i>Date of
                            Birth</i></label>
                    <input required type="date" value="" name="date_of_birth"
                        class="tanggal_lahir form-control tab-0" placeholder="Date of Birth">
                </div>

                {{-- ! tinggi --}}
                <div class="col-md-4 form-group">
                    <label><span class="text-danger">* </span>Tinggi Badan (cm) /
                        <i>Height (cm)</i></label>
                    <input required type="number" value="" name="height" class="tinggi form-control tab-0"
                        placeholder="Cth. 165">
                </div>

                {{-- ! berat --}}
                <div class="col-md-4 form-group">
                    <label><span class="text-danger">* </span>Berat Badan (kg) / <i>Weight
                            (kg)</i></label>
                    <input required type="number" name="weight" value="" class="berat form-control tab-0"
                        placeholder="Cth. 60">
                </div>

                {{-- ! status kawin --}}
                <div class="col-md-4 form-group">
                    <label><span class="text-danger">* </span>Status Marital / <i>Status
                            Perkawinan</i></label><br>
                    <input required type="radio" class="form-check-input-marital tab-0" name="status_marital"
                        value="single">
                    Single / <i>Belum Menikah</i>
                    <br>
                    <input required type="radio" class="form-check-input-marital tab-0" name="status_marital"
                        value="married" }>
                    Married / <i>Sudah Menikah</i>
                    <br>
                    <input required type="radio" class="form-check-input-marital tab-0" name="status_marital"
                        value="disvorced">
                    Cerai / <i>Disvorced</i>
                </div>

                {{-- ! ktp --}}
                <div class="col-md-6 form-group">
                    <label><span class="text-danger">* </span>Nomor Kartu Identitas (KTP)
                        / <i>Identity Card Number (ID
                            Card)</i></label>
                    <input required value="" type="number" data-v-min-length="16" data-v-max-length="16"
                        name="card_id" class="ktp form-control tab-0" placeholder="Cth. 16740320015980001">
                </div>

                {{-- ! sim --}}
                <div class="col-md-6 form-group">
                    <label>Surat Izin Mengemudi (SIM) /
                        <i>Driver's License
                            (SIM)</i></label>

                    <div class="checkbox checkbox-primary">
                        <input id="cek1" type="checkbox" value="cb1">
                        <label for="cek1">SIM A</label>
                        <input name="sim_a" placeholder="Cth. 980511786549" required type="text" value=""
                            hidden class="cek1-form form-control tab-0">
                    </div>
                    <div class="checkbox checkbox-primary">
                        <input id="cek2" type="checkbox" value="cb2">
                        <label for="cek2">SIM B</label>
                        <input name="sim_b" placeholder="Cth. 980511786549" required type="text" value=""
                            hidden class="cek2-form form-control tab-0">
                    </div>
                    <div class="checkbox checkbox-primary">
                        <input id="cek3" type="checkbox" value="cb3">
                        <label for="cek3">SIM C</label>
                        <input name="sim_c" placeholder="Cth. 980511786549" required type="text" value=""
                            hidden class="cek3-form form-control tab-0">
                    </div>
                </div>

                {{-- ! alamat ktp --}}
                <div class="form-group col-md-12">
                    <label for=""><span class="text-danger">* </span>Alamat /
                        <i>Address</i></label>
                    <textarea required name="address" class="alamatKtp form-control tab-0" id="" cols="30" rows="5"
                        placeholder="Cth. Jalan Kemuning"></textarea>
                </div>

                {{-- ! alamat skrg --}}
                <div class="form-group col-md-12">
                    <label for="">Alamat Sekarang <small class="text-danger">*tidak
                            perlu diisi jika sama dengan
                            KTP</small> / <i>Current Address <small class="text-danger">*
                                No need to fill in if same as the identity
                                above</small></i> </label>
                    <textarea name="address1" class="alamatskrg form-control tab-0" id="" cols="30" rows="5"
                        placeholder="Cth. Jalan Kemuning"></textarea>
                </div>

                {{-- ! status rumah --}}
                <div class="col-md-6 form-group">
                    <label><span class="text-danger">* </span>Status Rumah Yang
                        Ditinggali / <i>Residential Status
                        </i></label><br>
                    <input required type="radio" class="form-check-input cek-rumah tab-0" name="residential_status"
                        value="sendiri">
                    Milik Sendiri / <i>One's own</i>
                    <br>
                    <input required type="radio" class="form-check-input cek-rumah tab-0" name="residential_status"
                        value="ortu">
                    Orang Tua / <i>Parents's</i>
                    <br>
                    <input required type="radio" class="form-check-input cek-rumah tab-0" name="residential_status"
                        value="kantor">
                    Kantor / <i>Office's</i>
                    <br>
                    <input required type="radio" class="form-check-input cek-other cek-rumah tab-0"
                        name="residential_status" value="other">
                    Lain-lain / <i>Other</i>
                    <input value="" type="text" class="form-control form-rumah tab-0" hidden required
                        name="other_residential" placeholder="Cth. Kontrak">
                </div>

                {{-- ! status kendaraan --}}
                <div class="col-md-6 form-group">
                    <label>Jenis Kendaraan / <i>Vehicle Ownership Status
                        </i></label><br>
                    <input type="text" value="" hidden class="form-control form-kendaraan tab-0"
                        name="kendaraan" placeholder="Cth. Yamaha Vixion" required>

                    <input required type="radio" class="form-check-input cek-kendaraan tab-0" name="vehicle"
                        value="sendiri">
                    Milik Sendiri / <i>One's own</i>
                    <br>
                    <input required type="radio" class="form-check-input cek-kendaraan tab-0" name="vehicle"
                        value="ortu">
                    Orang Tua / <i>Parents's</i>
                    <br>
                    <input required type="radio" class="form-check-input cek-kendaraan tab-0" name="vehicle"
                        value="kantor">
                    Kantor / <i>Office's</i>
                </div>

                {{-- ! hp --}}
                <div class="form-group col-md-6">
                    <label><span class="text-danger">* </span>Nomor HP(1) / <i>Number
                            Phone(1)</i></label>
                    <input type="number" value="" name="phone_number" data-v-min-length="8"
                        data-v-max-length="13" class="form-control phone text-capitalize tab-0"
                        placeholder="Cth. 081234567891" required>
                </div>

                {{-- ! hp2 --}}
                <div class="form-group col-md-6">
                    <label>Nomor HP(2) / <i>Number Phone(2)</i></label>
                    <input type="number" value="" name="phone_number1"
                        class="form-control phone2 text-capitalize tab-0" placeholder="Cth. 081234567891">
                </div>

                {{-- ! kontak darurat --}}
                <div class="form-group col-md-6">
                    <label><span class="text-danger">* </span>Kontak Darurat / <i>Emergency
                            Contact
                        </i></label>
                    <input value="" type="number" name="e_contact" data-v-min-length="8"
                        data-v-max-length="13" class="form-control e_contact text-capitalize tab-0"
                        placeholder="Cth. 081234567891" required>
                </div>
                {{-- ! relasi kontak --}}
                <div class="form-group col-md-6">
                    <label><span class="text-danger">* </span>Relationship / <i>Hubungan
                        </i></label>
                    <select name="relation" id="" class="form-control relation select2 tab-0" required>
                        <option value="" selected>--Choose Relation--</option>
                        <option value="Ayah">Ayah / <i>Father</i>
                        </option>
                        <option value="Ibu">Ibu / <i>Mother</i>
                        </option>
                        <option value="Kakak">Kakak /
                            <i>Brother</i>
                        </option>
                        <option value="Adik">Adik / <i>Sister</i>
                        </option>
                        <option value="Suami">Suami /
                            <i>Husband</i>
                        </option>
                        <option value="Istri">Istri / <i>Wife</i>
                        </option>
                    </select>
                </div>

                {{-- ! email --}}
                <div class="form-group col-md-12">
                    <label><span class="text-danger">* </span>Alamat Surel / <i>Email
                        </i></label>
                    <input type="email" value="" name="email" class="form-control email tab-0"
                        placeholder="Cth. jhoendo@mail.com" required>
                </div>






            </div>
        </div>
    </div>
</div>
