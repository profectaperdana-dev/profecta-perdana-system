<script>
    $(document).on('change', '.vehicle', function() {
        let vehicle = $(this).val();
        console.log(vehicle);

        if (vehicle && vehicle.length > 0) {
            if (vehicle[0].includes('Granmax')) {
                $('#modalVehicle').modal('show');
                $('#granmax').attr('hidden', false);
                $('#mobilio').attr('hidden', true);
                // canvasVehicle.clear();
            } else if (vehicle[0].includes('Mobilio')) {
                console.log('mobilio');
                $('#modalVehicle').modal('show');

                $('#mobilio').attr('hidden', false);
                $('#granmax').attr('hidden', true);
                // canvas.clear();
            }
        } else {
            $('#mobilio').attr('hidden', true);
            $('#granmax').attr('hidden', true);
        }
    });

    var mobilGranmax = document.getElementById('mobil1').value;
    const canvasGranmax = new fabric.Canvas('canvasVehicle');
    fabric.Image.fromURL(mobilGranmax, function(img) {
        canvasGranmax.setDimensions({
            width: img.width,
            height: img.height
        });
        canvasGranmax.setBackgroundImage(img, canvasGranmax.renderAll.bind(canvasGranmax));
    });
    canvasGranmax.isDrawingMode = true;
    canvasGranmax.freeDrawingBrush.width = 0;
    const colorsGranmax = [
        '#FF0000',
        
    ];
    let currentColorIndexGranmax = 0;
    // Buat elemen temporary canvas
    let tempCanvasGranmax = document.createElement('canvas');
    tempCanvasGranmax.width = canvasGranmax.width;
    tempCanvasGranmax.height = canvasGranmax.height;
    let tempContextGranmax = tempCanvasGranmax.getContext('2d');
    let granmaxNumber = 1;

    canvasGranmax.on('path:created', function(e) {
        const path = e.path;
        const color = colorsGranmax[currentColorIndexGranmax];
        const notes = prompt('Enter notes for this annotation :');
        path.set('notes', notes);
        path.set('stroke', color);
        currentColorIndexGranmax = (currentColorIndexGranmax + 1) % colorsGranmax.length;
        const annotations = canvasGranmax.getObjects();
        let annotationsHTML = '';
        let k = 1;
        annotations.forEach(function(annotation) {

            const strokeColor = annotation.get('stroke');
            const notes = annotation.get('notes');
            if (notes != undefined) {
                let listItemHTML =
                    `<li class="notesParent mb-2">
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"
                                        style="background: white !important">
                                        <i class="fa fa-circle" style="color:${strokeColor}"></i>
                                    </span>
                                    <input type="text" class="form-control" name="formVehicle[${k}][notes]" value="${k}. ${notes}">
                                </div>
                            <input type="hidden" name="formVehicle[${k}][color]" value="${strokeColor}">
                        </li>
                    `;
                annotationsHTML += listItemHTML;
                listItemHTML = '';
                k++;
            }

        });
        // Hitung posisi tengah berdasarkan koordinat klik mouse
        const centerX = (path.left + path.width / 2);
        const centerY = (path.top + path.height / 2);
        const circle = new fabric.Circle({
            left: centerX - 15,
            top: centerY - 15,
            radius: 15, // Sesuaikan radius sesuai kebutuhan
            fill: 'rgba(0, 0, 0, 0)', // Set fill ke transparan (alpha = 0)
            stroke: color,
            strokeWidth: 5, // Sesuaikan lebar garis sesuai kebutuhan
            selectable: true
        });
        
       
        var text = new fabric.Text(String(granmaxNumber), { 
            left: centerX - 4,
            top: centerY - 7,
            fontSize: 14,
            stroke:color,
            
        });
        canvasGranmax.add(text);
        // Tambahkan lingkaran ke canvas dan hapus path
        canvasGranmax.add(circle);
        // canvasGranmax.remove(path);

        // Render ulang canvas
        canvasGranmax.renderAll();

        // Render ulang path untuk menggambarnya dengan fill dan stroke yang sesuai
        path.render(canvasGranmax.contextContainer);
        canvasGranmax.requestRenderAll();
        document.getElementById('annotationsList').innerHTML = annotationsHTML;
        // Bersihkan tempCanvasGranmax
        tempContextGranmax.clearRect(0, 0, tempCanvasGranmax.width, tempCanvasGranmax.height);
        tempContextGranmax.drawImage(canvasGranmax.getElement(), 0, 0, canvasGranmax.width, canvasGranmax
            .height);
        // Gambar ulang semua annotasi ke tempCanvasGranmax
        annotations.forEach(function(annotation) {
            annotation.render(tempContextGranmax);
        });
        // Konversi seluruh canvas menjadi data URL
        let canvasDataUrlGranmax = tempCanvasGranmax.toDataURL("image/png");
        // Set data URL ke elemen input
        let canvasDataInputGranmax = document.getElementById('canvasDataInputMobilio');
        canvasDataInputGranmax.value = canvasDataUrlGranmax;
        granmaxNumber++;
    });





    // granmax
    var mobil = document.getElementById('mobil2').value;
    const canvas = new fabric.Canvas('canvas');
    fabric.Image.fromURL(mobil, function(img) {
        canvas.setDimensions({
            width: img.width,
            height: img.height
        });
        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
        // var canvasGranmax = document.getElementById('canvas');
    });
    canvas.isDrawingMode = true;
    canvas.freeDrawingBrush.width = 1;
    const colors = [
        '#FF0000',
        
    ];
    let currentColorIndex = 0;
    let tempCanvas = document.createElement('canvas');
    tempCanvas.width = canvas.width;
    tempCanvas.height = canvas.height;
    let tempContext = tempCanvas.getContext('2d');
    let mobilioNumber =1;
    canvas.on('path:created', function(e) {
        const path = e.path;
        const color = colors[currentColorIndex];
        const notes = prompt('Enter notes for this annotation :');
        path.set('notes', notes);
        path.set('stroke', color);
        currentColorIndex = (currentColorIndex + 1) % colors.length;
        const annotations = canvas.getObjects();
        let annotationsHTML = '';
        let t = 0;
        annotations.forEach(function(annotation) {
            const strokeColor = annotation.get('stroke');
            const notes = annotation.get('notes');
            if (notes != undefined) {
                let listItemHTML =
                    `<li class="notesParent mb-2">
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"
                                        style="background: white !important">
                                        <i class="fa fa-circle" style="color:${strokeColor}"></i>
                                    </span>
                                    <input type="text" class="form-control" name="formVehicle[${t}][notes]" value="${t+1}. ${notes}">
                                </div>
                            <input type="hidden" name="formVehicle[${t}][color]" value="${strokeColor}">
                        </li>
                    `;
                annotationsHTML += listItemHTML;
                listItemHTML = '';
                t++;
            }

        });
        // Hitung posisi tengah berdasarkan koordinat klik mouse
        const centerX = (path.left + path.width / 2);
        const centerY = (path.top + path.height / 2);
        const circle = new fabric.Circle({
            left: centerX - 15,
            top: centerY - 15,
            radius: 15, // Sesuaikan radius sesuai kebutuhan
            fill: 'rgba(0, 0, 0, 0)', // Set fill ke transparan (alpha = 0)
            stroke: color,
            strokeWidth: 5, // Sesuaikan lebar garis sesuai kebutuhan
            selectable: true
        });
var text = new fabric.Text(String(mobilioNumber), { 
            left: centerX - 4,
            top: centerY - 7,
            fontSize: 14,
            stroke:color,
            
        });
                canvas.add(text);

        // Tambahkan lingkaran ke canvas dan hapus path
        canvas.add(circle);
        // canvas.remove(path);

        // Render ulang canvas
        canvas.renderAll();

        // Render ulang path untuk menggambarnya dengan fill dan stroke yang sesuai
        path.render(canvas.contextContainer);
        canvas.requestRenderAll();

        document.getElementById('annotationsList').innerHTML = annotationsHTML;
        // Clear the tempCanvas
        tempContext.clearRect(0, 0, tempCanvas.width, tempCanvas.height);
        tempContext.drawImage(canvas.getElement(), 0, 0, canvas.width, canvas.height);
        // Redraw all the annotations onto the tempCanvas
        annotations.forEach(function(annotation) {
            annotation.render(tempContext);
        });
        // Convert the entire canvas to a data URL
        let canvasDataUrl = tempCanvas.toDataURL("image/png");
        // Set the data URL to the input element
        let canvasDataInput = document.getElementById('canvasDataInputGranmax');
        canvasDataInput.value = canvasDataUrl;
        mobilioNumber++;
    });




    document.getElementById('undoButtonGranmax').addEventListener('click', function() {
        const objects = canvas.getObjects();
        // const lastObject = canvas.getObjects();
        if (objects.length > 0) {
            const lastObject = objects[objects.length - 2];
            const lastObjectIndex = objects[objects.length - 1];
            const number = objects[objects.length - 3];            
            canvas.remove(number);
            canvas.remove(lastObject);
            canvas.remove(lastObjectIndex);

            canvas.renderAll();
    
            $('.notesParent').last().remove();
                    mobilioNumber--;

        }
    });
    document.getElementById('undoButtonMobilio').addEventListener('click', function() {
        const objects = canvasGranmax.getObjects();
        // const lastObject = canvasGranmax.getObjects();
        if (objects.length > 0) {
            const lastObject = objects[objects.length - 2];
            const lastObjectIndex = objects[objects.length - 1];
            const number = objects[objects.length - 3];
            canvasGranmax.remove(lastObject);
            canvasGranmax.remove(lastObjectIndex);
            canvasGranmax.remove(number);
            canvasGranmax.renderAll();

            $('.notesParent').last().remove();
                    granmaxNumber--;

        }
    });
