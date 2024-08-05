<div class="ribbon-wrapper card">
    <div class="card-body">
        <div class="ribbon ribbon-bookmark ribbon-primary">Data Pendidikan & Data Pengalaman / <i>Educational Data and
                Experience Data</i></div>
        <input type="hidden" value="{{ url()->current() }}" name="link">

        {{-- ! SEKOLAH --}}
        <div class="row font-weight-bold">
            {{-- <input type="hidden" value="{{ url()->current() }}" name="link"> --}}

            {{-- ! sekolah 1 --}}
            <div class="form-group row">
                <h5>Riwayat Pendidikan / <i>Educational Background
                    </i></h5>
                <hr class="bg-primary">
                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="background-color: #3db39d" type="text" name="" readonly value="1"
                        class="form-control text-center text-white text-capitalize tab-2" placeholder="Ayah / Father"
                        required>
                </div>
                <div class="col-md-6 form-group">
                    <label><span class="text-danger">* </span> Nama Sekolah / <i>School Name</i></label>
                    <input type="text" name="" value=""
                        class="form-control namaSekolah_1 text-capitalize tab-2" placeholder="Cth. SMA Harapan Bangsa"
                        required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Jurusan / <i>Study Program</i></label>
                    <input type="text" name="" value=""
                        class="form-control jurusanSekolah_1 text-capitalize tab-2" placeholder="Cth. Manajemen Bisnis"
                        required>
                </div>
                <div class="col-md-7 form-group">
                    <label><span class="text-danger">* </span> Alamat / <i>Address
                        </i></label>
                    <input type="text" name="" value=""
                        class="form-control alamatSekolah_1 text-capitalize tab-2" placeholder="Cth. Jl Protokol"
                        required>
                </div>

                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Tahun Lulus / <i>Graduation Year</i></label>
                    <input type="number" data-v-min-length="4" data-v-max-length="4" name="job" value=""
                        class="form-control tahunLulusSekolah_1 text-capitalize tab-2" placeholder="Cth. 2016" required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> IPK atau Nilai / <i>GPS</i></label>
                    <input type="text" name="job" value=""
                        class="form-control nilaiRataRataSekolah_1 text-capitalize tab-2" placeholder="Cth. 3.80"
                        required>
                </div>
            </div>
            {{-- ! sekolah 2 --}}
            <div class="form-group row">

                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="background-color: #3db39d" type="text" name="" readonly value="2"
                        class="form-control text-center text-white text-capitalize tab-2" placeholder="Ayah / Father"
                        required>
                </div>
                <div class="col-md-6 form-group">
                    <label><span class="text-danger">* </span> Nama Sekolah / <i>School Name</i></label>
                    <input type="text" name="" value=""
                        class="form-control namaSekolah_2 text-capitalize tab-2" placeholder="Cth. SMA Harapan Bangsa"
                        required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Jurusan / <i>Study Program</i></label>
                    <input type="text" name="" value=""
                        class="form-control jurusanSekolah_2 text-capitalize tab-2" placeholder="Cth. Manajemen Bisnis"
                        required>
                </div>
                <div class="col-md-7 form-group">
                    <label><span class="text-danger">* </span> Alamat / <i>Address
                        </i></label>
                    <input type="text" name="" value=""
                        class="form-control alamatSekolah_2 text-capitalize tab-2" placeholder="Cth. Jl Protokol"
                        required>
                </div>

                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Tahun Lulus / <i>Graduation Year</i></label>
                    <input type="number" data-v-min-length="4" data-v-max-length="4" name="job" value=""
                        class="form-control tahunLulusSekolah_2 text-capitalize tab-2" placeholder="Cth. 2016" required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> IPK atau Nilai / <i>GPS</i></label>
                    <input type="text" name="job" value=""
                        class="form-control nilaiRataRataSekolah_2 text-capitalize tab-2" placeholder="Cth. 3.80"
                        required>
                </div>
            </div>
            {{-- ! sekolah 3 --}}
            <div class="form-group row">

                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="background-color: #3db39d" type="text" name="" readonly value="3"
                        class="form-control text-center text-white text-capitalize tab-2" placeholder="Ayah / Father"
                        required>
                </div>
                <div class="col-md-6 form-group">
                    <label><span class="text-danger">* </span> Nama Sekolah / <i>School Name</i></label>
                    <input type="text" name="" value=""
                        class="form-control namaSekolah_3 text-capitalize tab-2" placeholder="Cth. SMA Harapan Bangsa"
                        required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Jurusan / <i>Study Program</i></label>
                    <input type="text" name="" value=""
                        class="form-control jurusanSekolah_3 text-capitalize tab-2"
                        placeholder="Cth. Manajemen Bisnis" required>
                </div>
                <div class="col-md-7 form-group">
                    <label><span class="text-danger">* </span> Alamat / <i>Address
                        </i></label>
                    <input type="text" name="" value=""
                        class="form-control alamatSekolah_3 text-capitalize tab-2" placeholder="Cth. Jl Protokol"
                        required>
                </div>

                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Tahun Lulus / <i>Graduation Year</i></label>
                    <input type="number" data-v-min-length="4" data-v-max-length="4" name="job" value=""
                        class="form-control tahunLulusSekolah_3 text-capitalize tab-2" placeholder="Cth. 2016"
                        required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> IPK atau Nilai / <i>GPS</i></label>
                    <input type="text" name="job" value=""
                        class="form-control nilaiRataRataSekolah_3 text-capitalize tab-2" placeholder="Cth. 3.80"
                        required>
                </div>
            </div>
        </div>

        {{-- ! PEKERJAAN --}}
        <div class="row font-weight-bold">
            {{-- ! job 1 --}}
            <div class="form-group row">
                <h5>Riwayat Pekerjaan / <i>Job Experiences
                    </i></h5>
                <hr class="bg-primary">
                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="color: black;background-color: #eedd86;" type="text" name="" readonly
                        value="1" class="form-control text-center text-white text-capitalize tab-2"
                        placeholder="Ayah / Father" required>
                </div>
                <div class="col-md-6 form-group">
                    <label><span class="text-danger">* </span> Perusahaan / <i>Company</i></label>
                    <input type="text" name="" value=""
                        class="form-control namaPerusahaan_1 text-capitalize tab-2"
                        placeholder="Cth. PT Harapan Bangsa" required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Jabatan / <i>Position</i></label>
                    <input type="text" name="" value=""
                        class="form-control jabatanPerusahaan_1 text-capitalize tab-2" placeholder="Cth. Admin Sales "
                        required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Bulan / <i>Month
                        </i></label>
                    <input type="number" data-v-min-length="1" data-v-max-length="2" name="" value=""
                        class="form-control bulanMasukPerusahaan_1 text-capitalize tab-2" placeholder="Cth. 05"
                        required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Tahun / <i>Year
                        </i></label>
                    <input type="number" name="" value=""
                        class="form-control tahunMasukPerusahaan_1 text-capitalize tab-2" placeholder="Cth. 02"
                        required>
                </div>
                <div class="col-md-4 form-group">
                    <label><span class="text-danger">* </span> Gaji / <i>Salary
                        </i></label>
                    <input type="text" name="" value=""
                        class="form-control gajiPerusahaan_1 text-capitalize tab-2" placeholder="Cth. 500.000"
                        required>
                </div>

                <div class="col-md-4 form-group">
                    <label><span class="text-danger">* </span> Alasan Berhenti / <i>Reason of Quit</i></label>
                    <input type="text" name="job" value=""
                        class="form-control alasanKeluarPerusahaan_1 text-capitalize tab-2"
                        placeholder="Cth. Gaji tidak naik" required>
                </div>

            </div>
            {{-- ! job 2 --}}
            <div class="form-group row">

                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="color: black;background-color: #eedd86;" type="text" name="" readonly
                        value="2" class="form-control text-center text-white text-capitalize tab-2"
                        placeholder="Ayah / Father" required>
                </div>
                <div class="col-md-6 form-group">
                    <label><span class="text-danger">* </span> Perusahaan / <i>Company</i></label>
                    <input type="text" name="" value=""
                        class="form-control namaPerusahaan_2 text-capitalize tab-2"
                        placeholder="Cth. PT Harapan Bangsa" required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Jabatan / <i>Position</i></label>
                    <input type="text" name="" value=""
                        class="form-control jabatanPerusahaan_2 text-capitalize tab-2" placeholder="Cth. Admin Sales "
                        required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Bulan / <i>Month
                        </i></label>
                    <input type="number" data-v-min-length="1" data-v-max-length="2" name="" value=""
                        class="form-control bulanMasukPerusahaan_2 text-capitalize tab-2" placeholder="Cth. 05"
                        required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Tahun / <i>Year
                        </i></label>
                    <input type="number" name="" value=""
                        class="form-control tahunMasukPerusahaan_2 text-capitalize tab-2" placeholder="Cth. 02"
                        required>
                </div>
                <div class="col-md-4 form-group">
                    <label><span class="text-danger">* </span> Gaji / <i>Salary
                        </i></label>
                    <input type="text" name="" value=""
                        class="form-control gajiPerusahaan_2 text-capitalize tab-2" placeholder="Cth. 500.000"
                        required>
                </div>

                <div class="col-md-4 form-group">
                    <label><span class="text-danger">* </span> Alasan Berhenti / <i>Reason of Quit</i></label>
                    <input type="text" name="job" value=""
                        class="form-control alasanKeluarPerusahaan_2 text-capitalize tab-2"
                        placeholder="Cth. Gaji tidak naik" required>
                </div>

            </div>
            {{-- ! job 3 --}}
            <div class="form-group row">

                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="color: black;background-color: #eedd86;" type="text" name="" readonly
                        value="3" class="form-control text-center text-white text-capitalize tab-2"
                        placeholder="Ayah / Father" required>
                </div>
                <div class="col-md-6 form-group">
                    <label><span class="text-danger">* </span> Perusahaan / <i>Company</i></label>
                    <input type="text" name="" value=""
                        class="form-control namaPerusahaan_3 text-capitalize tab-2"
                        placeholder="Cth. PT Harapan Bangsa" required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Jabatan / <i>Position</i></label>
                    <input type="text" name="" value=""
                        class="form-control jabatanPerusahaan_3 text-capitalize tab-2" placeholder="Cth. Admin Sales "
                        required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Bulan / <i>Month
                        </i></label>
                    <input type="number" data-v-min-length="1" data-v-max-length="2" name="" value=""
                        class="form-control bulanMasukPerusahaan_3 text-capitalize tab-2" placeholder="Cth. 05"
                        required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Tahun / <i>Year
                        </i></label>
                    <input type="number" name="" value=""
                        class="form-control tahunMasukPerusahaan_3 text-capitalize tab-2" placeholder="Cth. 02"
                        required>
                </div>
                <div class="col-md-4 form-group">
                    <label><span class="text-danger">* </span> Gaji / <i>Salary
                        </i></label>
                    <input type="text" name="" value=""
                        class="form-control gajiPerusahaan_3 text-capitalize tab-2" placeholder="Cth. 500.000"
                        required>
                </div>

                <div class="col-md-4 form-group">
                    <label><span class="text-danger">* </span> Alasan Berhenti / <i>Reason of Quit</i></label>
                    <input type="text" name="job" value=""
                        class="form-control alasanKeluarPerusahaan_3 text-capitalize tab-2"
                        placeholder="Cth. Gaji tidak naik" required>
                </div>

            </div>

        </div>

        {{-- ! REFRENSI --}}
        <div class="row font-weight-bold">
            {{-- ! ref 1 --}}
            <div class="form-group row">
                <h5>Referensi / <i>Reference
                    </i></h5>
                <hr class="bg-primary">
                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="color: black;background-color: #dd5f6c;" type="text" name="" readonly
                        value="1" class="form-control text-center text-white text-capitalize tab-2"
                        placeholder="Ayah / Father" required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Nama / <i>Name</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Freddy Kurnia " required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Alamat / <i>Address</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Jl Rajawali " required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> HP / <i>Number Phone
                        </i></label>
                    <input type="number" data-v-min-length="8" data-v-max-length="13" name=""
                        value="" class="form-control text-capitalize tab-2" placeholder="Cth. 082395458921"
                        required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Hubungan / <i>Relation
                        </i></label>
                    <input type="text" name="" value="" class="form-control text-capitalize tab-2"
                        placeholder="Cth. Manajer PT ABC" required>
                </div>


            </div>
            {{-- ! ref 1 --}}
            <div class="form-group row">
                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="color: black;background-color: #dd5f6c;" type="text" name="" readonly
                        value="2" class="form-control text-center text-white text-capitalize tab-2"
                        placeholder="Ayah / Father" required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Nama / <i>Name</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Freddy Kurnia " required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Alamat / <i>Address</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Jl Rajawali " required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> HP / <i>Number Phone
                        </i></label>
                    <input type="number" data-v-min-length="8" data-v-max-length="13" value=""
                        class="form-control text-capitalize tab-2" placeholder="Cth. 082395458921" required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Hubungan / <i>Relation
                        </i></label>
                    <input type="text" name="" value="" class="form-control text-capitalize tab-2"
                        placeholder="Cth. Manajer PT ABC" required>
                </div>


            </div>


        </div>
        {{-- ! ORGANISASI --}}
        <div class="row font-weight-bold">
            {{-- ! org 1 --}}
            <div class="form-group row">
                <h5>Organization / <i>Organisasi
                    </i></h5>
                <hr class="bg-primary">
                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="color: black;background-color: #f16696;" type="text" name="" readonly
                        value="1" class="form-control text-center text-white text-capitalize tab-2"
                        placeholder="Ayah / Father" required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Nama Organisasi / <i>Organization Name</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Bank Indonesia " required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Jenis Organisai / <i>Type of Organization</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Prestasi Akademik " required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Jabatan / <i>Position
                        </i></label>
                    <input type="text" name="" value="" class="form-control text-capitalize tab-2"
                        placeholder="Cth. Ketua" required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Tahun / <i>Year
                        </i></label>
                    <input type="number" data-v-min-length="4" data-v-max-length="4" name="" value=""
                        class="form-control text-capitalize tab-2" placeholder="Cth. 2020" required>
                </div>
            </div>
            {{-- ! org 2 --}}
            <div class="form-group row">

                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="color: black;background-color: #f16696;" type="text" name="" readonly
                        value="2" class="form-control text-center text-white text-capitalize tab-2"
                        placeholder="Ayah / Father" required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Nama Organisasi / <i>Organization Name</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Bank Indonesia " required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Jenis Organisai / <i>Type of Organization</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Prestasi Akademik " required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Jabatan / <i>Position
                        </i></label>
                    <input type="text" name="" value="" class="form-control text-capitalize tab-2"
                        placeholder="Cth. Ketua" required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Tahun / <i>Year
                        </i></label>
                    <input type="number" data-v-min-length="4" data-v-max-length="4" name="" value=""
                        class="form-control text-capitalize tab-2" placeholder="Cth. 2020" required>
                </div>
            </div>
            {{-- ! org 3 --}}
            <div class="form-group row">

                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="color: black;background-color: #f16696;" type="text" name="" readonly
                        value="3" class="form-control text-center text-white text-capitalize tab-2"
                        placeholder="Ayah / Father" required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Nama Organisasi / <i>Organization Name</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Bank Indonesia " required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Jenis Organisai / <i>Type of Organization</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Prestasi Akademik " required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Jabatan / <i>Position
                        </i></label>
                    <input type="text" name="" value="" class="form-control text-capitalize tab-2"
                        placeholder="Cth. Ketua" required>
                </div>
                <div class="col-md-3 form-group">
                    <label><span class="text-danger">* </span> Tahun / <i>Year
                        </i></label>
                    <input type="number" name="" data-v-min-length="4" data-v-max-length="4" value=""
                        class="form-control text-capitalize tab-2" placeholder="Cth. 2020" required>
                </div>
            </div>
        </div>

        {{-- ! KURSUS --}}
        <div class="row font-weight-bold">
            {{-- ! kurusus 1 --}}
            <div class="form-group row">
                <h5>Course & Seminars / <i>Kursus & Seminar
                    </i></h5>
                <hr class="bg-primary">
                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="color: black;background-color: #cca888;" type="text" name="" readonly
                        value="1" class="form-control text-center text-white text-capitalize tab-2"
                        placeholder="Ayah / Father" required>
                </div>

                <div class="col-md-4 form-group">
                    <label><span class="text-danger">* </span> Jenis Kursus & Seminar / <i>Type of Course &
                            Seminar</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Prestasi Akademik " required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Penyelenggara / <i>Organizer</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Bank Indonesia " required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Tahun / <i>Year
                        </i></label>
                    <input type="number" data-v-min-length="4" data-v-max-length="4" name="" value=""
                        class="form-control text-capitalize tab-2" placeholder="Cth. 2019" required>
                </div>

            </div>
            {{-- ! kurusus 2 --}}
            <div class="form-group row">

                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="color: black;background-color: #cca888;" type="text" name="" readonly
                        value="2" class="form-control text-center text-white text-capitalize tab-2"
                        placeholder="Ayah / Father" required>
                </div>

                <div class="col-md-4 form-group">
                    <label><span class="text-danger">* </span> Jenis Kursus & Seminar / <i>Type of Course &
                            Seminar</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Prestasi Akademik " required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Penyelenggara / <i>Organizer</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Bank Indonesia " required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Tahun / <i>Year
                        </i></label>
                    <input type="number" data-v-min-length="4" data-v-max-length="4" name="" value=""
                        class="form-control text-capitalize tab-2" placeholder="Cth. 2019" required>
                </div>

            </div>
            {{-- ! kurusus 3 --}}
            <div class="form-group row">

                <div class="col-md-1 form-group">
                    <label><span class="text-danger">* </span> No / <i> No </i></label>
                    <input style="color: black;background-color: #cca888;" type="text" name="" readonly
                        value="3" class="form-control text-center text-white text-capitalize tab-2"
                        placeholder="Ayah / Father" required>
                </div>

                <div class="col-md-4 form-group">
                    <label><span class="text-danger">* </span> Jenis Kursus & Seminar / <i>Type of Course &
                            Seminar</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Prestasi Akademik " required>
                </div>
                <div class="col-md-5 form-group">
                    <label><span class="text-danger">* </span> Penyelenggara / <i>Organizer</i></label>
                    <input type="text" name="" value="" class="form-control  text-capitalize tab-2"
                        placeholder="Cth. Bank Indonesia " required>
                </div>
                <div class="col-md-2 form-group">
                    <label><span class="text-danger">* </span> Tahun / <i>Year
                        </i></label>
                    <input type="number" data-v-min-length="4" data-v-max-length="4" name="" value=""
                        class="form-control text-capitalize tab-2" placeholder="Cth. 2019" required>
                </div>

            </div>

        </div>
    </div>
</div>
