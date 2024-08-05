<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="logo-wrapper text-start mt-3" style="margin-left: 30px">
                    <a href="index.html"><img class="img-fluid" style="width: 150px" src="{{ asset('images/logos.png') }}"
                            alt=""></a>
                </div>
                <div class="card-header pb-0">
                    <h5>Data Calon Karyawan / <i>Candidate Employee Data</i></h5>
                    <span> Wajib Diisi <span class="text-danger">(*) </span>/ <i>Form
                            Required <span class="text-danger">(*) </span> </i>
                    </span>
                </div>
                <div class="card-body">
                    <form class="form-wizard needs-validation" action="{{ url('candidate_employee/store_form/') }}"
                        novalidate id="regForm" method="POST">
                        @csrf
                        @method('POST')
                        <input type="text" id="currenTab">
                        <div class="tab">
                            @include('prospective_employee.tab-0')
                        </div>
                        <div class="tab">
                            @include('prospective_employee.tab-1')
                        </div>
                        <div class="tab">
                            @include('prospective_employee.tab-2')
                        </div>
                        <div class="tab">
                            @include('prospective_employee.tab-3')
                        </div>

                        <div>

                            <div class="text-end btn-mb">
                                {{-- button save --}}
                                <button class="btn btn-secondary" id="prevBtn" type="button"
                                    onclick="nextPrev(-1)">Previous</button>
                                <button class="btn btn-primary" disabled id="nextBtn" type="button"
                                    onclick="nextPrev(1)">Next</button>
                            </div>
                        </div>
                        <!-- Circles which indicates the steps of the form:-->
                        <div class="text-center"><span class="step"></span><span class="step"></span><span
                                class="step"></span><span class="step"></span></div>
                        <!-- Circles which indicates the steps of the form:-->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