</script>

{{-- ! GOOGLE MAPS --}}
<script>
    /**
     * @license
     * Copyright 2019 Google LLC. All Rights Reserved.
     * SPDX-License-Identifier: Apache-2.0
     */
    function initMap() {
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer();
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: {
                lat: -2.946603262626756,
                lng: 104.78541701074731,
            },
        });
        directionsRenderer.setMap(map);
        document.getElementById("submit").addEventListener("click", () => {
            calculateAndDisplayRoute(directionsService, directionsRenderer);
        });
    }

    function calculateAndDisplayRoute(directionsService, directionsRenderer) {
        const waypts = [];
        const checkboxArray = document.getElementsByClassName("waypoints");
        const selectedOptionsInput = document.getElementById("selectedOptionsInput");
        let location = [];
// console.log('::::' + checkboxArray[0].value);
        for (let i = 0; i < checkboxArray.length; i++) {
            const selectElement = checkboxArray[i];
            if (selectElement && selectElement.selectedOptions) {
                const selectedOptions = Array.from(selectElement.selectedOptions);
                
    
                selectedOptions.forEach(function(option) {
                    waypts.push({
                        location: option.value,
                        stopover: true,
                    });
                    if (option.dataset.nama != null) {
                        location.push(
                            option.dataset.nama
                        );
                    } else {
                        location.push(
                            option.value
                        );
                    }
    
                });
            }else{
                waypts.push({
                    location: selectElement.value,
                    stopover: true,
                });
                
                location.push(
                    selectElement.value
                );
            }
        }
        // Simpan nilai option.value ke elemen input
        selectedOptionsInput.value = location;
        
        let startVal = document.getElementById("start").value;
        let temp_startVal = startVal;
        if (startVal == 'other') {
            startVal = $('.getOutsideStart').val();
        }
        let endVal = document.getElementById("end").value;
        if (endVal == 'other') {
            endVal = $('.getOutsideEnd').val();
        }

        directionsService
            .route({
                origin: startVal,
                destination: endVal,
                waypoints: waypts,
                optimizeWaypoints: false,
                travelMode: google.maps.TravelMode.DRIVING,
            })
            .then((response) => {

                directionsRenderer.setDirections(response);
                const route = response.routes[0];
                const summaryPanel = document.getElementById("directions-panel");

                //awal
                const selectElementAwal = document.getElementById("start");
                const selectedOptionsAwal = Array.from(selectElementAwal.selectedOptions);
                const selectedDataNamesAwal = selectedOptionsAwal.map(option => option.dataset.nama);

                //akhir
                const selectElementAkhir = document.getElementById("end");
                const selectedOptionsAkhir = Array.from(selectElementAkhir.selectedOptions);
                const selectedDataNamesAkhir = selectedOptionsAkhir.map(option => option.dataset.nama);

                if(temp_startVal == 'other'){
                    document.getElementById('awal').value = startVal;
                    document.getElementById('akhir').value = endVal;
                }else{
                    document.getElementById('awal').value = selectedDataNamesAwal;
                    document.getElementById('akhir').value = selectedDataNamesAkhir;
                }
                

                summaryPanel.innerHTML = "";
                let total = 0;
                var startLat = 0;
                var startLng = 0;
                var endLat = 0;
                var endLng = 0;
                // For each route, display summary information.
                for (let i = 0; i < route.legs.length; i++) {
                    const routeSegment = i + 1;
                    summaryPanel.innerHTML += "<b>Route Trip: " + routeSegment + "</b><br>";
                    summaryPanel.innerHTML += route.legs[i].start_address +
                        " <i class='fa fa-arrow-right text-success'></i> ";
                    summaryPanel.innerHTML += route.legs[i].end_address + "<br>";
                    summaryPanel.innerHTML += "<div class='badge badge-warning'>" + route.legs[i].distance.text +
                        "</div><br><br>";
                    startLat = route.legs[i].start_location.lat();
                    startLng = route.legs[i].start_location.lng();
                    endLat = route.legs[i].end_location.lat();
                    endLng = route.legs[i].end_location.lng();
                    total += Math.round(route.legs[i].distance.value / 1000);
                }
                console.log(total);
                let tranVal = $('.transports').val();
                if (tranVal == 'Operational Vehicle') {
                    $('#toll').attr('hidden', false);
                    $('#fuel').attr('hidden', false);

                    let fuel_price = $('.fuel_price_').val();
                    $(document).find('.transport_').val((total / 10) * fuel_price);
                    $(document).find('.transport').val(((total / 10) * fuel_price).toLocaleString("EN-en"));
                    $(document).find('.distance').val(total.toLocaleString("EN-en"));
                    let totalCashAdvance = 0;
                    let transport = $('.transport_').val();
                    let accomodation = $('.accomodation_').val();
                    let other = $('.other_').val();

                    if (isNaN(transport)) {
                        transport = 0;
                    }
                    if (isNaN(accomodation)) {
                        accomodation = 0;
                    }
                    if (isNaN(other)) {
                        other = 0;
                    }
                    totalCashAdvance = parseInt(transport) + parseInt(accomodation) + parseInt(other);
                    $('.totalCashAdvance').val(totalCashAdvance.toLocaleString("EN-en"));
                    $('.totalCashAdvance_').val(totalCashAdvance);
                    $('.transport').attr('readonly', true);
                    $('#formLoan').attr('hidden', false);
                    $('#transport_expense').val(transport);
                    $('#acomodation_expense').val(accomodation);
                    $('#other_expense').val(other);

                } else if (tranVal == 'Own Vehicle') {
                    $('#toll').attr('hidden', false);
                    $('#fuel').attr('hidden', false);

                    let fuel_price = $('.fuel_price_').val();
                    $(document).find('.transport_').val((total / 10) * fuel_price);
                    $(document).find('.transport').val(((total / 10) * fuel_price).toLocaleString("EN-en"));
                    $(document).find('.distance').val(total.toLocaleString("EN-en"));
                    let totalCashAdvance = 0;
                    let transport = $('.transport_').val();
                    let accomodation = $('.accomodation_').val();
                    let other = $('.other_').val();

                    if (isNaN(transport)) {
                        transport = 0;
                    }
                    if (isNaN(accomodation)) {
                        accomodation = 0;
                    }
                    if (isNaN(other)) {
                        other = 0;
                    }
                    totalCashAdvance = parseInt(transport) + parseInt(accomodation) + parseInt(other);
                    $('.totalCashAdvance').val(totalCashAdvance.toLocaleString("EN-en"));
                    $('.totalCashAdvance_').val(totalCashAdvance);
                    $('.transport').attr('readonly', true);
                    $('#formLoan').attr('hidden', true);
                    $('#transport_expense').val(transport);
                    $('#acomodation_expense').val(accomodation);
                    $('#other_expense').val(other);

                } else {
                    let totalCashAdvance = 0;
                    $('.transport').val(0);
                    $('.transport_').val(0);
                    $('.distance').val(0);
                    let transport = $('.transport_').val();
                    let accomodation = $('.accomodation_').val();
                    let other = $('.other_').val();
                    $(document).find('.distance').val(total.toLocaleString("EN-en"));

                    if (isNaN(transport)) {
                        transport = 0;
                    }
                    if (isNaN(accomodation)) {
                        accomodation = 0;
                    }
                    if (isNaN(other)) {
                        other = 0;
                    }
                    totalCashAdvance = parseInt(transport) + parseInt(accomodation) + parseInt(other);
                    $('.totalCashAdvance').val(totalCashAdvance.toLocaleString("EN-en"));
                    $('.totalCashAdvance_').val(totalCashAdvance);
                    $('.transport').attr('readonly', false);
                    $('.transport').val(parseInt(transport).toLocaleString("EN-en"));
                    $('#formLoan').attr('hidden', true);
                    $('#transport_expense').val(transport);
                    $('#acomodation_expense').val(accomodation);
                    $('#other_expense').val(other);
                    $('#toll').attr('hidden', true);
                    $('#fuel').attr('hidden', true);


                }
            })
            .catch((e) => window.alert("Directions request failed due to " + e.message));

    }
    window.initMap = initMap;
