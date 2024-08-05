<script>
    $(document).ready(function() {
        $('#start_date, #end_date').datepicker({
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
        });
        $('.datepicker-here').datepicker({
            onSelect: function(formattedDate, date, inst) {
                inst.hide();
            },
        });

        function parseDate(date) {
            let now = date;
            // Format the date as "dd-mm-yyyy"
            let day = now.getDate().toString().padStart(2, '0');
            let month = (now.getMonth() + 1).toString().padStart(2, '0');
            let year = now.getFullYear();
            let formattedDate = `${day}-${month}-${year}`;
            return formattedDate;
        }
        // Get the current date


        // Set the value of the input element
        document.querySelector('input[name="from_date"]').value = parseDate(new Date());
        document.querySelector('input[name="to_date"]').value = parseDate(new Date());
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on("click", ".modal-btn2", function(event) {

            let modal_id = $(this).attr('data-bs-target');
            var id = $(modal_id).find('.id').val();

            $(modal_id).find('.transports,.vehicle').select2({
                placeholder: 'Select an option',
                allowClear: true,
                maximumSelectionLength: 1,
                width: '100%',
                dropdownParent: modal_id
            });
            $(modal_id).find('.transports').change(function() {
                let valTransport = $(this).val();
                if (valTransport == 'Operational Vehicle') {
                    $(modal_id).find('.purpose').removeClass('col-lg-6');
                    $(modal_id).find('.purpose').addClass('col-lg-4');
                    $(modal_id).find('.notes').removeClass('col-lg-6');
                    $(modal_id).find('.notes').addClass('col-lg-4');
                    $(modal_id).find('.transport_').removeClass('col-lg-6');
                    $(modal_id).find('.transport_').addClass('col-lg-4');
                    $(modal_id).find('.vehicle_').removeClass('d-none');
                    $(modal_id).find('.vehicle_').removeClass('col-lg-6');
                    $(modal_id).find('.vehicle_').addClass('col-lg-4');
                    $(modal_id).find('.distance_').removeClass('col-lg-6');
                    $(modal_id).find('.distance_').addClass('col-lg-4');
                    $(modal_id).find('.kendaraan').attr('hidden', false);
                    $(modal_id).find('.anotasi').attr('hidden', false);
                    $(modal_id).find('.hideVehicle').attr('hidden', false);


                } else {
                    $(modal_id).find('.purpose').addClass('col-lg-6');
                    $(modal_id).find('.purpose').removeClass('col-lg-4');
                    $(modal_id).find('.notes').addClass('col-lg-6');
                    $(modal_id).find('.notes').removeClass('col-lg-4');
                    $(modal_id).find('.transport_').removeClass('col-lg-4');
                    $(modal_id).find('.transport_').addClass('col-lg-6');
                    $(modal_id).find('.vehicle_').addClass('d-none');
                    $(modal_id).find('.vehicle_').removeClass('col-lg-4');
                    $(modal_id).find('.vehicle_').addClass('col-lg-6');
                    $(modal_id).find('.distance_').removeClass('col-lg-4');
                    $(modal_id).find('.distance_').addClass('col-lg-6');
                    $(modal_id).find('.kendaraan').attr('hidden', true);
                    $(modal_id).find('.anotasi').attr('hidden', true);
                    $(modal_id).find('.hideVehicle').attr('hidden', true);
                }
            });
            let valVehicle = $(modal_id).find('.vehicle').val();
            $(modal_id).find('#default' + id).attr('hidden', false);
            $(modal_id).find('.vehicle').change(function() {
                let valVehicle_ = $(this).val();
                if (valVehicle_ && Array.isArray(valVehicle_) && valVehicle_.length > 0) {
                    // console.log(valVehicle_[0]);
                    // if (valVehicle.includes(valVehicle_[0])) {
                    //     //nambahin coretan
                    //     console.log('sama');
                    //     $(modal_id).find('.kendaraan' + id).removeClass('d-none');
                    //     $(modal_id).find('.anotasi' + id).removeClass('d-none');
                    //     $(modal_id).find('#default' + id).attr('hidden', false);
                    //     $(modal_id).find('#mobilio' + id).attr('hidden', true);
                    //     $(modal_id).find('#granmax' + id).attr('hidden', true);
                    //     $(modal_id).find('#annotationsListDefault' + id).attr('hidden', false);
                    // } else 
                    if (valVehicle_[0].includes('Granmax')) {
                        //granmax
                        $(modal_id).find('.kendaraan' + id).removeClass('d-none');
                        $(modal_id).find('.anotasi' + id).removeClass('d-none');
                        $(modal_id).find('#default' + id).attr('hidden', true);
                        $(modal_id).find('#mobilio' + id).attr('hidden', true);
                        $(modal_id).find('#granmax' + id).attr('hidden', false);
                        $(modal_id).find('#annotationsListDefault' + id).attr('hidden', true);
                    } else if (valVehicle_[0].includes('Mobilio')) {
                        //mobilio
                        $(modal_id).find('.kendaraan' + id).removeClass('d-none');
                        $(modal_id).find('.anotasi' + id).removeClass('d-none');
                        $(modal_id).find('#default' + id).attr('hidden', true);
                        $(modal_id).find('#mobilio' + id).attr('hidden', false);
                        $(modal_id).find('#granmax' + id).attr('hidden', true);
                        $(modal_id).find('#annotationsListDefault' + id).attr('hidden', true);
                    }
                }
            });


            // default
            var mobilVariable = document.getElementById('mobilDefault' + id).value;
            const canvasVariable = new fabric.Canvas('canvasDefault' + id);
            fabric.Image.fromURL(mobilVariable, function(img) {
                canvasVariable.setBackgroundImage(img, canvasVariable.renderAll.bind(
                    canvasVariable));
            });
            canvasVariable.isDrawingMode = true;
            canvasVariable.freeDrawingBrush.width = 1;
            const colorsVariable = [
        '#00FF00',
       
            ];
            let currentColorIndexVariable = 0;

            // Create a temporary canvas element
            let tempCanvasVariable = document.createElement('canvas');
            tempCanvasVariable.width = canvasVariable.width;
            tempCanvasVariable.height = canvasVariable.height;
            let tempContextVariable = tempCanvasVariable.getContext('2d');
            let defaultNumber = 1;
            canvasVariable.on('path:created', function(e) {
                const path = e.path;
                const color = colorsVariable[currentColorIndexVariable];
                const notes = prompt('Enter notes for this annotation (Color: ' + color + '):');
                path.set('notes', notes);
                path.set('stroke', color);
                currentColorIndexVariable = (currentColorIndexVariable + 1) % colorsVariable
                    .length;

                const annotations = canvasVariable.getObjects();
                let annotationsHTML = '';
                let k = document.getElementById('annotationsListDefault' + id).getAttribute(
                    'loop');

                annotations.forEach(function(annotation) {

                    const strokeColor = annotation.get('stroke');
                    const notes = annotation.get('notes');
                    if (notes != undefined) {
                        const listItemHTML =
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
                        k++;
                    }

                });


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
var text = new fabric.Text(String(defaultNumber), { 
            left: centerX - 4,
            top: centerY - 7,
            fontSize: 14,
            stroke:color,
            
        });
        canvasVariable.add(text);
        
                // Tambahkan lingkaran ke canvas dan hapus path
                canvasVariable.add(circle);
                // canvasVariable.remove(path);

                // Render ulang canvas
                canvasVariable.renderAll();

                // Render ulang path untuk menggambarnya dengan fill dan stroke yang sesuai
                path.render(canvasVariable.contextContainer);
                canvasVariable.requestRenderAll();
                document.getElementById('annotationsList' + id).innerHTML = annotationsHTML;

                // Clear the tempCanvasVariable
                tempContextVariable.clearRect(0, 0, tempCanvasVariable.width, tempCanvasVariable
                    .height);
                tempContextVariable.drawImage(canvasVariable.getElement(), 0, 0, canvasVariable
                    .width, canvasVariable.height);

                // Redraw all annotations on tempCanvasVariable
                annotations.forEach(function(annotation) {
                    annotation.render(tempContextVariable);
                });

                // Convert the entire canvas to data URL
                let canvasDataUrlVariable = tempCanvasVariable.toDataURL("image/png");

                // Set the data URL to an input element
                let canvasDataInputVariable = document.getElementById(
                    'canvasDataInputVariable' + id);
                canvasDataInputVariable.value = canvasDataUrlVariable;
                defaultNumber++;
            });


            // mobilio
            var mobilGranmax = document.getElementById('mobilMobilio' + id).value;
            const canvasGranmax = new fabric.Canvas('canvasVehicle' + id);
            fabric.Image.fromURL(mobilGranmax, function(img) {
                canvasGranmax.setBackgroundImage(img, canvasGranmax.renderAll.bind(
                    canvasGranmax));
            });
            canvasGranmax.isDrawingMode = true;
            canvasGranmax.freeDrawingBrush.width = 5;
            const colorsGranmax = [
        '#00FF00',
        
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
                const notes = prompt('Enter notes for this annotation (Color: ' + color + '):');
                path.set('notes', notes);
                path.set('stroke', color);
                currentColorIndexGranmax = (currentColorIndexGranmax + 1) % colorsGranmax
                    .length;
                const annotations = canvasGranmax.getObjects();
                let annotationsHTML = '';
                let k = 1;
                annotations.forEach(function(annotation) {
                    const strokeColor = annotation.get('stroke');
                    const notes = annotation.get('notes');

                    if (notes != undefined) {
                        const listItemHTML =
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
                        k++;

                    }

                });
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
                path.render(canvasGranmax.contextContainer);
                canvasGranmax.requestRenderAll();
                document.getElementById('annotationsList' + id).innerHTML = annotationsHTML;
                // Bersihkan tempCanvasGranmax
                tempContextGranmax.clearRect(0, 0, tempCanvasGranmax.width, tempCanvasGranmax
                    .height);
                tempContextGranmax.drawImage(canvasGranmax.getElement(), 0, 0, canvasGranmax
                    .width, canvasGranmax
                    .height);
                // Gambar ulang semua annotasi ke tempCanvasGranmax
                annotations.forEach(function(annotation) {
                    annotation.render(tempContextGranmax);
                });
                // Konversi seluruh canvas menjadi data URL
                let canvasDataUrlGranmax = tempCanvasGranmax.toDataURL("image/png");
                // Set data URL ke elemen input
                let canvasDataInputGranmax = document.getElementById('canvasDataInputMobilio' +
                    id);
                canvasDataInputGranmax.value = canvasDataUrlGranmax;
                granmaxNumber++;
            });

            // granmax
            var mobil = document.getElementById('mobilGranMax' + id).value;
            const canvas = new fabric.Canvas('canvas' + id);
            fabric.Image.fromURL(mobil, function(img) {
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
                // var canvasGranmax = document.getElementById('canvas');

            });
            canvas.isDrawingMode = true;
            canvas.freeDrawingBrush.width = 5;
            const colors = [
        '#00FF00',
        
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
                const notes = prompt('Enter notes for this annotation (Color: ' + color + '):');
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
                        const listItemHTML =
                            `<li class="notesParent mb-2">
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"
                                        style="background: white !important">
                                        <i class="fa fa-circle" style="color:${strokeColor}"></i>
                                    </span>
                                    <input type="text" class="form-control" name="formVehicle[${t}][notes]" value="${t}. ${notes}">
                                </div>
                            <input type="hidden" name="formVehicle[${t}][color]" value="${strokeColor}">
                        </li>
                     `;
                        annotationsHTML += listItemHTML;
                        t++;
                    }

                });
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

                // Tambahkan lingkaran ke canvas dan hapus path
                canvas.add(circle);
                // canvas.remove(path);
var text = new fabric.Text(String(mobilioNumber), { 
            left: centerX - 4,
            top: centerY - 7,
            fontSize: 14,
            stroke:color,
            
        });
                        canvas.add(text);

                // Render ulang canvas
                canvas.renderAll();
                path.render(canvas.contextContainer);
                canvas.requestRenderAll();
                document.getElementById('annotationsList' + id).innerHTML = annotationsHTML;

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
                let canvasDataInput = document.getElementById('canvasDataInputGranmax' + id);
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
                }
            });
            document.getElementById('undoButtonMobilio').addEventListener('click', function() {
                const objects = canvasGranmax.getObjects();
                // const lastObject = canvasGranmax.getObjects();
                if (objects.length > 0) {
                    const lastObject = objects[objects.length - 2];
                    const lastObjectIndex = objects[objects.length - 1];
                    const number = objects[objects.length - 3];            
                    canvasGranmax.remove(number);  
                    canvasGranmax.remove(lastObject);
                    canvasGranmax.remove(lastObjectIndex);
                    canvasGranmax.renderAll();

                    $('.notesParent').last().remove();
                }
            });
            document.getElementById('undoDefault').addEventListener('click', function() {
                const objects = canvasVariable.getObjects();
                // const lastObject = canvasVariable.getObjects();
                if (objects.length > 0) {
                    const lastObject = objects[objects.length - 2];
                    const lastObjectIndex = objects[objects.length - 1];
                     const number = objects[objects.length - 3];            
                    canvasVariable.remove(number); 
                    canvasVariable.remove(lastObject);
                    canvasVariable.remove(lastObjectIndex);
                    canvasVariable.renderAll();

                    $('.notesParent').last().remove();
                }
            });





        })



        load_data();

        function load_data(from_date = '', to_date = '') {

            $('#dataTable').DataTable({
                "responsive": true,
                "language": {
                    "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                },
                "lengthChange": false,
                "bPaginate": false, // disable pagination
                "bLengthChange": false, // disable show entries dropdown
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": false, // disable automatic column width
                destroy: true,
                // rowsGroup: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 25, 26, 27],
                processing: true,
                serverSide: true,
                pageLength: -1,
                ajax: {
                    url: "{{ url('trip/approval') }}",
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                    }
                },
                columns: [{
                        className: 'dtr-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    }, {
                        className: 'text-end fw-bold',
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'trip_number',
                        name: 'trip_number',
                    },
                    {
                        data: 'id_employee',
                        name: 'id_employee',
                    },
                    {
                        className: 'text-center',
                        data: 'departure_date',
                        name: 'departure_date',

                    },
                    {
                        className: 'text-center',
                        data: 'return_date',
                        name: 'return_date',

                    },
                    {
                        className: 'text-center',
                        data: 'status',
                        name: 'status',

                    },


                ],

            });
        }
        $('#filter').click(function() {
            // gunakan fungsi convertDate untuk mengubah format tanggal
            function formatDate(date) {
                // Split the date string into day, month, and year components
                let dateParts = date.split('-');

                // Create a new Date object using the year, month, and day components
                let dateObject = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);

                // Format the date as "yyyy-mm-dd"
                let year = dateObject.getFullYear();
                let month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
                let day = dateObject.getDate().toString().padStart(2, '0');
                let formattedDate = `${year}-${month}-${day}`;

                return formattedDate;
            }

            var from_date = formatDate($('#from_date').val());
            var to_date = formatDate($('#to_date').val());
            console.log(from_date + ' ' + to_date);
            if (from_date > to_date) {
                $.notify({
                    title: 'Warning',
                    message: 'Start Date must be less than End Date'
                }, {
                    type: 'danger',
                    allow_dismiss: true,
                    newest_on_top: true,
                    mouse_over: true,
                    showProgressbar: false,
                    spacing: 10,
                    timer: 1000,
                    placement: {
                        from: 'top',
                        align: 'right'
                    },
                    offset: {
                        x: 30,
                        y: 30
                    },
                    delay: 1000,
                    z_index: 10000,
                    animate: {
                        enter: 'animated bounceInDown',
                        exit: 'animated bounceInUp'
                    }
                });
                $('#from_date').val('');
                $('#to_date').val('');
            } else {
                if (from_date != '' && to_date != '') {
                    load_data(from_date, to_date);
                } else {
                    $.notify({
                        title: 'Warning',
                        message: 'Please Select Date'
                    }, {
                        type: 'warning',
                        allow_dismiss: true,
                        newest_on_top: true,
                        mouse_over: true,
                        showProgressbar: false,
                        spacing: 10,
                        timer: 1000,
                        placement: {
                            from: 'top',
                            align: 'right'
                        },
                        offset: {
                            x: 30,
                            y: 30
                        },
                        delay: 1000,
                        z_index: 10000,
                        animate: {
                            enter: 'animated bounceInDown',
                            exit: 'animated bounceInUp'
                        }
                    });
                }
            }
        });
        $('#refresh').click(function() {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#dataTable').DataTable().destroy();
            load_data();
        });

    });
</script>