</script>

{{-- ! JS Lain Lain --}}
<script>
    $(document).ready(function() {
        // set CSRF Token

        var csrf = $('meta[name="csrf-token"]').attr('content');
        var partner = 1;
        var i = 1;

        // validasi form
        $(function() {
            let validator = $('form.needs-validation').jbvalidator({
                errorMessage: true,
                successClass: false,
                language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
            });
        });

        // set datepicker
        $(document).find('.datepicker-here').datepicker({
            onSelect: function(formattedDate, date, inst) {
                inst.hide();
            },
        });

        // set modal maps
        $(document).on('change', '.transports', function() {
            let valTransport = $(this).val();
            if (valTransport == 'Operational Vehicle' || valTransport == 'Public Transport' ||
                valTransport == 'Own Vehicle') {
                $('#detailForm').modal('show');
            }
        });

        // add partner
        $(document).on('click', '.addPartner', function() {
            partner++;
            let form = `<div class="row">
                            <div class="form-group mx-auto col-2 col-lg-1">
                                    <label for="">&nbsp;</label>
                                    <br>
                                    <button class="btn btn-danger remPartner">-</button>
                            </div>
                            <div class="form-group mx-auto col-9 col-lg-4 ">
                                    <label>Name</label>
                                    <select name="formPartner[${partner}][id_employee]"  class="form-control select-employee"
                                        multiple>
                                    </select>
                            </div>
                            <div class="form-group col-lg-3">
                                    <label>NIK</label>
                                    <input type="text" class="form-control nik"  value=""
                                        placeholder="Select employee first" readonly>
                            </div>
                            <div class="form-group col-lg-4">
                                    <label>Phone</label>
                                    <input type="text" class="form-control phone"  value=""
                                        placeholder="Select employee first" readonly>
                            </div>
                        </div>`;
            $(document).find('#formPartner').append(form);
            var currentForm = $(document).find('#formPartner').children().last();
            $(document).find('.select-employee').select2({
                placeholder: 'Select an option',
                allowClear: true,
                maximumSelectionLength: 1,
                width: '100%',
                ajax: {
                    context: this,
                    type: "GET",
                    url: "/trip/get-employee/",
                    data: function(params) {
                        return {
                            _token: csrf,
                        };
                    },
                    dataType: "json",
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return [{
                                    text: item.name,
                                    id: item.id,
                                    nik: item.nik,
                                    phone: item.phone,
                                }, ];
                            }),
                        };
                    },
                },
            }).on('select2:select', function(e) {
                var selectedOption = $(this).select2('data')[0];
                var nik = selectedOption.nik;
                var phone = selectedOption.phone;
                currentForm.find('.nik').val(nik);
                currentForm.find('.phone').val(phone);
            });
        });

        // remove partner
        $(document).on('click', '.remPartner', function() {
            $(this).parent().parent().remove();
        });

        // set cash advance
        $(document).on('click', '.dp', function() {
            $('#delete').modal('show');
        });
        $('.bank').select2({
            placeholder: 'Select an option',
            allowClear: true,
            maximumSelectionLength: 1,
            width: '100%',
            ajax: {
                context: this,
                type: "GET",
                url: "/get_bank/",
                data: function(params) {
                    return {
                        q: params.term,
                        _token: csrf,
                    };
                },
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return [{
                                text: item.code + ' ' + item.name,
                                id: item.code + ' ' + item.name,
                            }];
                        }),
                    };
                },

            },

        });

        $('.getCity').select2({
            placeholder: 'Select an option',
            allowClear: true,
            maximumSelectionLength: 1,
            width: '100%',
            dropdownParent: $('#detailForm'),
            ajax: {
                context: this,
                type: "GET",
                url: "/get_cities/",
                data: function(params) {
                    return {
                        q: params.term,
                        _token: csrf,
                    };
                },
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return [{
                                text: item.city,
                                id: item.city,
                            }];
                        }),
                    };
                },

            },

        });

        // set modal select2
        $('.getRoute_,.select-destination,#start,#end').select2({
            placeholder: 'Select an option',
            allowClear: true,
            maximumSelectionLength: 1,
            width: '100%',
            dropdownParent: $('#detailForm'),
        });
        $('.transports,.vehicle').select2({
            placeholder: 'Select an option',
            allowClear: true,
            maximumSelectionLength: 1,
            width: '100%',
        });
        
        //Start Point select other location
        $('#start').on('change', function() {
            
            let val = $(this).val()[0];
            if (val == 'other') {
                $(this).attr('name', '');
                $('.outsideStart_').show();
            } else {
                $(this).attr('name', 'start');
                $('.outsideStart_').hide();
                $('.getOutsideStart').val('');
            }
        });

        //End Point select other location
        $('#end').on('change', function() {
            let val = $(this).val()[0];
            if (val == 'other') {
                $('.outsideEnd_').show();
            } else {
                $('.outsideEnd_').hide();
                $('.getOutsideEnd').val('');
            }
        });

        //  set cash advance (hitung otomatis)
        $('.transport,.accomodation,.other,.fuel_price,.toll_cost').on('input', function(event) {
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
            input = input.replace(/[\D\s\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            $this.val(function() {
                return input.toLocaleString("EN-en");
            });
            $this.next().val(input);
        });

        //  set cash advance (hitung otomatis)
        $('.transport,.accomodation,.other,.toll_cost,.fuel_price').on('focusout', function(event) {
            let fuel_price = $('.fuel_price_').val();
            let toll_cost = $('.toll_cost_').val();
            let transport = '';
            let accomodation = $('.accomodation_').val();
            let other = $('.other_').val();
            let totalCashAdvance = 0;
            let distance = $('.distance').val();
            let valTransport = $('.transports').val();

            if (valTransport == 'Public Transport') {
                transport = $('.transport_').val();
                fuel_price = 10000;
                $('.transport').val(transport.toLocaleString("EN-en"));

            } else {
                fuel_price = $('.fuel_price_').val();
                //hilangkan , pada distance
                distance = distance.replace(/,/g, '');
                transport = (parseInt(distance) / 10) * parseInt(fuel_price);

            }


            totalCashAdvance = parseInt(toll_cost) + parseInt(transport) +
                parseInt(accomodation) + parseInt(other);
            $(document).find('.transport').val(transport.toLocaleString("EN-en"));
            $('.totalCashAdvance').val(totalCashAdvance.toLocaleString("EN-en"));
            $('.totalCashAdvance_').val(totalCashAdvance);
            $('.transport').val(parseInt(transport).toLocaleString("EN-en"));
            $('#fuel_price').val(fuel_price);
            $('#toll_cost').val(toll_cost);
            $('#transport_expense').val(transport);
            $('#acomodation_expense').val(accomodation);
            $('#other_expense').val(other);
        });

        // add new location
        $(document).on('click', '.addLocation', function() {
            i++;
            let formLocation = `
            <div class="row mb-3">
                <div class="my-auto col-2 col-lg-2">
                    <label for="">&nbsp;</label>
                    <a type="button" class="text-center addLocation btn btn-sm btn-primary">+</a>
                </div>
                <div class="my-auto col-2 col-lg-2">
                    <label for="">&nbsp;</label>
                    <a type="button" class="text-center remLocation btn btn-sm btn-danger">-</a>
                </div>
                <div class=" col-8 col-lg-8">
                    <label>Route :</label>
                    <select name="location[${i}]['waypoint']" class="form-select getRoute_ waypoints" multiple >
                        <option value="other">Other Location</option>
                        <option value="outside">Inter-island Trip</option>
                    </select>
                    <div hidden class="otherRoute_ mt-1">
                        <select name="" class="getCity form-control " multiple>
                        </select>
                    </div>
                    <div style="display:none" class="outsideRoute_ mt-1">
                        <input type="text" name="" class="form-control getOutside" placeholder="Enter the place name..." />
                    </div>
                </div>
            </div>
                                    `;

            $(document).find('#formLocation').append(formLocation);
            $(document).on('change', '.getRoute_', function() {
                let route = $(this).val();
                let attrName = $(this).attr('name');
                if (route == 'other') {
                    $(this).siblings('.otherRoute_').attr('hidden', false);
                    $(this).siblings('.outsideRoute_').hide();
                    $(this).attr('name', '');
                    $(this).siblings('.otherRoute_').children('.getCity').attr('name',
                        attrName);
                    $(this).siblings('.outsideRoute_').children('.getOutside').attr('name',
                        '');
                    $(this).removeClass('waypoints');
                    $(this).siblings('.otherRoute_').children('.getCity').addClass(
                        'waypoints');
                    $(this).siblings('.outsideRoute_').children('.getOutside').removeClass(
                        'waypoints');
                } else if(route == 'outside'){
                    $(this).siblings('.otherRoute_').attr('hidden', true);
                    $(this).siblings('.outsideRoute_').show();
                    $(this).attr('name', '');
                    $(this).siblings('.otherRoute_').children('.getCity').attr('name',
                        '');
                    $(this).siblings('.outsideRoute_').children('.getOutside').attr('name',
                        attrName);
                    $(this).removeClass('waypoints');
                    $(this).siblings('.otherRoute_').children('.getCity').removeClass(
                        'waypoints');
                    $(this).siblings('.outsideRoute_').children('.getOutside').addClass(
                        'waypoints');
                } else {
                    $(this).siblings('.otherRoute_').attr('hidden', true);
                    $(this).siblings('.outsideRoute_').hide();
                    $(this).siblings('.otherRoute_').children('.getCity').attr('name',
                        '');
                    $(this).siblings('.outsideRoute_').children('.getOutside').attr('name',
                        '');
                    $(this).attr('name', attrName);
                    $(this).addClass('waypoints');
                    $(this).siblings('.otherRoute_').children('.getCity').removeClass(
                        'waypoints');
                    $(this).siblings('.outsideRoute_').children('.getOutside').removeClass(
                        'waypoints');    
                }
            })
            $(document).on('change', '.getRoute_', function() {
                const selectedOption = $(this).find('option:selected');
                const dataNama = selectedOption.attr('data-nama', selectedOption.text());
                console.log('Data Nama:', dataNama);
            });

            $('.getRoute_').select2({
                placeholder: 'Select an option',
                allowClear: true,
                maximumSelectionLength: 1,
                width: '100%',
                dropdownParent: $('#detailForm'),
                ajax: {
                    context: this,
                    type: "GET",
                    url: "/customer/get_select/",
                    data: function(params) {
                        return {
                            q: params.term,
                            _token: csrf,
                        };
                    },
                    dataType: "json",
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: [{
                                text: 'Other Location',
                                id: 'other'
                            },{
                                text: 'Inter-island Trip',
                                id: 'outside'
                            }].concat($.map(data, function(item) {
                                return {
                                    text: item.code_cust + ' - ' + item
                                        .name_cust,
                                    id: item.coordinate
                                };
                            })),
                        };
                    },



                },
            });
            $(".getCity").select2({
                placeholder: 'Select an option',
                allowClear: true,
                maximumSelectionLength: 1,
                width: '100%',
                dropdownParent: $('#detailForm'),
                ajax: {
                    context: this,
                    type: "GET",
                    url: "/get_cities/",
                    data: function(params) {
                        return {
                            q: params.term,
                            _token: csrf,
                        };
                    },
                    dataType: "json",
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return [{
                                    text: item.city,
                                    id: item.city,
                                }];
                            }),
                        };
                    },

                },

            });
        });

        // remove location
        $(document).on('click', '.remLocation', function() {
            $(this).parent().parent().remove();
        });
        $(document).on('change', '.getRoute_', function() {
            let route = $(this).val();
            let attrName = $(this).attr('name');
            if (route == 'other') {
                    $(this).siblings('.otherRoute_').attr('hidden', false);
                    $(this).siblings('.outsideRoute_').hide();
                    $(this).attr('name', '');
                    $(this).siblings('.otherRoute_').children('.getCity').attr('name',
                        attrName);
                    $(this).siblings('.outsideRoute_').children('.getOutside').attr('name',
                        '');
                    $(this).removeClass('waypoints');
                    $(this).siblings('.otherRoute_').children('.getCity').addClass(
                        'waypoints');
                    $(this).siblings('.outsideRoute_').children('.getOutside').removeClass(
                        'waypoints');
                } else if(route == 'outside'){
                    $(this).siblings('.otherRoute_').attr('hidden', true);
                    $(this).siblings('.outsideRoute_').show();
                    $(this).attr('name', '');
                    $(this).siblings('.otherRoute_').children('.getCity').attr('name',
                        '');
                    $(this).siblings('.outsideRoute_').children('.getOutside').attr('name',
                        attrName);
                    $(this).removeClass('waypoints');
                    $(this).siblings('.otherRoute_').children('.getCity').removeClass(
                        'waypoints');
                    $(this).siblings('.outsideRoute_').children('.getOutside').addClass(
                        'waypoints');
                } else {
                    $(this).siblings('.otherRoute_').attr('hidden', true);
                    $(this).siblings('.outsideRoute_').hide();
                    $(this).siblings('.otherRoute_').children('.getCity').attr('name',
                        '');
                    $(this).siblings('.outsideRoute_').children('.getOutside').attr('name',
                        '');
                    $(this).attr('name', attrName);
                    $(this).addClass('waypoints');
                    $(this).siblings('.otherRoute_').children('.getCity').removeClass(
                        'waypoints');
                    $(this).siblings('.outsideRoute_').children('.getOutside').removeClass(
                        'waypoints');    
                }
        })

    });
</script>
